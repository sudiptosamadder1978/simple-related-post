<?php
/**
 * Sudipto Related Post
 *
 * Plugin Name: Sudipto Related Post
 * Plugin URI:  https://github.com/kamalahmed/sudipto-related-post/
 * Description: A simple plugin that display related post
 * Version:     1.0.0
 * Author:      Sudipto samadder
 * Author URI:  https://sudiptosamadder.com
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: sudipto-related-post
 * Domain Path: /languages
 * Requires at least: 4.9
 * Tested up to: 5.8
 * Requires PHP: 5.2.4
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */


 if(! function_exists('add_action')){
    echo 'Hi there';
    exit;
 }
//Create function
 function srp_get_related_post(){
    global $post;
    $tags = wp_get_post_tags($post->ID);

    if ( !empty ( $tags )){
      $tag_ids = [];
      foreach($tags as $tag){
        $tag_ids[] = $tag->term_id;
      }

//get posts by tag ids value assign 
      $args =[
     'tag__in' => $tag_ids,
     'post__not_in' => [$post->ID],
     'posts_per_page' => 5,
     'orderby' => 'rand',

      ];
    }else{
//get posts by category ids value assign
     $cats = get_the_category($post->ID);

     if ( !empty ( $cats )){
        $cat_ids = [];
        foreach($cats as $cat){
            $cat_ids[] = $cat->term_id;
        }

        // Argument set for related post

        $args =[
            'category__in' => $cat_ids,
            'post__not_in' => [$post->ID],
            'posts_per_page' => 5,
            'orderby' => 'rand',
        ];
     }
    }

    if ( !empty ( $args )){
   //get the related post and return them

     $rp_loop = new WP_Query($args);
//To show related post create wrapper
     $rp = '<div class="srp-wrap">';
     $rp .= '<h2>Related Post:</h2>';
     $rp .= '<ul>';

    if($rp_loop->have_posts()){
        while($rp_loop->have_posts()) : $rp_loop->the_post();

        $rp .= '<li><a href="'.get_the_permalink().'">'.get_the_title().'</a></li>';
    endwhile;
    }

    $rp .='</u></div>';

    return $rp;


    }

    return null;

 }

//Show related post after comment 
 add_action('the_content','srp_show_posts');

 function srp_show_posts($content){
    if ( is_single() ){
        return $content.srp_get_related_post();
    }

    return $content;
 }