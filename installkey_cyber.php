<?php
echo '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
require_once 'config.php';

$dbhost = DB_HOSTNAME;
$dbuser = DB_USERNAME;
$dbpass = DB_PASSWORD;
$dbdatabase = DB_DATABASE;
$dbprefix = DB_PREFIX;

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbdatabase);
if (!$conn )
	die('Could not connect: ' . mysql_error());

/* activation cyberstore */
$file_lic      = 'cyberstore.php';
$license_key   = licenseKeyGen();
$local_key_dec = localKeyCreate($license_key);
$conn->query("UPDATE " . DB_PREFIX . "cyberstore_key SET `license_key`='" . $license_key . "' where `key`='local_key'");
file_put_contents('./' . $file_lic, $local_key_dec);
file_put_contents('./admin/' . $file_lic, $local_key_dec);

echo " CyberStore Key Successfully installed";

function licenseKeyGen()
{
    $key     = md5(time() . mt_rand(1, 5));
    $new_key = '';
    for ($i = 1; $i <= 25; $i++) {
        $new_key .= $key[$i];
        if ($i % 5 === 0 && $i !== 25) {
            $new_key .= '-';
        }
    }

    return strtoupper($new_key);
}

function localKeyCreate($key)
{

    $domain = str_replace("www.", "", $_SERVER['HTTP_HOST']);

    $instance             = array();
    $instance['domain'][] = $domain;
    $instance['domain'][] = 'www.' . $domain;

    $enforce   = array();
    $enforce[] = 'domain';

    $local_key['instance'] = $instance;
    $local_key['enforce']  = $enforce;

    $local_key['domain_wildcard']         = '2';
    $local_key['user_id']                 = 0;
    $l_phpversion                         = explode(".", phpversion());
    $l_phpversion                         = (int) $l_phpversion[0];
    $local_key['l_phpversion']            = $l_phpversion;
    $local_key['user_name']               = 'user_' . rand();
    $local_key['activation_key']          = $key;
    $local_key['license_started']         = (int) time();
    $local_key['activation_key_expires']  = 'never';
    $local_key['local_key_expires']       = 'never';
    $local_key['status']                  = 1;
    $local_key['custom_fields']           = array();
    $local_key['download_access_expires'] = 0;
    $local_key['support_access_expires']  = 0;

    $local_key = serialize($local_key);

    $encrypt_method  = "AES-256-CBC";
    $secret_key      = "0ppTa6EIjJhe75KL51KL4ZnD";
    $iv              = substr(hash('sha256', $secret_key), 0, 16);
    $license_info    = array();
    $license_info[0] = $local_key;
    $license_info[1] = md5($secret_key . $license_info[0]);
    $license_info[2] = md5(microtime());
    $license_info    = implode("{protect}", $license_info);
    $license_info    = openssl_encrypt(base64_encode($license_info), $encrypt_method, $secret_key, 0, $iv);
    $license_info    = base64_encode($license_info);
    return wordwrap($license_info, 64, "\n", 1);
}

?>