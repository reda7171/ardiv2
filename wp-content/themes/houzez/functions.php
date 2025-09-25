<?php
/**
 * Houzez functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Houzez
 * @since Houzez 1.0
 * @author Waqas Riaz
 */
update_option( 'houzez_activation', 'activated' );
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
global $wp_version;

/**
*	---------------------------------------------------------------------------------------
*	Define constants
*	---------------------------------------------------------------------------------------
*/
define( 'HOUZEZ_THEME_NAME', 'Houzez' );
define( 'HOUZEZ_THEME_SLUG', 'houzez' );
define( 'HOUZEZ_THEME_VERSION', '4.1.2' );
define( 'HOUZEZ_FRAMEWORK', get_template_directory() . '/framework/' );
define( 'HOUZEZ_WIDGETS', get_template_directory() . '/inc/widgets/' );
define( 'HOUZEZ_INC', get_template_directory() . '/inc/' );
define( 'HOUZEZ_TEMPLATE_PARTS', get_template_directory() . '/template-parts/' );
define( 'HOUZEZ_IMAGE', get_template_directory_uri() . '/img/' );
define( 'HOUZEZ_CSS_DIR_URI', get_template_directory_uri() . '/css/' );
define( 'HOUZEZ_JS_DIR_URI', get_template_directory_uri() . '/js/' );

