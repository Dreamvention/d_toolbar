<?php
class ControllerExtensionDToolbarDToolbar extends Controller {
	private $codename = 'd_toolbar';
	private $route = 'extension/d_toolbar/d_toolbar';
	private $config_file = 'd_toolbar';
	private $error = array();
	
	/*
	*	Functions for Toolbar.
	*/
	public function toolbar_config($route) {
		$data = array();
		
		$url_token = '';
		
		if (isset($this->session->data['token'])) {
			$url_token .= 'token=' . $this->session->data['token'];
		}
		
		if (isset($this->session->data['user_token'])) {
			$url_token .= 'user_token=' . $this->session->data['user_token'];
		}
				
		if ($route == 'product/category') {
			if (isset($this->request->get['path'])) {
				$parts = explode('_', (string)$this->request->get['path']);
				$category_id = (int)array_pop($parts);
			} else {
				$category_id = 0;
			}
					
			if ($category_id) {
				$data['route'] = 'category_id=' . $category_id;
				$data['edit'] = $this->{'model_extension_module_' . $this->codename}->link('catalog/category/edit', $url_token . '&category_id=' . $category_id, true);
			}
		}
				
		if ($route == 'product/product') {
			if (isset($this->request->get['product_id'])) {
				$product_id = (int)$this->request->get['product_id'];
			} else {
				$product_id = 0;
			}
				
			if ($product_id) {
				$data['route'] = 'product_id=' . $product_id;
				$data['edit'] = $this->{'model_extension_module_' . $this->codename}->link('catalog/product/edit', $url_token . '&product_id=' . $product_id, true);
			}
		}
				
		if ($route == 'product/manufacturer/info') {
			if (isset($this->request->get['manufacturer_id'])) {
				$manufacturer_id = (int)$this->request->get['manufacturer_id'];
			} else {
				$manufacturer_id = 0;
			}
								
			if ($manufacturer_id) {
				$data['route'] = 'manufacturer_id=' . $manufacturer_id;
				$data['edit'] = $this->{'model_extension_module_' . $this->codename}->link('catalog/manufacturer/edit', $url_token . '&manufacturer_id=' . $manufacturer_id, true);
			}
		}
				
		if ($route == 'information/information') {
			if (isset($this->request->get['information_id'])) {
				$information_id = (int)$this->request->get['information_id'];
			} else {
				$information_id = 0;
			}	
					
			if ($information_id) {
				$data['route'] = 'information_id=' . $information_id;
				$data['edit'] = $this->{'model_extension_module_' . $this->codename}->link('catalog/information/edit', $url_token . '&information_id=' . $information_id, true);
			}
		}
				
		return $data;
	}
}
