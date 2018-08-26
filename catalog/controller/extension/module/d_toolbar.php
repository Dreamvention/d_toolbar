<?php
class ControllerExtensionModuleDToolbar extends Controller {
	private $codename = 'd_toolbar';
	private $route = 'extension/module/d_toolbar';
	private $config_file = 'd_toolbar';
	
	public function index($route, $data, &$output) {
		$_language = new Language();
		$_language->load($this->route);
				
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		$url_token = '';
		
		if (isset($this->session->data['token'])) {
			$url_token .= 'token=' . $this->session->data['token'];
		}
		
		if (isset($this->session->data['user_token'])) {
			$url_token .= 'user_token=' . $this->session->data['user_token'];
		}
				
		// Setting
		$_config = new Config();
		$_config->load($this->config_file);
		$data['setting'] = ($_config->get($this->codename . '_setting')) ? $_config->get($this->codename . '_setting') : array();
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : array();
		$setting = ($this->config->get('module_' . $this->codename . '_setting')) ? $this->config->get('module_' . $this->codename . '_setting') : array();
						
		if (VERSION >= '2.2.0.0') {		
			$user = new Cart\User($this->registry);
		} else {
			$user = new User($this->registry);
		}
										
		if ($status && $user->isLogged()) {
			if (isset($this->request->get['route'])) {
				$route = $this->request->get['route'];
			} else {
				$route = 'common/home';
			} 
			
			if ($route) {
				$data['setting']['route'] = $route;
				$data['setting']['edit'] = '';
				
				$installed_toolbar_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledToolbarExtensions();
				
				foreach ($installed_toolbar_extensions as $installed_toolbar_extension) {
					$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_toolbar_extension . '/toolbar_config', $data['setting']['route']);
					if ($info) $data['setting'] = array_replace_recursive($data['setting'], $info);
				}
			}
			
			if (isset($data['setting']['route'])) {	
				$data['href_admin'] = $this->{'model_extension_module_' . $this->codename}->link('common/dashboard', $url_token, true);
				$data['href_edit'] = $data['setting']['edit'];
				$data['href_user'] = $this->{'model_extension_module_' . $this->codename}->link('user/user/edit', $url_token . '&user_id=' . $user->getId(), true);
				$data['href_setting'] = $this->{'model_extension_module_' . $this->codename}->link('extension/module/' . $this->codename, $url_token, true);
												
				$data['text_admin'] = $_language->get('text_admin');
				$data['text_edit'] = $_language->get('text_edit');
				$data['text_user'] = $_language->get('text_user');
				$data['text_settings'] = $_language->get('text_settings');
				
				foreach ($installed_toolbar_extensions as $installed_toolbar_extension) {
					$toolbar_widgets = $this->load->controller('extension/' . $this->codename . '/' . $installed_toolbar_extension . '/toolbar_widgets', $data['setting']['route']);
					if ($toolbar_widgets) $data['setting']['widget'] = array_merge($data['setting']['widget'], $toolbar_widgets);
				}
				
				if (!empty($setting)) {
					$data['setting'] = array_replace_recursive($data['setting'], $setting);
				}
				
				$widgets = array();
		
				foreach ($data['setting']['widget'] as $widget) {
					if (isset($widget['code']) && isset($widget['name']) && isset($widget['status']) && isset($widget['html'])) {							
						$widgets[] = $widget;
					}
				}
								
				$data['setting']['widget'] = $this->{'model_extension_module_' . $this->codename}->sortArrayByColumn($widgets, 'sort_order');
				
				if (VERSION >= '2.2.0.0') {
					$theme = $this->config->get($this->config->get('config_theme') . '_directory');
					
					if (file_exists(DIR_TEMPLATE . $theme . '/stylesheet/' . $this->codename . '.css')) {
						$this->document->addStyle('catalog/view/theme/' . $theme . '/stylesheet/' . $this->codename . '.css');
					} else {
						$this->document->addStyle('catalog/view/theme/default/stylesheet/' . $this->codename . '.css');
					}
					
					if (file_exists(DIR_TEMPLATE . $theme . '/javascript/' . $this->codename . '.js')) {
						$this->document->addScript('catalog/view/theme/' . $theme . '/javascript/' . $this->codename . '.js');
					} else {
						$this->document->addScript('catalog/view/theme/default/javascript/' . $this->codename . '.js');
					}
					
					$html = $this->load->view($this->route, $data);
				} else {
					if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/' . $this->codename . '.css')) {
						$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/' . $this->codename . '.css');
					} else {
						$this->document->addStyle('catalog/view/theme/default/stylesheet/' . $this->codename . '.css');
					}
					
					if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/javascript/' . $this->codename . '.js')) {
						$this->document->addScript('catalog/view/theme/' . $this->config->get('config_template') . '/javascript/' . $this->codename . '.js');
					} else {
						$this->document->addScript('catalog/view/theme/default/javascript/' . $this->codename . '.js');
					}
					
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $this->route . '.twig')) {
						$html = $this->load->view($this->config->get('config_template') . '/template/' . $this->route, $data);
					} else {
						$html = $this->load->view('default/template/' . $this->route, $data);
					}
				}
								
				$output = preg_replace('/<\/body>/', $html . '</body>', $output);
			}
		}
	}		
}