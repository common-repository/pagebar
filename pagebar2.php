<?php
/**
 * Plugin Name: Pagebar2
 * Plugin URI: http://www.elektroelch.de/hacks/wp/pagebar
 * Description: Adds an advanced page navigation to WordPress.
 * Version: 2.70
 * Requires at least: 5.0
 * Tested up to: 6.0
 * Tags: navigation, pagination
 * Stable tag: trunk
 * Author: Lutz Schr&ouml;er
 * Author URI: http://elektroelch.de/blog
 * Text Domain: pagebar
 * Domain Path: /language
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package pagebar
 **/

/**
 * Display pagebar
 *
 * @param WP_Query $query The WP_Query instance (passed by reference).
 **/
function pagebar2_automagic_postbar( $query ) {
	global $paged, $wp_query, $pb_options;

	// no automagic insertion if we're not in the main loop.
	if ( $pb_options['auto'] && ! ( $wp_query->is_main_query() ) ) {
		return;
	}

	require_once 'class-postbar.php';
	new Pagebar2_Postbar( $paged, intval( $wp_query->max_num_pages ) );

}

/**
 * Add pagebar to multipaged pages
 *
 * @return void
 */
function pagebar2_multipagebar() {
	global $page, $numpages;
	require_once 'class-multipagebar.php';
	pagebar2_Multipagebar();
}

/**
 * Add pagebar to multipaged comment sections
 *
 * @return void
 */
function pagebar2_commentbar() {
	global $wp_query;
	require_once 'class-commentbar.php';
	$paged    = intval( get_query_var( 'cpage' ) );
	$max_page = intval( $wp_query->max_num_comment_pages );
	new pagebar2_Commentbar( $paged, $max_page );
}

/**
 * Register stylesheet defind in the options
 *
 * @param string $url string URL of the stylesheet.
 * @param string $handle string Name of the stylesheet.
 * @param string $pluginurl Path of the plugin.
 *
 * @return void
 */
function pagebar2_register_stylesheet( string $url, string $handle, string $pluginurl = '' ) {
	wp_register_style( $handle, $pluginurl . $url, 2 );
	wp_enqueue_style( $handle );
}

/**
 * Add stylesheet tag
 *
 * @return void
 */
function pagebar2_add_user_stylesheet() {
	global $pb_options;
	// use default style for default themes.
	$stylesheet = get_stylesheet();

	if ( in_array(
		$stylesheet,
		array(
			'twentyten',
			'twentyeleven',
			'twentytwelve',
			'twentythirteen',
			'twentyfourteen',
		),
		true
	) ) {
		pagebar2_register_stylesheet( plugin_dir_url( __FILE__ ) . 'css/' . $stylesheet . '.css', 'preset_css' );
	}

	if ( 'styleCss' !== $pb_options['stylesheet'] ) {
		pagebar2_register_stylesheet(
			get_bloginfo( 'stylesheet_directory' )
			. '/' . $pb_options['cssFilename'],
			'pagebar-stylesheet'
		);
	}

}

/**
 * Setup options
 *
 * @return void
 */
function pagebar2_activate() {
	require_once 'activate.php';
}

/**
 * Add Settings link to plugin page
 *
 * @param array $links plugin action links.
 *
 * @return array $links altered plugin action links
 **/
function pagebar2_add_configure_link( array $links ) {
	$settings_link = '<a href="options-general.php?page=pagebar2_options.php">' . __( 'Settings', 'pagebar' ) . '</a>';
	array_unshift( $links, $settings_link );

	return $links;
}

/**
 * Add filter for displaying complete paged page.
 *
 * @return string $content Altered content
 */
function pagebar2_allpage_show( $content ) {
	global $multipage, $posts;

	if ( $multipage && $all_page = get_query_var( 'all' ) ) {
		$content = $posts[0]->post_content;
	}

	return $content;
}

add_filter( 'the_content', 'pagebar2_allpage_show', 0 );

/**
 * add filter to allow URL parameter "all"
 */
