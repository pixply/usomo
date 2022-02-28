<?php

namespace Usomo;

class Widgets_Loader
{
  // Singleton pattern
  private static $_instance = null;
  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function __construct()
  {
    add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets'], 99);
  }

  public function register_widgets()
  {
    $this->include_widget_files();

    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ProjectSlider());
  }

  public function include_widget_files()
  {
    require_once(__DIR__ . '/elementor-widgets/project-slider.php');
  }
}

// Instantiate Plugin Class
Widgets_Loader::instance();
