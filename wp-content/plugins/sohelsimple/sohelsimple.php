<?php

/*
Plugin Name: sohel simple
Plugin URI: http://sohel4r.blogspot.com/
Description: Used by millions, Akismet is quite possibly the best way in the world to <strong>protect your blog from comment and trackback spam</strong>. It keeps your site protected from spam even while you sleep. To get started: 1) Click the "Activate" link to the left of this description, 2) <a href="http://akismet.com/get/">Sign up for an Akismet API key</a>, and 3) Go to your Akismet configuration page, and save your API key.
Version: 1.0
Author: Sohel Rana
Author URI: http://sohel4r.blogspot.com/
License: GPLv2 or later
*/

add_filter( 'the_content', 'zvarp_add_related_posts' );

/**
 * Add links to related posts at the end of the content
 */
function zvarp_add_related_posts($content) {
    //if it's not a singular post, ignore
    if(!is_singular('post')) {
        return $content;
    }
    
    //get post categories   http://codex.wordpress.org/Function_Reference/get_the_terms
    $categories = get_the_terms(get_the_ID(),'category' );
    $categoriesIds = array(); 
    
    foreach($categories as $category) {
        $categoriesIds[] = $category->term_id;
    }
    
    $loop = new WP_Query(array(
        'category_in' => $categoriesIds,
        'posts_per_page' => 4,
        'post__not_in' => array(get_the_ID()),
        'orderby' => 'rand'
    ));
    
    //if there are posts
    if($loop->have_posts()) {
        
        $content .= 'RELATED POSTS:<br/><ul>';
        while($loop->have_posts()) {
            $loop->the_post();
            $content .= '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
        }
        $content .= '</ul>';
    }
    
    //retore data http://codex.wordpress.org/Function_Reference/wp_reset_query
    wp_reset_query();
    
    return $content;
}