add_action( 'init', 'pagebar2_allpage_permalink', 99 );
function pagebar2_allpage_permalink() {
	global $wp_rewrite;
	$wp_rewrite->add_endpoint( 'all', EP_ALL );
	$wp_rewrite->flush_rules( false );
}

/**
 * Remove possible standard navigation of theme navigation by adding CSS property
 *
 * @return void
 */
function pagebar2_remove_nav() {
	if ( ! is_single() ) {
		return;
	} ?>
	<style>.navigation {
            visibility: collapse;
        }</style>
	<style> #nav-below {
            visibility: collapse;
        }</style>
	<?php
}

/**
 * add filter to allow URL parameter "all"
 */
add_filter( 'query_vars', 'pagebar2_all_page_endpoint_query_vars_filter' );
function pagebar2_all_page_endpoint_query_vars_filter( $vars ) {
	$vars[] = 'all';

	return $vars;
}

function pagebar2_register_pagebar_settings() {
	register_setting( 'pagebar-options', 'postbar' );
	register_setting( 'pagebar-options', 'Pagebar2_Multipagebar' );
	register_setting( 'pagebar-options', 'pagebar2_commentbar' );
}//end pagebar2_register_pagebar_settings()

function pagebar2_detect_theme() {

	// $stylesheet = get_stylesheet();
	// if ($stylesheet = "twentyten") {
	//
	// $handle = 'detected_css';
	// wp_register_style($handle, plugin_dir_url(__FILE__) . 'css/' . $stylesheet . '.css');
	// wp_enqueue_style($handle);
	// wp_print_styles();
	//
	// pagebar2_register_stylesheet('preset_css', plugin_dir_url(__FILE__) . 'css/' . $stylesheet . '.css');
	// }
}

/**
 *
 * main
 */

add_action( 'plugins_loaded', 'pagebar2_load_textdomain' );
function pagebar2_load_textdomain() {
	load_plugin_textdomain( 'pagebar', false, plugin_basename( dirname( __FILE__ ) . '/language' ) );
}

if ( is_admin() ) {
	add_action(
		'plugins_loaded',
		function () {
			require 'pagebar_options.php';
			add_action( 'admin_print_scripts', array( &$pagebaroptions, 'pb_load_jquery' ) );
			// $pagebaroptions->pb_load_jquery();
			$plugin = plugin_basename( __FILE__ );
			add_filter( "plugin_action_links_$plugin", 'pagebar2_add_configure_link' );
			add_action( 'admin_init', 'pagebar2_register_pagebar_settings' );
		}
	);
}

/** We need to load the postbar option outside the classes since the actions
 * need to be started. There may be a different solution, but I did not find one.
 */
if ( ! $pb_options = get_option( 'Pagebar2_Postbar' ) ) {
	pagebar2_activate();
	$pb_options = get_option( 'Pagebar2_Postbar' );
}
add_action( 'activate_' . dirname( plugin_basename( __FILE__ ) ) . '/pagebar2.php', 'pagebar2_activate' );
register_activation_hook( __FILE__, 'pagebar2_activate' );

add_action( 'wp_head', 'pagebar2_add_user_stylesheet' );
add_action( 'wp_print_styles', 'pagebar2_add_user_stylesheet' );

if ( $pb_options ['auto'] && in_array( $pagenow, array( 'index.php' ), true ) ) {
	if ( 'on' === $pb_options ['bef_loop'] ) {
		add_action( 'loop_start', 'pagebar2_automagic_postbar' );
	}
	if ( 'on' === $pb_options ['aft_loop'] ) {
		add_action( 'loop_end', 'pagebar2_automagic_postbar' );
	}
	if ( 'on' === $pb_options ['footer'] ) {
		add_action( 'wp_footer', 'pagebar2_automagic_postbar' );
	}
	if ( 'on' === $pb_options ['remove'] ) {
		add_action( 'wp_head', 'pagebar2_remove_nav' );
	}
}
