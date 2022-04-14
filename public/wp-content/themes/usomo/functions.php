<?php

require_once 'custom-elementor.php';

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function usomo_enqueue_scripts()
{
  wp_enqueue_style(
    'style',
    get_stylesheet_directory_uri() . '/style.css',
    [
      'hello-elementor-theme-style',
    ],
    '1.0.1'
  );

  wp_enqueue_style(
    'usomo-style',
    get_stylesheet_directory_uri() . '/assets/dist/style.min.css',
    [
      'hello-elementor-theme-style',
    ],
    '1.0.1'
  );

  wp_register_script('slick-carousel-script', get_stylesheet_directory_uri() . '/assets/src/js/slick.min.js', ['elementor-frontend'], '1.0.0', true);
  wp_register_style('slick-carousel-style', get_stylesheet_directory_uri() . '/assets/src/js/slick.css');
  // wp_register_style('slick-carousel-theme-style', get_stylesheet_directory_uri() . '/node_modules/slick-carousel/slick/slick-theme.css');

  wp_enqueue_script('usomo-script', get_stylesheet_directory_uri()  . '/assets/dist/script.min.js', ['jquery', 'slick-carousel-script'], false, true);
}
add_action('wp_enqueue_scripts', 'usomo_enqueue_scripts', 20);

add_image_size('project-slider', 2160, 1440, true);
