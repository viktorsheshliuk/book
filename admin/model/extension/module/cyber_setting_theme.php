<?php
class ModelExtensionModuleCyberSettingTheme extends Model {

	public function saveSetting($data) {
		$store_id = 0;
		$code = 'cyber_store_theme';
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '". $store_id ."' AND `code` = '". $code ."'");

		foreach ($data as $key => $value) {
			if (!is_array($value)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
				} else {
					if (VERSION < 2.1) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
					} else {
						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value)) . "', serialized = '1'");
					}
				}
			}
			$this->generateCss($data);
	}

	public function generateCss($data){
		$file = DIR_CATALOG."view/theme/cyberstore/stylesheet/csseditor.css";
		$dataStr = "";
		$catalog = isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')) ? HTTPS_CATALOG : HTTP_CATALOG;

		if(isset($data['nst_data']['bg_mode_pos_2']) && ($data['nst_data']['bg_mode_pos_2'] == '1') && ($data['nst_data']['img_pos_2'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_2 {background-image:url(\'' . $catalog . 'image/'.$data['nst_data']['img_pos_2'].'\');}}';
		}
		if(isset($data['nst_data']['bg_mode_pos_2']) && ($data['nst_data']['bg_mode_pos_2'] == '2') && ($data['nst_data']['bg_mode_color_pos_2'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_2 {background-color:'.$data['nst_data']['bg_mode_color_pos_2'].';}}';
		}
		if(isset($data['nst_data']['title_color_pos_2']) && ($data['nst_data']['title_color_pos_2'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_2 .title-module span, .bg_mode_pos_2 h3{color:'.$data['nst_data']['title_color_pos_2'].' !important;}}';
		}

		if(isset($data['nst_data']['bg_mode_pos_22']) && ($data['nst_data']['bg_mode_pos_22'] == '1') && ($data['nst_data']['img_pos_22'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_22 {background-image:url(\'' . $catalog . 'image/'.$data['nst_data']['img_pos_22'].'\');}}';
		}
		if(isset($data['nst_data']['bg_mode_pos_22']) && ($data['nst_data']['bg_mode_pos_22'] == '2') && ($data['nst_data']['bg_mode_color_pos_22'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_22 {background-color:'.$data['nst_data']['bg_mode_color_pos_22'].';}}';
		}
		if(isset($data['nst_data']['title_color_pos_22']) && ($data['nst_data']['title_color_pos_22'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_22 .title-module span, .bg_mode_pos_22 h3{color:'.$data['nst_data']['title_color_pos_22'].' !important;}}';
		}

		if(isset($data['nst_data']['bg_mode_pos_11']) && ($data['nst_data']['bg_mode_pos_11'] == '1') && ($data['nst_data']['img_pos_11'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_11 {background-image:url(\'' . $catalog . 'image/'.$data['nst_data']['img_pos_11'].'\');}}';
		}
		if(isset($data['nst_data']['bg_mode_pos_11']) && ($data['nst_data']['bg_mode_pos_11'] == '2') && ($data['nst_data']['bg_mode_color_pos_11'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_11 {background-color:'.$data['nst_data']['bg_mode_color_pos_11'].';}}';
		}
		if(isset($data['nst_data']['title_color_pos_11']) && ($data['nst_data']['title_color_pos_11'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_11 .title-module span, .bg_mode_pos_11 h3{color:'.$data['nst_data']['title_color_pos_11'].' !important;}}';
		}

		if(isset($data['nst_data']['bg_mode_pos_15']) && ($data['nst_data']['bg_mode_pos_15'] == '1') && ($data['nst_data']['img_pos_15'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_15 {background-image:url(\'' . $catalog . 'image/'.$data['nst_data']['img_pos_15'].'\');}}';
		}
		if(isset($data['nst_data']['bg_mode_pos_15']) && ($data['nst_data']['bg_mode_pos_15'] == '2') && ($data['nst_data']['bg_mode_color_pos_15'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_15 {background-color:'.$data['nst_data']['bg_mode_color_pos_15'].';}}';
		}
		if(isset($data['nst_data']['title_color_pos_15']) && ($data['nst_data']['title_color_pos_15'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_15 .title-module span, .bg_mode_pos_15 h3{color:'.$data['nst_data']['title_color_pos_15'].' !important;}}';
		}
		file_put_contents($file,$dataStr);
	}
}