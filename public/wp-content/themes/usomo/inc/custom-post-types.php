<?php

function usomo_cpt_project()
{

  $labels = array(
    'name' => _x('Projects', 'post type general name'),
    'singular_name' => _x('Project', 'post type singular name'),
    'add_new' => _x('Add New', 'Project'),
    'add_new_item' => __('Add New Project'),
    'edit_item' => __('Edit Project'),
    'new_item' => __('New Project'),
    'all_items' => __('All Projects'),
    'view_item' => __('View Project'),
    'search_items' => __('Search Projects'),
    'not_found' => __('No Project found'),
    'not_found_in_trash' => __('No Project found in the Trash'),
    'parent_item_colon' => '',
    'menu_name' => 'Projects'
  );
  $args = array(
    'labels' => $labels,
    'description' => 'Projects',
    'public' => true,
    'menu_position' => 3,
    'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
    'has_archive' => true
  );
  register_post_type('project', $args);
}
