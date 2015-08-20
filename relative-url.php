<?php
/*
Plugin Name: Sitepress Relative URL
Description: Relative URL applies wp_make_link_relative function to links (posts, categories, pages and etc.) to convert them to relative URLs. Useful for developers when debugging local WordPress instance on a mobile device (iPad. iPhone, etc.). This fork is to make the plugin compatible with WPML.
Version: 0.1
Author: Mark Nightingale
Author URI: http://marknightingale.net/
Author Email: hello@marknightingale.net
License: GPLv2 or later

  Copyright 2015 Mark Nightingale (hello@marknightingale.net)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

  // Modified from https://github.com/retlehs/roots/issues/490

  // This makes paths realitive for NEW content.
  // For previously posted content, full urls are already loaded in to the database and will need to be updated.

  // for media and image paths - update wp admin: settings>media
  // set "Full URL path to files" feild to '/your-upload-folder-name/'
  // This would be '/assets/' for a default roots install.

  // http://www.deluxeblogtips.com/2012/06/relative-urls.html

  add_action( 'template_redirect', 'relative_url' );

  function relative_url() {
    $filters = array(
      'bloginfo_url',
      //'the_permalink',
      //'wp_list_pages',
      //'wp_list_categories',
      'the_content_more_link',
      'the_tags',
      'the_author_posts_link',
      //'post_link',       // Normal post link
      //'post_type_link',  // Custom post type link
      //'page_link',       // Page link
      //'attachment_link', // Attachment link
      //'get_shortlink',   // Shortlink
      //'post_type_archive_link',    // Post type archive link
      //'get_pagenum_link',          // Paginated link
      //'get_comments_pagenum_link', // Paginated comment link
      //'term_link',   // Term link, including category, tag
      //'search_link', // Search link
      //'day_link',   // Date archive link
      //'month_link',
      //'year_link',

      // site location
      // 'option_siteurl',
      // 'blog_option_siteurl',
      // 'option_home',
      'admin_url',
      // 'home_url',
      'includes_url',
      'site_url',
      'site_option_siteurl',
      'network_home_url',
      'network_site_url',

      // debug only filters
      'get_the_author_url',
      'get_comment_link',
      'wp_get_attachment_image_src',
      'wp_get_attachment_thumb_url',
      'wp_get_attachment_url',
      'wp_login_url',
      'wp_logout_url',
      'wp_lostpassword_url',
      'get_stylesheet_uri',
      // 'get_stylesheet_directory_uri',
      // 'plugins_url',
      // 'plugin_dir_url',
      // 'stylesheet_directory_uri',
      // 'get_template_directory_uri',
      // 'template_directory_uri',
      'get_locale_stylesheet_uri',
      'script_loader_src', // plugin scripts url
      'style_loader_src', // plugin styles url
      'get_theme_root_uri',
      // 'home_url'

      // Plugin filters
      'bwp_minify_get_src'
      
    );

    // Check if WPML Multidomain is active : 
    if (WPML_SUNRISE_MULTISITE_DOMAINS) {
      // set home URLs as site urls

      $sitepress_settings = get_option('icl_sitepress_settings');
      $home_urls = $sitepress_settings['language_domains'];
    } else {
      // Get Home URL
      $home_urls = array(home_url());
    }

    $filter_fn = function( $link ) use ($home_urls){
      $rel = false;
      foreach($home_urls as $home_url) {
        if ( strpos( $link, $home_url ) === 0 ) {
          $rel = true;
        } 
      }
      if ($rel == true) {
        return wp_make_link_relative( $link );
      } else {
        return $link;
      }

      
    };

    foreach ( $filters as $filter ) {
      add_filter( $filter, $filter_fn );
    }
  }

?>