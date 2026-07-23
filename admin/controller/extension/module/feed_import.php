<?php
class ControllerExtensionModuleFeedImport extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/feed_import');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			// Save mapping to file
			$this->saveMapping();

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/feed_import', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/feed_import', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		$data['load_categories_url'] = 'index.php?route=extension/module/feed_import/loadCategories&user_token=' . $this->session->data['user_token'];

		// Get current mapping
		$data['feed_categories'] = array();
		$mapping = $this->getMapping();

		// Site categories are loaded via AJAX on button click
		$data['site_categories'] = array();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/feed_import', $data));
	}

	public function loadCategories() {
		$this->load->language('extension/module/feed_import');

		if (!$this->user->hasPermission('modify', 'extension/module/feed_import')) {
			$json['error'] = $this->language->get('error_permission');
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		$json = array();

		// Load feed data
		$xml_data = $this->loadFeedData();
		if ($xml_data === null) {
			$json['error'] = 'Не вдалося завантажити фід';
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		$xml = simplexml_load_string($xml_data);
		if ($xml === false) {
			$json['error'] = 'Не вдалося розпарсити XML';
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		$shop = $xml->shop;
		$categories = array();

		foreach ($shop->categories->category as $category) {
			$cat_id = (int)$category['id'];
			$parent_id = isset($category['parentId']) ? (int)$category['parentId'] : 0;
			$name = trim((string)$category);
			$categories[] = array(
				'feed_id' => $cat_id,
				'parent_id' => $parent_id,
				'name' => $name
			);
		}

		// Get current mapping and site categories
		$mapping = $this->getMapping();
		$site_categories = $this->getSiteCategories();

		// Build category tree with mapping
		$category_tree = array();
		foreach ($categories as $cat) {
			$category_tree[] = array(
				'feed_id' => $cat['feed_id'],
				'name' => $cat['name'],
				'parent_id' => $cat['parent_id'],
				'mapped_id' => isset($mapping[$cat['feed_id']]) ? $mapping[$cat['feed_id']] : 0,
				'indent' => 0
			);
		}

		// Calculate indent levels
		$category_tree = $this->buildTree($category_tree);

		$json['success'] = true;
		$json['categories'] = $category_tree;
		$json['site_categories'] = $site_categories;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function buildTree($categories) {
		$result = array();
		$children_map = array();

		// Build parent->children map
		foreach ($categories as $cat) {
			$children_map[$cat['parent_id']][] = $cat;
		}

		// Flatten with indentation
		$this->flattenTree($children_map, 0, 0, $result);

		return $result;
	}

	private function flattenTree($children_map, $parent_id, $depth, &$result) {
		if (!isset($children_map[$parent_id])) return;

		foreach ($children_map[$parent_id] as $cat) {
			$cat['indent'] = $depth;
			$result[] = $cat;
			$this->flattenTree($children_map, $cat['feed_id'], $depth + 1, $result);
		}
	}

	private function getMapping() {
		$map_file = DIR_SYSTEM . '../price/import/feed_import_mapping.php';
		if (file_exists($map_file)) {
			return require($map_file);
		}
		return array();
	}

	private function saveMapping() {
		if (!isset($this->request->post['mapping'])) return;

		$mapping = array();
		foreach ($this->request->post['mapping'] as $feed_id => $oc_id) {
			$feed_id = (int)$feed_id;
			$oc_id = (int)$oc_id;
			if ($feed_id > 0 && $oc_id > 0) {
				$mapping[$feed_id] = $oc_id;
			}
		}

		$map_file = DIR_SYSTEM . '../price/import/feed_import_mapping.php';
		$content = '<?php' . "\n";
		$content .= '/**' . "\n";
		$content .= ' * Маппинг категорий фида → категории OpenCart' . "\n";
		$content .= ' *' . "\n";
		$content .= ' * Ключ: ID категории из YML фида' . "\n";
		$content .= ' * Значение: category_id в OpenCart' . "\n";
		$content .= ' *' . "\n";
		$content .= ' * Автоматически сгенерировано модулем Feed Import Mapping.' . "\n";
		$content .= ' */' . "\n";
		$content .= 'return ' . var_export($mapping, true) . ';' . "\n";

		file_put_contents($map_file, $content);
	}

	private function getSiteCategories() {
		// Single query to get all categories instead of recursive calls
		$result = $this->db->query("SELECT c.category_id, cd.name, c.parent_id 
			FROM " . DB_PREFIX . "category c 
			LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) 
			WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			ORDER BY c.sort_order, cd.name");
		
		$all_cats = array();
		foreach ($result->rows as $row) {
			$all_cats[] = array(
				'category_id' => (int)$row['category_id'],
				'name' => $row['name'],
				'parent_id' => (int)$row['parent_id']
			);
		}

		// Build children map
		$children = array();
		foreach ($all_cats as $cat) {
			$children[$cat['parent_id']][] = $cat;
		}

		// Flatten with indentation
		$flat = array();
		$this->flattenSiteCategories($children, 0, 0, $flat);
		return $flat;
	}

	private function flattenSiteCategories($children, $parent_id, $depth, &$result) {
		if (!isset($children[$parent_id])) return;
		foreach ($children[$parent_id] as $cat) {
			$prefix = $depth > 0 ? str_repeat('&nbsp;&nbsp;&nbsp;', $depth) : '';
			$result[] = array(
				'category_id' => $cat['category_id'],
				'name' => $prefix . $cat['name']
			);
			$this->flattenSiteCategories($children, $cat['category_id'], $depth + 1, $result);
		}
	}

	private function loadFeedData() {
		// Load feed config to get constants
		$config_file = DIR_SYSTEM . '../price/import/config.php';
		if (!file_exists($config_file)) {
			return null;
		}
		
		// Get constants without require_once (to avoid DB constant conflicts)
		$config_content = file_get_contents($config_file);
		
		// Extract FEED_SOURCE
		$feed_source = null;
		$cache_file = DIR_SYSTEM . '../price/import/feed_cached.xml';
		$cache_ttl = 3600;
		
		if (preg_match("/define\('FEED_SOURCE',\s*'([^']+)'/", $config_content, $m)) {
			$feed_source = $m[1];
		}
		if (preg_match("/define\('FEED_CACHE_FILE',\s*'([^']+)'/", $config_content, $m)) {
			$cache_file = $m[1];
		}
		if (preg_match("/define\('FEED_CACHE_TTL',\s*(\d+)/", $config_content, $m)) {
			$cache_ttl = (int)$m[1];
		}
		
		if (!$feed_source) return null;

		// Try cache first
		if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_ttl) {
			return file_get_contents($cache_file);
		}

		// Download feed
		if (strpos($feed_source, 'http://') === 0 || strpos($feed_source, 'https://') === 0) {
			$data = @file_get_contents($feed_source);
			if ($data === false) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $feed_source);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 60);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				$data = curl_exec($ch);
				$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);
				if ($http_code != 200) return null;
			}
			if (empty($data)) return null;
			file_put_contents($cache_file, $data);
			return $data;
		}

		if (file_exists($feed_source)) {
			return file_get_contents($feed_source);
		}

		return null;
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/feed_import')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}