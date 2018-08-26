# Frontend Toolbar
### d_toolbar
https://dreamvention.ee

WordPress has a frontend toolbar. Drupal has a  frontend toolbar, now OpenCart has one too. The frontend toolbar extension gives site administrators quick access to editing the product, category and information pages, it allows other extensions to place their widgets. SEO module and Carbon cache are just a few that use it.

## Why do I need a Frontend Toolbar?
If you are an OpenCart administrator, you probably noticed how anoying it is to search for a product in the admin to just edit a description or title. Same goes for category page or information page.

With Frontend toolbar you get
- Quick access to editing product, category, infromation pages
- Quickly see SEO rating from SEO Module
- Quickly see load speed from Carbon Cache
- Add other widgets from other extensions

If you want to save time administrating your OpenCart, you must have the Frontend Toolbar.

## How to install
1. Download the compiled extension package from Shopunity at https://shopunity.net/extension/frontent-toolbar
2. Install via Extension installer

## How to develope your own widget for the Frontend Toolbar
1. Download the Frontend Toolbar widget from Shopunity at https://shopunity.net/extension/frontent-toolbar-default-widget
2. Edit it to your needs. 

Every widget requires at least 3 files
- controller
- language
- template

### Controller file
catalog/controller/extension/d_toolbar/my_widget.php

```php
<?php
class ControllerExtensionDToolbarMyWidget extends Controller {
  /*
  *	Functions for Toolbar.
  */
  
  public function toolbar_widgets($route) {
    $result = array();
    $this->load->language('d_toolbar/my_widget');

    $widget = array(
      'code' => 'my_widget',
      'name' => $this->language->get('text_my_widget'); 
      'status' => true,
      'sort_order' => '10',
      'html' => $this->load->view('d_toolbar/my_widget', $data);
    );

    $result['my_widget'] = $widget;
    return $result;
  }
}
```

### Language file
catalog/language/en-gb/extension/d_toolbar/my_widget.php

```php
<?php

// Text
$_['text_my_widget'] = 'My Super Widget';

```

### Template file
catalog/view/theme/default/template/extension/d_toolbar/my_widget.twig (although it supports tpl for version 2.x we suggest using d_twig_manger to port twig files to older OpenCart Versions)

```twig
<li class="d-seo-module-adviser navbar-item dropdown">
  <a class="title dropdown-toggle" data-toggle="dropdown">
    {{ text_my_widget }}
  </a>
  <div class="dropdown-menu">
    <div class="dropdown-inner">
      <ul class="list-unstyled">
        <li class="dropdown-item" data-toggle="tooltip" data-placement="right" title="Tooltip">
          <a class="title">
            <span>Your widget can do something here</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</li>
```

# Support
We offer free support for this module. In case you need help, feel free to contact us at https://dreamvention.ee/support

The Dreamvention team
