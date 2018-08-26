<?php
class ModelExtensionModuleDToolbar extends Model {
	private $codename = 'd_toolbar';
		
	/*
	*	Return list of installed Toolbar extensions.
	*/
	public function getInstalledToolbarExtensions() {
		$this->load->model('setting/setting');
				
		$installed_extensions = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension ORDER BY code");
		
		foreach ($query->rows as $result) {
			$installed_extensions[] = $result['code'];
		}
				
		$toolbar_extensions = array();
		
		$files = glob(DIR_APPLICATION . 'controller/extension/' . $this->codename . '/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$toolbar_extension = basename($file, '.php');
				
				if (in_array($toolbar_extension, $installed_extensions)) {
					$toolbar_extensions[] = $toolbar_extension;
				}
			}
		}
		
		return $toolbar_extensions;
	}
	
	/*
	*	Sort Array By Column.
	*/
	public function sortArrayByColumn($arr, $col, $dir = SORT_ASC) {
		$sort_col = array();
		$sort_key = array();
		
		foreach ($arr as $key => $row) {
			$sort_key[$key] = $key;
			
			if (isset($row[$col])) {
				$sort_col[$key] = $row[$col];
			} else {
				$sort_col[$key] = '';
			}
		}
		
		array_multisort($sort_col, $dir, $sort_key, SORT_ASC, $arr);
		
		return $arr;
	}
	
	/*
	*	Return Link.
	*/
	public function link($route, $args = '', $secure = false) {
		$url = $this->config->get('config_url') . 'admin/';
		$ssl = $this->config->get('config_ssl') . 'admin/';
		
		if ($ssl && $secure) {
			$url = $ssl . 'index.php?route=' . $route;
		} else {
			$url = $url . 'index.php?route=' . $route;
		}
		
		if ($args) {
			if (is_array($args)) {
				$url .= '&amp;' . http_build_query($args);
			} else {
				$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
			}
		}
				
		return $url; 
	}
}