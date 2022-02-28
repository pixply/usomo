<?php

namespace Usomo\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use ElementorPro\Plugin;
use WP_Query;

if (!defined('ABSPATH')) exit;

class ProjectSlider extends Widget_Base
{

  public function __construct($data = [], $args = null)
  {
    parent::__construct($data, $args);
  }

  public function get_script_depends()
  {
    return ['slick-carousel-script'];
  }

  public function get_style_depends()
  {
    return ['slick-carousel-style'];
    return ['slick-carousel-theme-style'];
  }

  public function get_name()
  {
    return 'projectslider';
  }

  public function get_title()
  {
    return 'Project Slider';
  }

  public function get_icon()
  {
    return 'eicon-t-letter';
  }

  public function get_categories()
  {
    return ['general'];
  }

  protected function _register_controls()
  {
    $this->start_controls_section(
      'section_content',
      [
        'label' => 'Settings',
      ]
    );

    $this->add_control(
      'amount',
      [
        'label' => 'Amount',
        'type' => \Elementor\Controls_Manager::NUMBER,
        'min' => '1',
        'max' => '5',
        'default' => '3'
      ]
    );

    $this->add_control(
      'slider_type',
      [
        'label' => 'Type',
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => [
          'default' => 'Latest Project',
          'multiple' => 'Multiple Project',
        ],
        'default' => 'default',
      ]
    );

    $this->end_controls_section();
  }

  protected function loadPosts($amount)
  {
    $args = array(
      'post_type' => 'projekt',
      'post_status' => 'publish',
      'posts_per_page' => $amount,
      'orderby' => 'date',
      'order' => 'DESC'
    );

    $query = new WP_Query($args);
    $result = $query->posts;
    $posts = array();

    foreach ($result as $id => $post) {
      $array = (array) $post;
      $array['pictures'][] = get_field('bild_1', $post->ID);
      $array['pictures'][] = get_field('bild_2', $post->ID);
      $array['pictures'][] = get_field('bild_3', $post->ID);
      array_push($posts, $array);
    }

    wp_reset_postdata();

    return $posts;
  }

  protected function render()
  {
    $settings = $this->get_settings_for_display();

?>
    <div class="project-slider <?php echo $settings['slider_type']; ?>">
      <?php
      if ($settings['slider_type'] === 'default') {
        $post = $this->loadPosts(1)[0];
      ?>
        <div class="project-slider__project" data-post="<?php echo 'post-' . $post['ID'] ?>">
          <div class="project-slider__project__pictures slider">
            <?php foreach ($post['pictures'] as $picture) : ?>
              <div class="project-slider__project__pictures__picture slide">
                <?php echo wp_get_attachment_image($picture['ID'], 'full'); ?>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="project-slider__project__title">
            <h3><?php echo $post['post_title']; ?></h3>
          </div>
          <div class="project-slider__project__excerpt">
            <?php echo get_the_excerpt($post['ID']); ?>
          </div>
        </div>

      <?php

      } elseif ($settings['slider_type'] === 'multiple') {
        $posts = $this->loadPosts($settings['amount']);
      ?>
        <div class="slider">
          <?php foreach ($posts as $post) : ?>
            <div class="project-slider__project" data-post="<?php echo 'post-' . $post['ID'] ?>">
              <div class="project-slider__project__picture">
                <?php echo wp_get_attachment_image($post['pictures'][0]['ID'], 'full'); ?>
              </div>
              <div class="project-slider__project__content">
                <div class="project-slider__project__title">
                  <h3><?php echo $post['post_title']; ?></h3>
                </div>
                <div class="project-slider__project__excerpt">
                  <?php echo get_the_excerpt($post['ID']); ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php
      }

      ?>
    </div>
<?php
  }
}
