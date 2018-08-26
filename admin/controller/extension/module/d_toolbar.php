<?php
class ControllerExtensionModuleDToolbar extends Controller {
	private $codename = 'd_toolbar';
	private $route = 'extension/module/d_toolbar';
	private $config_file = 'd_toolbar';
	private $extension = array();
	private $error = array(); 
		
	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->d_shopunity = (file_exists(DIR_SYSTEM . 'library/d_shopunity/extension/d_shopunity.json'));
		$this->extension = json_decode(file_get_contents(DIR_SYSTEM . 'library/d_shopunity/extension/' . $this->codename . '.json'), true);
	}
		
	public function index() {
		$this->load->language($this->route);

		$this->load->model($this->route);
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');
		
		if ($this->d_shopunity) {		
			$this->load->model('extension/d_shopunity/mbooth');
				
			$this->model_extension_d_shopunity_mbooth->validateDependencies($this->codename);
		}
		
		if (file_exists(DIR_APPLICATION . 'model/extension/module/d_twig_manager.php')) {
			$this->load->model('extension/module/d_twig_manager');
			
			$this->model_extension_module_d_twig_manager->installCompatibility();
		}
		
		if (file_exists(DIR_APPLICATION . 'model/extension/module/d_event_manager.php')) {
			$this->load->model('extension/module/d_event_manager');
				
			$this->model_extension_module_d_event_manager->installCompatibility();				
		}
		
		// Styles and Scripts
		$this->document->addStyle('view/stylesheet/d_bootstrap_extra/bootstrap.css');
		$this->document->addScript('view/javascript/d_bootstrap_switch/js/bootstrap-switch.min.js');
        $this->document->addStyle('view/javascript/d_bootstrap_switch/css/bootstrap-switch.css');
		
		// Heading
		$this->document->setTitle($this->language->get('heading_title_main'));
		$data['heading_title'] = $this->language->get('heading_title_main');

		// Variable
		$data['codename'] = $this->codename;
		$data['route'] = $this->route;
		$data['version'] = $this->extension['version'];
		$data['config'] = $this->config_file;
		$data['d_shopunity'] = $this->d_shopunity;
		$data['stores'] = $this->{'model_extension_module_' . $this->codename}->getStores();
		$data['languages'] = $this->{'model_extension_module_' . $this->codename}->getLanguages();
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
		
		$url_token = '';
		
		if (isset($this->session->data['token'])) {
			$url_token .=  'token=' . $this->session->data['token'];
		}
		
		if (isset($this->session->data['user_token'])) {
			$url_token .=  'user_token=' . $this->session->data['user_token'];
		}
				
		// Action
		$data['module_link'] = $this->url->link($this->route, $url_token, true);
		$data['action'] = $this->url->link($this->route . '/save', $url_token, true);
			
		if (VERSION >= '3.0.0.0') {
			$data['cancel'] = $this->url->link('marketplace/extension', $url_token . '&type=module', true);
		} elseif (VERSION >= '2.3.0.0') {
			$data['cancel'] = $this->url->link('extension/extension', $url_token . '&type=module', true);
		} else {
			$data['cancel'] = $this->url->link('extension/module', $url_token, true);
		}
				
		// Tab
		$data['text_settings'] = $this->language->get('text_settings');
		$data['text_instructions'] = $this->language->get('text_instructions');
		$data['text_instructions_full'] = $this->language->get('text_instructions_full');
		$data['text_basic_settings'] = $this->language->get('text_basic_settings');
		$data['text_widgets'] = $this->language->get('text_widgets');
		$data['text_no_widgets'] = $this->language->get('text_no_widgets');
		
		// Button
		$data['button_save'] = $this->language->get('button_save');
		$data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		// Column
		$data['column_widget'] = $this->language->get('column_widget');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		
		// Entry
		$data['entry_status'] = $this->language->get('entry_status');
		
		// Text
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		// Notification
		foreach($this->error as $key => $error){
			$data['error'][$key] = $error;
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		// Breadcrumbs
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $url_token, true)
		);
			
		if (VERSION >= '3.0.0.0') {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_modules'),
				'href' => $this->url->link('marketplace/extension', $url_token . '&type=module', true)
			);
		} elseif (VERSION >= '2.3.0.0') {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_modules'),
				'href' => $this->url->link('extension/extension', $url_token . '&type=module', true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_modules'),
				'href' => $this->url->link('extension/module', $url_token, true)
			);
		}
			
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link($this->route, $url_token, true)
		);
		
		// Setting 		
		$this->config->load($this->config_file);
		$data['setting'] = ($this->config->get($this->codename . '_setting')) ? $this->config->get($this->codename . '_setting') : array();
		
		$installed_toolbar_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledToolbarExtensions();
		
		foreach ($installed_toolbar_extensions as $installed_toolbar_extension) {
			$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_toolbar_extension . '/toolbar_config');
			if ($info) $data['setting'] = array_replace_recursive($data['setting'], $info);
		}
				
		$setting = $this->model_setting_setting->getSetting('module_' . $this->codename);
		$status = isset($setting['module_' . $this->codename . '_status']) ? $setting['module_' . $this->codename . '_status'] : false;
		$setting = isset($setting['module_' . $this->codename . '_setting']) ? $setting['module_' . $this->codename . '_setting'] : array();
		
		$data['status'] = $status;
								
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
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($this->route, $data));
	}
			
	public function save() {
		$this->load->language($this->route);
		
		$this->load->model($this->route);
		$this->load->model('setting/setting');
						
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_' . $this->codename, $this->request->post);

			$data['success'] = $this->language->get('success_save');
		}
						
		$data['error'] = $this->error;
				
		$this->response->setOutput(json_encode($data));
	}
		
	public function install() {
		if ($this->d_shopunity) {
			$this->load->model('extension/d_shopunity/mbooth');
			
			$this->model_extension_d_shopunity_mbooth->installDependencies($this->codename);
		}
					
		if (file_exists(DIR_APPLICATION . 'model/extension/module/d_event_manager.php')) {
			$this->load->model('extension/module/d_event_manager');
				
			$this->model_extension_module_d_event_manager->installCompatibility();				
			$this->model_extension_module_d_event_manager->deleteEvent($this->codename);
			$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/view/common/footer/after', 'extension/module/d_toolbar');
		}
	}
	
	public function uninstall() {
		if (file_exists(DIR_APPLICATION . 'model/module/d_event_manager.php')) {
			$this->load->model('extension/module/d_event_manager');
			
			$this->model_extension_module_d_event_manager->deleteEvent($this->codename);
		}
	}
											
	/*
	*	Validator Functions.
	*/		 	
	private function validate($permission = 'modify') {				
		if (!$this->user->hasPermission($permission, $this->route)) {
			$this->error['warning'] = $this->language->get('error_permission');
			return false;
		}
		
		return true;
	}
}