/**
*	----------------------------------------------------------------------------------------
*	Set up theme default and register various supported features.
*	----------------------------------------------------------------------------------------
*/
if ( ! function_exists( 'houzez_setup' ) ) {
	
	function houzez_setup() {

		/* add title tag support */
		add_theme_support( 'title-tag' );

		/* Load parent theme languages */
		load_theme_textdomain( 'houzez', get_template_directory() . '/languages' );

		/* Load child theme languages to override parent's translations */
		load_child_theme_textdomain( 'houzez', get_stylesheet_directory() . '/languages' );

		/* Add default posts and comments RSS feed links to head */
		add_theme_support( 'automatic-feed-links' );

		//Add support for post thumbnails.
		add_theme_support( 'post-thumbnails' );

		/**
		*	Register nav menus. 
		*/
		register_nav_menus(
			array(
				'top-menu' => esc_html__( 'Top Menu', 'houzez' ),
				'main-menu' => esc_html__( 'Main Menu', 'houzez' ),
				'main-menu-left' => esc_html__( 'Menu Left', 'houzez' ),
				'main-menu-right' => esc_html__( 'Menu Right', 'houzez' ),
				'mobile-menu-hed6' => esc_html__( 'Mobile Menu Header 6', 'houzez' ),
				'footer-menu' => esc_html__( 'Footer Menu', 'houzez' )
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * Enable support for Post Formats.
		 * See https://developer.wordpress.org/themes/functionality/post-formats/
		 */
		add_theme_support( 'post-formats', array(

		) );

		//remove gallery style css
		add_filter( 'use_default_gallery_style', '__return_false' );

		update_option( 'redux-framework_extendify_plugin_notice', 'hide' );
	
		/*
		 * Adds `async` and `defer` support for scripts registered or enqueued by the theme.
		 */
		$loader = new Houzez_Script_Loader();
		add_filter( 'script_loader_tag', array( $loader, 'filter_script_loader_tag' ), 10, 2 );
	}
}
add_action( 'after_setup_theme', 'houzez_setup' );


remove_filter( 'pre_user_description', 'wp_filter_kses' );
// Add sanitization for WordPress posts.
add_filter( 'pre_user_description', 'wp_filter_post_kses' );

/**
 *	---------------------------------------------------------------------------------------
 *	Options Admin Panel
 *	---------------------------------------------------------------------------------------
 */
require_once( HOUZEZ_FRAMEWORK . 'options/remove-tracking-class.php' ); // Remove tracking
require_once( HOUZEZ_FRAMEWORK . 'options/houzez-option.php' );

if( ! function_exists( 'houzez_load_redux_config' ) ) {
	function houzez_load_redux_config() {
		if ( class_exists( 'ReduxFramework' ) ) {
			require_once(get_theme_file_path('/framework/options/houzez-options.php'));
			require_once(get_theme_file_path('/framework/options/main.php'));
		}
	}
}
add_action('after_setup_theme', 'houzez_load_redux_config', 20);

/**
 *	---------------------------------------------------------------------
 *	Hooks
 *	---------------------------------------------------------------------
 */
require_once( HOUZEZ_FRAMEWORK . 'template-hooks.php' );


/**
 *	---------------------------------------------------------------------
 *	Functions
 *	---------------------------------------------------------------------
 */
require_once( HOUZEZ_FRAMEWORK . 'functions/template-functions.php' );
require_once( HOUZEZ_FRAMEWORK . 'functions/price_functions.php' );
require_once( HOUZEZ_FRAMEWORK . 'functions/helper_functions.php' );
require_once( HOUZEZ_FRAMEWORK . 'functions/taxonomy-helper.php' );
require_once( HOUZEZ_FRAMEWORK . 'functions/cache.php' );
require_once( HOUZEZ_FRAMEWORK . 'functions/map-functions.php' );
require_once( HOUZEZ_FRAMEWORK . 'functions/profile_functions.php' );
require_once( HOUZEZ_FRAMEWORK . 'functions/property_functions.php' );
require_once( HOUZEZ_FRAMEWORK . 'functions/emails-functions.php' );
require_once( HOUZEZ_FRAMEWORK . 'functions/blog-functions.php' );
require_once( HOUZEZ_FRAMEWORK . 'functions/membership-functions.php' );
require_once( HOUZEZ_FRAMEWORK . 'functions/cron-functions.php' );
require_once( HOUZEZ_FRAMEWORK . 'functions/property-expirator.php');
require_once( HOUZEZ_FRAMEWORK . 'functions/messages_functions.php');
require_once( HOUZEZ_FRAMEWORK . 'functions/property_rating.php');
require_once( HOUZEZ_FRAMEWORK . 'functions/menu-walker.php');
require_once( HOUZEZ_FRAMEWORK . 'functions/mobile-menu-walker.php');
require_once( HOUZEZ_FRAMEWORK . 'functions/review.php');
require_once( HOUZEZ_FRAMEWORK . 'functions/stats.php');
require_once( HOUZEZ_FRAMEWORK . 'functions/agency_agents.php');
require_once( HOUZEZ_FRAMEWORK . 'admin/menu/menu.php');

if ( class_exists( 'WooCommerce', false ) ) {
	require_once( HOUZEZ_FRAMEWORK . 'functions/woocommerce.php' );
}

require_once( get_template_directory() . '/template-parts/header/partials/favicon.php' );

require_once(get_theme_file_path('localization.php'));

require_once( HOUZEZ_FRAMEWORK . 'backward-compatibility.php' );


/**
 *	---------------------------------------------------------------------
 *	Classes
 *	---------------------------------------------------------------------
 */
require_once( HOUZEZ_FRAMEWORK . 'class-houzez-query.php' );
require_once( HOUZEZ_FRAMEWORK . 'class-houzez-data-source.php' );
require_once( HOUZEZ_FRAMEWORK . 'class-script-loader.php');
require_once( HOUZEZ_FRAMEWORK . 'class-houzez-lazy-load.php');
require_once( HOUZEZ_FRAMEWORK . 'admin/class-admin.php');
require_once( HOUZEZ_FRAMEWORK . 'class-houzez-property-submit.php');
require_once( HOUZEZ_FRAMEWORK . 'class-houzez-property-search.php');
require_once( HOUZEZ_FRAMEWORK . 'class-houzez-user-verification.php');
require_once( HOUZEZ_FRAMEWORK . 'class-plugin-version-checker.php');

/**
 *	---------------------------------------------------------------------------------------
 *	Yelp
 *	---------------------------------------------------------------------------------------
 */
require_once( get_template_directory() . '/inc/yelpauth/yelpoauth.php' );

/**
 *	---------------------------------------------------------------------------------------
 *	include metaboxes
 *	---------------------------------------------------------------------------------------
 */
if( houzez_theme_verified() ) {

	if( is_admin() ) {
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/property-metaboxes.php' );
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/property-additional-metaboxes.php' );
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/agency-metaboxes.php' );
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/agent-metaboxes.php' );
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/partner-metaboxes.php' );
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/testimonials-metaboxes.php' );
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/posts-metaboxes.php' );
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/packages-metaboxes.php' );
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/reviews-metaboxes.php' );
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/project-metaboxes.php' );

		if( houzez_check_classic_editor () ) {
			require_once( get_theme_file_path('/framework/metaboxes/listings-templates-metaboxes-classic-editor.php') );
			require_once( get_theme_file_path('/framework/metaboxes/page-header-metaboxes-classic-editor.php') );
		} else {
			require_once( get_theme_file_path('/framework/metaboxes/listings-templates-metaboxes.php') );
			require_once( get_theme_file_path('/framework/metaboxes/page-header-metaboxes.php') );
		}

		
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/header-search-metaboxes.php' );
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/page-template-metaboxes.php' );
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/transparent-menu-metaboxes.php' );
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/taxonomies-metaboxes.php' );

		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/status-meta.php' );
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/type-meta.php' );
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/label-meta.php' );
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/cities-meta.php' );
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/state-meta.php' );
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/area-meta.php' );
		require_once( HOUZEZ_FRAMEWORK . 'metaboxes/metaboxes.php' );
	}
	
}


/**
 *	----------------------------------------------------------------
 *	Enqueue scripts and styles.
 *	----------------------------------------------------------------
 */
require_once( HOUZEZ_INC . 'register-scripts.php' );


/**
 *	----------------------------------------------------------------
 *	Better JPG and SSL 
 *	----------------------------------------------------------------
 */
require_once( HOUZEZ_FRAMEWORK . 'thumbnails/better-jpgs.php');
require_once( HOUZEZ_FRAMEWORK . 'thumbnails/honor-ssl-for-attachments.php');

/**
 *	-----------------------------------------------------------------------------------------
 *	Styling
 *	-----------------------------------------------------------------------------------------
 */
if ( class_exists( 'ReduxFramework' ) ) {
	require_once( get_template_directory() . '/inc/styling-options.php' );
}

if ( houzez_check_elementor_installed() ) {
	require get_template_directory() . '/inc/blocks/blocks.php';
}

/**
 *	---------------------------------------------------------------------------------------
 *	Widgets
 *	---------------------------------------------------------------------------------------
 */
require_once(get_theme_file_path('/framework/widgets/about.php'));
require_once(get_theme_file_path('/framework/widgets/code-banner.php'));
require_once(get_theme_file_path('/framework/widgets/mortgage-calculator.php'));
require_once(get_theme_file_path('/framework/widgets/image-banner-300-250.php'));
require_once(get_theme_file_path('/framework/widgets/contact.php'));
require_once(get_theme_file_path('/framework/widgets/properties.php'));
require_once(get_theme_file_path('/framework/widgets/featured-properties.php'));
require_once(get_theme_file_path('/framework/widgets/properties-viewed.php'));
require_once(get_theme_file_path('/framework/widgets/property-taxonomies.php'));
require_once(get_theme_file_path('/framework/widgets/latest-posts.php'));
require_once(get_theme_file_path('/framework/widgets/agents-search.php'));
require_once(get_theme_file_path('/framework/widgets/agency-search.php'));
require_once(get_theme_file_path('/framework/widgets/advanced-search.php'));

 /**
 *	---------------------------------------------------------------------------------------
 *	Set up the content width value based on the theme's design.
 *	---------------------------------------------------------------------------------------
 */
if( !function_exists('houzez_content_width') ) {
	function houzez_content_width()
	{
		$GLOBALS['content_width'] = apply_filters('houzez_content_width', 1170);
	}

	add_action('after_setup_theme', 'houzez_content_width', 0);
}

/**
 *	------------------------------------------------------------------
 *	Visual Composer
 *	------------------------------------------------------------------
 */
if (is_plugin_active('js_composer/js_composer.php') && is_plugin_active('houzez-theme-functionality/houzez-theme-functionality.php') ) {

	if( !function_exists('houzez_include_composer') ) {
		function houzez_include_composer()
		{
			require_once(get_template_directory() . '/framework/vc_extend.php');
		}

		add_action('init', 'houzez_include_composer', 9999);
	}

	// Filter to replace default css class names for vc_row shortcode and vc_column
	if( !function_exists('houzez_custom_css_classes_for_vc_row_and_vc_column') ) {
		//add_filter('vc_shortcodes_css_class', 'houzez_custom_css_classes_for_vc_row_and_vc_column', 10, 2);
		function houzez_custom_css_classes_for_vc_row_and_vc_column($class_string, $tag)
		{
			if ($tag == 'vc_row' || $tag == 'vc_row_inner') {
				$class_string = str_replace('vc_row-fluid', 'row-fluid', $class_string);
				$class_string = str_replace('vc_row', 'row', $class_string);
				$class_string = str_replace('wpb_row', '', $class_string);
			}
			if ($tag == 'vc_column' || $tag == 'vc_column_inner') {
				$class_string = preg_replace('/vc_col-sm-(\d{1,2})/', 'col-sm-$1', $class_string);
				$class_string = str_replace('wpb_column', '', $class_string);
				$class_string = str_replace('vc_column_container', '', $class_string);
			}
			return $class_string;
		}
	}

}

/*-----------------------------------------------------------------------------------*/
/*	Register blog sidebar, footer and custom sidebar
/*-----------------------------------------------------------------------------------*/
if( !function_exists('houzez_widgets_init') ) {
	add_action('widgets_init', 'houzez_widgets_init');
	function houzez_widgets_init()
	{
		register_sidebar(array(
			'name' => esc_html__('Default Sidebar', 'houzez'),
			'id' => 'default-sidebar',
			'description' => esc_html__('Widgets in this area will be shown in the blog sidebar.', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget widget-wrap mb-4 p-4 %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-header"><h3 class="widget-title mb-4">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Property Listings', 'houzez'),
			'id' => 'property-listing',
			'description' => esc_html__('Widgets in this area will be shown in property listings sidebar.', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget widget-wrap mb-4 p-4 %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-header"><h3 class="widget-title mb-4">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Search Sidebar', 'houzez'),
			'id' => 'search-sidebar',
			'description' => esc_html__('Widgets in this area will be shown in search result page.', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget widget-wrap mb-4 p-4 %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-header"><h3 class="widget-title mb-4">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Single Property', 'houzez'),
			'id' => 'single-property',
			'description' => esc_html__('Widgets in this area will be shown in single property sidebar.', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget widget-wrap mb-4 p-4 %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-header"><h3 class="widget-title mb-4">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Page Sidebar', 'houzez'),
			'id' => 'page-sidebar',
			'description' => esc_html__('Widgets in this area will be shown in page sidebar.', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget widget-wrap mb-4 p-4 %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-header"><h3 class="widget-title mb-4">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Agency Sidebar', 'houzez'),
			'id' => 'agency-sidebar',
			'description' => esc_html__('Widgets in this area will be shown in agencies template and agency detail page.', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget widget-wrap mb-4 p-4 %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-header"><h3 class="widget-title mb-4">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Agent Sidebar', 'houzez'),
			'id' => 'agent-sidebar',
			'description' => esc_html__('Widgets in this area will be shown in agents template and angent detail page.', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget widget-wrap mb-4 p-4 %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-header"><h3 class="widget-title mb-4">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Mobile Menu', 'houzez'),
			'id' => 'hz-mobile-menu',
			'description' => esc_html__('Widgets in this area will be shown in the mobile menu', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget widget-wrap mb-4 p-4 %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-header"><h3 class="widget-title mb-4">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Custom Widget Area 1', 'houzez'),
			'id' => 'hz-custom-widget-area-1',
			'description' => esc_html__('You can assign this widget are to any page.', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget widget-wrap mb-4 p-4 %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-header"><h3 class="widget-title mb-4">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Custom Widget Area 2', 'houzez'),
			'id' => 'hz-custom-widget-area-2',
			'description' => esc_html__('You can assign this widget are to any page.', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget widget-wrap mb-4 p-4 %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-header"><h3 class="widget-title mb-4">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Custom Widget Area 3', 'houzez'),
			'id' => 'hz-custom-widget-area-3',
			'description' => esc_html__('You can assign this widget are to any page.', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget widget-wrap mb-4 p-4 %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-header"><h3 class="widget-title mb-4">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Footer Area 1', 'houzez'),
			'id' => 'footer-sidebar-1',
			'description' => esc_html__('Widgets in this area will be show in footer column one', 'houzez'),
			'before_widget' => '<div id="%1$s" class="footer-widget widget widget-wrap mb-4 p-4 %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-header"><h3 class="widget-title mb-4">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Footer Area 2', 'houzez'),
			'id' => 'footer-sidebar-2',
			'description' => esc_html__('Widgets in this area will be show in footer column two', 'houzez'),
			'before_widget' => '<div id="%1$s" class="footer-widget widget widget-wrap mb-4 p-4 %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-header"><h3 class="widget-title mb-4">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Footer Area 3', 'houzez'),
			'id' => 'footer-sidebar-3',
			'description' => esc_html__('Widgets in this area will be show in footer column three', 'houzez'),
			'before_widget' => '<div id="%1$s" class="footer-widget widget widget-wrap mb-4 p-4 %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-header"><h3 class="widget-title mb-4">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Footer Area 4', 'houzez'),
			'id' => 'footer-sidebar-4',
			'description' => esc_html__('Widgets in this area will be show in footer column four', 'houzez'),
			'before_widget' => '<div id="%1$s" class="footer-widget widget widget-wrap mb-4 p-4 %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-header"><h3 class="widget-title mb-4">',
			'after_title' => '</h3></div>',
		));
	}
}

/**
 *	---------------------------------------------------------------------
 *	Disable emoji scripts
 *	---------------------------------------------------------------------
 */
if( !function_exists('houzez_disable_emoji') ) {
	function houzez_disable_emoji() {
		if ( ! is_admin() && houzez_option( 'disable_emoji', 0 ) ) {
			remove_action('wp_head', 'print_emoji_detection_script', 7);
			remove_action('wp_print_styles', 'print_emoji_styles');
		}
	}
	houzez_disable_emoji();
}


/**
 *	---------------------------------------------------------------------
 *	Remove jQuery migrate.
 *	---------------------------------------------------------------------
 */
if( !function_exists('houzez_remove_jquery_migrate') ) {
	function houzez_remove_jquery_migrate( $scripts ) {
		if ( ! houzez_option( 'disable_jquery_migrate', 0 ) ) return;
		if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
			$script = $scripts->registered['jquery'];

			if ( $script->deps ) { // Check whether the script has any dependencies.
				$script->deps = array_diff( $script->deps, array(
					'jquery-migrate',
				) );
			}
		}
	}
	//add_action( 'wp_default_scripts', 'houzez_remove_jquery_migrate' );
}


if( !function_exists('houzez_js_async_attr')) {
	function houzez_js_async_attr($url){
	 
		# Do not add defer or async attribute to these scripts
		$scripts_to_exclude = array('jquery.js');
		
		//if ( is_user_logged_in() ) return $url;
		if ( is_admin() || houzez_is_dashboard() || is_preview() || houzez_option('defer_async_enabled', 0 ) == 0 ) return $url;
		
		foreach($scripts_to_exclude as $exclude_script){
		    if(true == strpos($url, $exclude_script ) )
		    return $url;    
		}
		 
		# Defer or async all remaining scripts not excluded above
		return str_replace( ' src', ' defer src', $url );
	}
	//add_filter( 'script_loader_tag', 'houzez_js_async_attr', 10 );
}

if( !function_exists('houzez_instantpage_script_loader_tag')) {
	function houzez_instantpage_script_loader_tag( $tag, $handle ) {
	  if ( 'houzez-instant-page' === $handle && houzez_option('preload_pages', 1) ) {
	    $tag = str_replace( 'text/javascript', 'module', $tag );
	  }
	  return $tag;
	}
	add_filter( 'script_loader_tag', 'houzez_instantpage_script_loader_tag', 10, 2 );
}

if(!function_exists('houzez_hide_admin_bar')) {
	function houzez_hide_admin_bar($bool) {
	  
	  if ( !current_user_can('administrator') && !is_admin() ) {
	  		return false;
	  } elseif ( houzez_is_dashboard() ) {
	    return false;
	  } else {
	    return $bool;
	  }
	}
	add_filter('show_admin_bar', 'houzez_hide_admin_bar');
}

if ( !function_exists( 'houzez_block_users' ) ) {

	add_action( 'admin_init', 'houzez_block_users' );

	function houzez_block_users() {
		$users_admin_access = houzez_option('users_admin_access');

		if( is_user_logged_in() && $users_admin_access && !houzez_is_demo() ) {
			
			if (is_admin() && !current_user_can('administrator') && isset( $_GET['action'] ) != 'delete' && !(defined('DOING_AJAX') && DOING_AJAX)) {
				wp_die(esc_html("You don't have permission to access this page.", "Houzez"));
				exit;
			}
		}
	}

}

if( !function_exists('houzez_unset_default_templates') ) {
	function houzez_unset_default_templates( $templates ) {
		if( !is_admin() ) {
			return $templates;
		}
		$houzez_templates = houzez_option('houzez_templates');

		if( !empty($houzez_templates) ) {
			foreach ($houzez_templates as $template) {
				unset( $templates[$template] );
			}
		}
	    
	    return $templates;
	}
	add_filter( 'theme_page_templates', 'houzez_unset_default_templates' );
}

if(!function_exists('houzez_author_pre_get')) {
	function houzez_author_pre_get( $query ) {
	    if ( $query->is_author() && $query->is_main_query() && !is_admin() ) :
	        $query->set( 'posts_per_page', houzez_option('num_of_agent_listings', 10) );
	        $query->set( 'post_type', array('property') );
	    endif;
	}
	add_action( 'pre_get_posts', 'houzez_author_pre_get' );
}

add_action ('redux/options/houzez_options/saved', 'houzez_save_custom_options_for_cron');
if( ! function_exists('houzez_save_custom_options_for_cron') ) {
    function houzez_save_custom_options_for_cron() {

    	
        $insights_removal = houzez_option('insights_removal', '60');
        $custom_days = houzez_option('custom_days', '90');
        
        update_option('houzez_insights_removal', $insights_removal);
        update_option('houzez_custom_days', $custom_days);

    }
}


if( ! function_exists('houzez_update_existing_users_with_manager_role_once') ) {
	function houzez_update_existing_users_with_manager_role_once() {
	    // Check if the update has already been done
	    if (get_option('houzez_manager_role_updated')) {
	        return; // Exit if already run
	    }

	    // Fetch all users with the houzez_manager role
	    $args = [
	        'role' => 'houzez_manager'
	    ];
	    $users = get_users($args);

	    foreach ($users as $user) {
	        // Ensure each user has the houzez_manager role, which now has updated capabilities
	        $user->add_role('houzez_manager');
	    }

	    // Set an option to indicate the update has been run
	    update_option('houzez_manager_role_updated', true);
	}

	// Run the function to update users
	houzez_update_existing_users_with_manager_role_once();
}

/**
 * Remove unnecessary scripts and styles from dashboard pages
 */
if( ! function_exists('houzez_remove_frontend_assets_from_dashboard') ) {
	function houzez_remove_frontend_assets_from_dashboard() {
		if (!houzez_is_dashboard()) {
			return;
		}

		wp_dequeue_style('houzez-main');
	}
	add_action('wp_enqueue_scripts', 'houzez_remove_frontend_assets_from_dashboard', 100);
}

add_action('init', function() {
    global $wp_post_types;

    if (isset($wp_post_types['houzez_invoice'])) {
        // on modifie la propriété du CPT
        $wp_post_types['houzez_invoice']->show_in_menu = true;
        $wp_post_types['houzez_invoice']->menu_icon = 'dashicons-media-spreadsheet'; // icône du menu
        $wp_post_types['houzez_invoice']->menu_position = 7; // position
    }
});

add_action('admin_menu', function() {
    global $menu, $wpdb;

    // 🔹 Compter factures NON payées (invoice_payment_status = 0)
    $count_unpaid = $wpdb->get_var("
        SELECT COUNT(*) 
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
        WHERE p.post_type = 'houzez_invoice'
        AND p.post_status = 'publish'
        AND pm.meta_key = 'invoice_payment_status'
        AND pm.meta_value = '0'
    ");

    // 🔹 Compter factures PAYÉES (invoice_payment_status != 0)
    $count_paid = $wpdb->get_var("
        SELECT COUNT(*) 
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
        WHERE p.post_type = 'houzez_invoice'
        AND p.post_status = 'publish'
        AND pm.meta_key = 'invoice_payment_status'
        AND pm.meta_value = '1'
    ");

    // 🔹 Badge rouge (non payées)
    $badge_unpaid = $count_unpaid > 0 ? " <span class='awaiting-mod'>$count_unpaid</span>" : '';

    // 🔹 Badge vert (payées) → on ajoute une classe CSS custom
    $badge_paid = $count_paid > 0 ? " <span class='houzez-badge-paid'>$count_paid</span>" : '';

    // 🔹 Modifier le titre du menu Factures
    foreach ($menu as $key => $value) {
        if (isset($value[2]) && $value[2] === 'edit.php?post_type=houzez_invoice') {
            $menu[$key][0] = __('Factures', 'houzez-theme-functionality') . $badge_unpaid . $badge_paid;
        }
    }
});

// 🔹 CSS pour styliser le badge vert (notification)
add_action('admin_head', function() {
    echo "
    <style>
	
	
    .houzez-badge-paid {
        background-color: #46b450; /* Vert WP */
        color: #fff;
        font-size: 11px;
        font-weight: 600;
        margin-left: 4px;
        padding: 0 6px;
        border-radius: 10px;
        line-height: 1.5em;
        display: inline-block;
    }
	

	   .houzez-badge-published {
        background-color: #46b450; /* Bleu WordPress */
        color: #fff;
        font-size: 11px;
        font-weight: 600;
        margin-left: 4px;
        padding: 0 6px;
        border-radius: 10px;
        line-height: 1.5em;
        display: inline-block;
    }
    </style>
    ";
});


if ( ! function_exists('get_user_active_package_id') ) {
    function get_user_active_package_id( $user_id ) {
        global $wpdb;

        // Récupérer l'ID du post 'user_packages' correspondant à cet utilisateur
        $package_post_id = $wpdb->get_var( $wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta} 
             WHERE meta_key = 'user_packages_userID' 
             AND meta_value = %d 
             LIMIT 1",
            $user_id
        ) );

        if ( $package_post_id ) {
            // Récupérer l'ID réel du package (fave_package_id)
            $package_id = get_post_meta( $package_post_id, 'user_packages_id', true );
            return $package_id ? intval($package_id) : false;
        }

        return false;
    }
}

 


if ( ! function_exists('get_agency_package_id') ) {
    function get_agency_package_id( $agency_id ) {
        global $wpdb;

        // Récupérer l'ID du post 'user_packages' correspondant à cette agence
        $package_post_id = $wpdb->get_var( $wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta} 
             WHERE meta_key = 'user_packages_userID' 
             AND meta_value = %d 
             LIMIT 1",
            $agency_id
        ) );

        if ( $package_post_id ) {
            // Récupérer l'ID réel du package (user_packages_id)
            $package_id = get_post_meta( $package_post_id, 'user_packages_id', true );
            return $package_id ? intval($package_id) : false;
        }

        return false;
    }
}


// Cacher le menu "Mises à jour" dans WP-Admin
add_action('admin_menu', function() {
    remove_submenu_page('index.php', 'update-core.php');
}, 999);

//🔒 Optionnel (si tu veux aussi bloquer l’accès direct via URL)
add_action('admin_init', function() {
    if ( ! current_user_can('manage_options') && strpos($_SERVER['PHP_SELF'], 'update-core.php') !== false ) {
        wp_redirect(admin_url());
        exit;
    }
});

// Supprimer l'icône WordPress de la barre d'administration
add_action('admin_bar_menu', function($wp_admin_bar) {
    $wp_admin_bar->remove_node('wp-logo');
}, 999);





// Changer le logo WordPress sur la page de login
function custom_login_logo() { ?>
    <style type="text/css">
        /* Logo */
        #login h1 a {
            background-image: url('https://webingenius.com/ardiv2/wp-content/uploads/2025/08/black-minimalist-home-logo-3.png');
            background-size: contain; /* adapte ton image */
            width: 200px;   /* largeur du logo */
            height: 200;  /* hauteur du logo */
        }
    </style>
<?php }
add_action('login_enqueue_scripts', 'custom_login_logo');


// Changer le lien du logo de login
function custom_login_logo_url() {
    return home_url(); // redirige vers la page d’accueil de ton site
}
add_filter('login_headerurl', 'custom_login_logo_url');

// Changer le texte alternatif (title) du logo
function custom_login_logo_title() {
    return get_bloginfo('name');
}
add_filter('login_headertitle', 'custom_login_logo_title');







// Disable comments support
function disable_comments_everywhere() {
    // Posts
    remove_post_type_support('post', 'comments');
    remove_post_type_support('post', 'trackbacks');

    // Pages
    remove_post_type_support('page', 'comments');
    remove_post_type_support('page', 'trackbacks');

    // Remove from admin bar
    remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
}
add_action('init', 'disable_comments_everywhere');

// Hide comments menu
add_action('admin_menu', function() {
    remove_menu_page('edit-comments.php');
});


// Injecte un script JS dans l'admin pour calculer automatiquement
add_action('admin_footer', function() {
    global $post;
    if ($post && $post->post_type === 'houzez_packages') :
?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const listingsInput = document.querySelector("#fave_package_featured_listings");
    const pointsInput   = document.querySelector("#fave_package_points");
    const ratio = 25; // 1 annonce = 10 points

	
	// Désactiver le champ featured listings
    if (listingsInput) {
        listingsInput.setAttribute("disabled", "disabled");
        listingsInput.style.backgroundColor = "#f0f0f0"; // optionnel : style grisé
    }
	
	
    if (listingsInput && pointsInput) {
        // Quand on tape dans "listings" → calcule points
        listingsInput.addEventListener("input", function() {
            let listings = parseInt(listingsInput.value) || 0;
            pointsInput.value = listings * ratio;
        });

        // Quand on tape dans "points" → calcule listings
        pointsInput.addEventListener("input", function() {
            let points = parseInt(pointsInput.value) || 0;
            listingsInput.value = Math.floor(points / ratio);
        });
    }
});
</script>
<?php
    endif;
});