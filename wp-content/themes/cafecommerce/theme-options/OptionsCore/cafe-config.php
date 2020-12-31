<?php
		/**
		 * ReduxFramework Sample Config File
		 * For full documentation, please visit: http://docs.reduxframework.com/
		 */

		if ( ! class_exists( 'Redux' ) ) {
			return;
		}


		// This is your option name where all the Redux data is stored.
		$opt_name = "cafe";

		// This line is only for altering the demo. Can be easily removed.
		$opt_name = apply_filters( 'cafe/opt_name', $opt_name );

		/*
		 *
		 * --> Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
		 *
		 */

		$sampleHTML = '';
		if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
			Redux_Functions::initWpFilesystem();

			global $wp_filesystem;

			$sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
		}

		// Background Patterns Reader
		$sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
		$sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
		$sample_patterns      = array();
		
		if ( is_dir( $sample_patterns_path ) ) {

			if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) {
	$sample_patterns = array();

	while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

		if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
			$name  = explode( '.', $sample_patterns_file );
			$name  = str_replace( '.' . end( $name ), '', $sample_patterns_file );
			$sample_patterns[] = array(
	'alt' => $name,
	'img' => $sample_patterns_url . $sample_patterns_file
			);
		}
	}
			}
		}

		/**
		 * ---> SET ARGUMENTS
		 * All the possible arguments for Redux.
		 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
		 * */

		$theme = wp_get_theme(); // For use with some settings. Not necessary.

		$args = array(
			// TYPICAL -> Change these values as you need/desire
			'opt_name' => $opt_name,
			// This is where your data is stored in the database and also becomes your global variable name.
			'display_name'         => $theme->get( 'Name' ),
			// Name that appears at the top of your panel
			'display_version'      => $theme->get( 'Version' ),
			// Version that appears at the top of your panel
			'menu_type'=> 'menu',
			//Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
			'allow_sub_menu'       => true,
			// Show the sections below the admin menu item or not
			'menu_title'           => __( 'تنظیمات کافه کامرس', 'redux-framework-demo' ),
			'page_title'           => __( 'تنظیمات کافه کامرس', 'redux-framework-demo' ),
			// You will need to generate a Google API key to use this feature.
			// Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
			'google_api_key'       => '',
			// Set it you want google fonts to update weekly. A google_api_key value is required.
			'google_update_weekly' => false,
			// Must be defined to add google fonts to the typography module
			'async_typography'     => false,
			// Use a asynchronous font on the front end or font string
			//'disable_google_fonts_link' => true,        // Disable this in case you want to create your own google fonts loader
			'admin_bar'=> true,
			// Show the panel pages on the admin bar
			'admin_bar_icon'       => 'dashicons-portfolio',
			// Choose an icon for the admin bar menu
			'admin_bar_priority'   => 50,
			// Choose an priority for the admin bar menu
			'global_variable'      => '',
			// Set a different name for your global variable other than the opt_name
			'dev_mode' => false,
			// Show the time the page took to load, etc
			'update_notice'        => true,
			// If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
			'customizer'           => true,
			// Enable basic customizer support
			//'open_expanded'     => true,        // Allow you to start the panel in an expanded way initially.
			//'disable_save_warn' => true,        // Disable the save warning when a user changes a field

			// OPTIONAL -> Give you extra features
			'page_priority'        => null,
			// Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
			'page_parent'          => 'themes.php',
			// For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
			'page_permissions'     => 'manage_options',
			// Permissions needed to access the options panel.
			'menu_icon'=> '',
			// Specify a custom URL to an icon
			'last_tab' => '',
			// Force your panel to always open to a specific tab (by id)
			'page_icon'=> 'icon-themes',
			// Icon displayed in the admin panel next to your menu_title
			'page_slug'=> '',
			// Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
			'save_defaults'        => true,
			// On load save the defaults to DB before user clicks save or not
			'default_show'         => false,
			// If true, shows the default value next to each field that is not the default value.
			'default_mark'         => '',
			// What to print by the field's title if the value shown is default. Suggested: *
			'show_import_export'   => true,
			// Shows the Import/Export panel when not used as a field.

			// CAREFUL -> These options are for advanced use only
			'transient_time'       => 60 * MINUTE_IN_SECONDS,
			'output'   => true,
			// Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
			'output_tag'           => true,
			// Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
			// 'footer_credit'     => '',       // Disable the footer credit of Redux. Please leave if you can help it.

			// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
			'database' => '',
			// possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
			'use_cdn'  => true,
			// If you prefer not to use the CDN for button_set2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

			// HINTS
			'hints'    => array(
	'icon'          => 'el el-question-sign',
	'icon_position' => 'right',
	'icon_color'    => 'lightgray',
	'icon_size'     => 'normal',
	'tip_style'     => array(
		'color'   => 'red',
		'shadow'  => true,
		'rounded' => false,
		'style'   => '',
	),
	'tip_position'  => array(
		'my' => 'top left',
		'at' => 'bottom right',
	),
	'tip_effect'    => array(
		'show' => array(
			'effect'   => 'slide',
			'duration' => '500',
			'event'    => 'mouseover',
		),
		'hide' => array(
			'effect'   => 'slide',
			'duration' => '500',
			'event'    => 'click mouseleave',
		),
	),
			)
		);

		// ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
		$args['admin_bar_links'][] = array(
			//'id'    => 'redux-support',
			'href'  => '#',
			'title' => __( 'پشتیبانی قالب کافه کامرس', 'redux-framework-demo' ),
		);

		$args['admin_bar_links'][] = array(
			'id'    => 'redux-extensions',
			'href'  => 'http://cafecommerce.ir',
			'title' => __( 'وب سایت توسعه دهنده', 'redux-framework-demo' ),
		);

		// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
		$args['share_icons'][] = array(
			'url'   => '#',
			'title' => 'Like us on Facebook',
			'icon'  => 'el el-facebook'
		);
		$args['share_icons'][] = array(
			'url'   => '#',
			'title' => 'Follow us on Twitter',
			'icon'  => 'el el-twitter'
		);
		$args['share_icons'][] = array(
			'url'   => '#',
			'title' => 'Find us on LinkedIn',
			'icon'  => 'el el-linkedin'
		);

		// Panel Intro text -> before the form
		if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
			if ( ! empty( $args['global_variable'] ) ) {
	$v = $args['global_variable'];
			} else {
	$v = str_replace( '-', '_', $args['opt_name'] );
			}
			$args['intro_text'] = sprintf( __( '', 'redux-framework-demo' ), $v );
		} else {
			$args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'redux-framework-demo' );
		}

		// Add content after the form.
		$args['footer_text'] = __( '', 'redux-framework-demo' );

		Redux::setArgs( $opt_name, $args );

		/*
		 * ---> END ARGUMENTS
		 */


		/*
		 * ---> START HELP TABS
		 */

		$tabs = array(
			array(
	'id'      => 'redux-help-tab-1',
	'title'   => __( 'راهنمای شماره یک', 'redux-framework-demo' ),
	'content' => __( '<p>با انتخاب هر طرح تنظیمات مربوطه نمایش داده شده و آن طرح به عنوان استایل پیشفرض انتخاب میشود!</p>', 'redux-framework-demo' )
			),
			array(
	'id'      => 'redux-help-tab-2',
	'title'   => __( 'راهنمای شماره دو', 'redux-framework-demo' ),
	'content' => __( '<p>تنظیمات قالب کافه کامرس قابل انتقال به قالب دیگر نمی باشد.</p>', 'redux-framework-demo' )
			)
		);
		Redux::setHelpTab( $opt_name, $tabs );

		// Set the help sidebar
		$content = __( '<p>قالبی برای فروش فایل!</p>', 'redux-framework-demo' );
		Redux::setHelpSidebar( $opt_name, $content );


		/*
		 * <--- END HELP TABS
		 */


		/*
		 *
		 * ---> START SECTIONS
		 *
		 */

		/*

			As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


		 */

		// -> START General Setting

		Redux::setSection( $opt_name, array(
			'title'=> 'تنظیمات عمومی',
			'id'   => 'general-setting',
			'customizer_width' => '700px',       
			'fields'           => array(
	
	array(
			'id'          => 'section_02',
			'title'=> 'تنظیمات عمومی',
			'type'     => 'section',
			'class'  => 'ruby-section-start',
			'indent' => true
	),   
	array(
		'id'       => 'keywords',
		'type'     => 'text',
		'title'    => 'کلمات کلیدی',
		'subtitle'     => 'کلمات کلیدی سایت خود را وارد کنید. ، کلمات را با , جدا کنید..',
		'default'  => 'قالب وردپرس , قالب سیگما , فروش فایل',
	),
	array(
		'id'          => 'favicon',
		'title'       => 'فاوآیکون',
		'subtitle'        => 'شما میتوانید یک فاوآیکون سفارشی از کتابخانه پرونده چند رسانه ای خود انتخاب کنید..  اندازه پیشنهادی : 40*40',
		'type'     => 'media',
		'url'      => true,
		'compiler' => 'true',   
		'default' => array( 'url'=>''.get_template_directory_uri().'/assets/img/favicon.ico'),
	),	  		           
	array(
			'id'       => 'cart_active',
		'type'     => 'button_set',
		'title'    => 'آیکون سبد خرید  ',
		'subtitle' => 'از این بخش میتوانید آیکون ثابت سبد خرید را مدیریت کنید.!',
		'default' => 'inherit',
		'options'  => array(
			'inherit' => 'فعال',
			'none' => 'غیرفعال',
		),    
	),       
	array(
			'id'       => 'cart_active_mobile',
		'type'     => 'button_set',
		'title'    => 'آیکون سبد خرید در موبایل',
		'subtitle' => 'از این بخش میتوانید آیکون ثابت سبد خرید در موبایل را مدیریت کنید.!',
		'default' => 'inherit',
		'options'  => array(
			'inherit' => 'فعال',
			'none' => 'غیرفعال',
		),    
	),
	array(
			'id'          => 'body_back',
			'title'       => 'رنگ پس زمینه سایت',
			'subtitle'        => 'رنگ پس زمینه سایت را انتخاب کنید.',
			'type'     => 'background',
			'background-repeat'      => false,
			'background-position'      => false,
			'background-size'      => false,
			'background-attachment'      => false,
			'background-image'      => false,
			'default'  => array(
			'background-color' => '#fff', ),
	),
	array(
		'id'       => 'backtop_show',
		'type'     => 'button_set',
		'title'    => 'نمایش دکمه بازگشت به بالا',
		'default' => 'inherit',
		'subtitle' => 'از این بخش میتوانید نمایش دکمه بازگشت به بالای سایت را مدیریت کنید.!',
		'options'  => array(
			'inherit' => 'فعال',
			'none' => 'غیرفعال',
		),    
	),        
	array(
		'id'       => 'share_posts',
		'default' => 'inherit',
		'type'     => 'button_set',
		'title'    => 'نمایش دکمه های اشتراک گذاری مطالب',
		'subtitle' => 'دکمه اشتراک گذاری در پایین جزییات محصول و نوشته ها نمایش داده میشود.',
		'options'  => array(
			'inherit' => 'فعال',
			'none' => 'غیرفعال',
			),    
		),
	),
			
	) );
	
	// Home Settings
	Redux::setSection( $opt_name, array(
			'title'=> 'تنظیمات صفحه اصلی',
			'id'   => 'home_settings_options',
			'customizer_width' => '400px',
			'icon' => 'el el-home'
	));
	Redux::setSection( $opt_name, array(
			'title'=> 'تنظیمات اسلایدر اول',
			'subsection'       => true,
			'id'   => 'slider01_options',
			'customizer_width' => '400px',        
			'fields'           => array(
	array(
		'id'       => 'section_slider01_settings',
		 'title'=> 'تنظیمات اسلایدر اول',
		'type'     => 'section',
		'indent' => true,
	),   
	array(
		'id'       => 'section_slider_show_01',
		 'type'    => 'switch',
		'title'    => 'فعال سازی اسلایدر اول',
		'subtitle' => 'از این بخش میتوانید اسلایدر اول سایت را فعال یا غیرفعال کنید.',
		'default'  => 1,
        'on'       => 'فعال',
        'off'      => 'غیرفعال',
	),
	array(
		'id'       => 'slider01_promo',
		'type'    => 'button_set',
		'required' => array( 'section_slider_show_01', '=', '1' ),
		'title'    => 'فعال سازی حالت تبلیغی اسلایدر',
		'subtitle' => 'از این بخش میتوانید اسلایدر اول سایت را فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		), 
	),
	array(
		'id'          => 'slider01_promo_images',
		'type'        => 'slides',
		'required' => array( 'slider01_promo', '=', 'block' ),
		'subtitle'    => 'اسلایدهای موجود را از این قسمت مدیریت کنید.',
		'placeholder' => array(
			'title'       => 'عنوان اسلایدر را وارد کنید.!',
			'subtitleription' => 'توضیحات اسلایدر را وارد کنید.!',
			'url'         => 'لینک اسلایدر را وارد کنید.!',
		),
	), 
	
	array(
		'id'    => 'info_warning',
		'type'  => 'info',
		'title' => 'یکی از حالت های اسلایدر را انتخاب کنید',
		'style' => 'warning',
		'desc'  => 'برای استفاده بهتر اسلایدر تنها یکی از حالت ها را انتخاب کنید'
	), 
	
	array(
		'id'       => 'slider01_products',
		'type'     => 'button_set',
		'title'    => 'فعال سازی محصولات اسلایدر',
		'required' => array( 'section_slider_show_01', '=', '1' ),
		'subtitle' => 'از این بخش می توانید محصولات اسلایدر را فعال سازی کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),
	),
	array(
		'id'       => 'slider01_products_title',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی عنوان محصولات',
		'required' => array( 'slider01_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید عنوان محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider01_products_price',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی قیمت محصولات',
		'required' => array( 'slider01_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید قیمت محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider_title_show_01',
		'type'     => 'button_set',
		'title'    => 'فعال سازی عنوان اسلایدر اول',
		'required' => array( 'section_slider_show_01', '=', '1' ),
		'subtitle' => 'از این بخش میتوانید عنوان اسلایدر اول را فعال یا غیرفعال کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),    
	),
	array(
		'id'          => 'slider01_title',
		'title'       => 'عنوان اسلایدر اول',
		'required' => array( 'slider01_products', '=', 'block' ),
		'required' => array( 'slider_title_show_01', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'فیلم های برگزیده'
	),
	array(
		'id'          => 'slider01_archive_title',
		'title'       => 'متن لینک آرشیو',
		'required' => array( 'slider01_products', '=', 'block' ),
		'required' => array( 'slider_title_show_01', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'بیشتر'
	),
	array(
		'id'          => 'slider01_product_archive_cat',
		'title'       => 'تغییر لینک آرشیو محصولات اسلایدر اول',
		'required' => array( 'slider01_products', '=', 'block' ),
		'required' => array( 'slider_title_show_01', '=', 'block' ),
		'subtitle'        => 'لینک آرشیو محصولات را وارد کنید..',
		'type'        => 'text',
		'validate' => 'url',
		'default'  => ''.get_site_url().'/shop'
	),
	array(
		'id'          => 'slider01_product_count',
		'required' => array( 'slider01_products', '=', 'block' ),
		'title'       => 'تعداد محصولات جهت نمایش در اسلایدر اول',
		'subtitle'        => 'از این قسمت می توانید تعداد محصولات سایت را مشخص کنید.',
		'type'        => 'text',
		'default'         => '12'
	),
	
	array(
		'id'          => 'slider01_product_cat',
		'required' => array( 'slider01_products', '=', 'block' ),
		'type'        => 'select',
		'title'       => 'دسته بندی دلخواه برای نمایش در اسلایدر اول محصولات',
		'subtitle'        => 'از این قسمت می توانید دسته بندی دلخواه برای نمایش در اسلایدر اول محصولات را مشخص کنید.',
		'multi'		=> true,			
		'data' => 'tags',
			'args' => array(
				'taxonomy' => array( 'product_cat','product_tag' ),
		),
		'default'         => '15'
	),
	)	
	)); 
		
	Redux::setSection( $opt_name, array(
			'title'=> 'تنظیمات اسلایدر دوم',
			'subsection'       => true,
			'id'   => 'slider02_options',
			'customizer_width' => '400px',
			'fields'           => array(
	array(
		'id'       => 'section_slider02_settings',
		 'title'=> 'تنظیمات اسلایدر دوم',
		'type'     => 'section',
		'indent' => true,
	),
	array(
		'id'       => 'section_slider_show_02',
		 'type'    => 'switch',
		'title'    => 'فعال سازی اسلایدر دوم',
		'subtitle' => 'از این بخش میتوانید اسلایدر دوم سایت را فعال یا غیرفعال کنید.',
		'default'  => 1,
        'on'       => 'فعال',
        'off'      => 'غیرفعال',
	),
	array(
		'id'       => 'slider02_promo',
		'type'    => 'button_set',
		'required' => array( 'section_slider_show_02', '=', '1' ),
		'title'    => 'فعال سازی حالت تبلیغی اسلایدر',
		'subtitle' => 'از این بخش میتوانید اسلایدر دوم سایت را فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		), 
	),
	array(
		'id'          => 'slider02_promo_images',
		'type'        => 'slides',
		'required' => array( 'slider02_promo', '=', 'block' ),
		'subtitle'    => 'اسلایدهای موجود را از این قسمت مدیریت کنید.',
		'placeholder' => array(
			'title'       => 'عنوان اسلایدر را وارد کنید.!',
			'subtitleription' => 'توضیحات اسلایدر را وارد کنید.!',
			'url'         => 'لینک اسلایدر را وارد کنید.!',
		),
	), 
	
	array(
		'id'    => 'info_warning',
		'type'  => 'info',
		'title' => 'یکی از حالت های اسلایدر را انتخاب کنید',
		'style' => 'warning',
		'desc'  => 'برای استفاده بهتر اسلایدر تنها یکی از حالت ها را انتخاب کنید'
	), 
	
	array(
		'id'       => 'slider02_products',
		'type'     => 'button_set',
		'title'    => 'فعال سازی محصولات اسلایدر',
		'required' => array( 'section_slider_show_02', '=', '1' ),
		'subtitle' => 'از این بخش می توانید محصولات اسلایدر را فعال سازی کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),
	),
	array(
		'id'       => 'slider02_products_title',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی عنوان محصولات',
		'required' => array( 'slider02_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید عنوان محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider02_products_price',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی قیمت محصولات',
		'required' => array( 'slider02_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید قیمت محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider_title_show_02',
		'type'     => 'button_set',
		'title'    => 'فعال سازی عنوان اسلایدر دوم',
		'required' => array( 'section_slider_show_02', '=', '1' ),
		'subtitle' => 'از این بخش میتوانید عنوان اسلایدر دوم را فعال یا غیرفعال کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),    
	),
	array(
		'id'          => 'slider02_title',
		'title'       => 'عنوان اسلایدر دوم',
		'required' => array( 'slider02_products', '=', 'block' ),
		'required' => array( 'slider_title_show_02', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'فیلم های برگزیده'
	),
	array(
		'id'          => 'slider02_archive_title',
		'title'       => 'متن لینک آرشیو',
		'required' => array( 'slider02_products', '=', 'block' ),
		'required' => array( 'slider_title_show_02', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'بیشتر'
	),
	array(
		'id'          => 'slider02_product_archive_cat',
		'title'       => 'تغییر لینک آرشیو محصولات اسلایدر دوم',
		'required' => array( 'slider02_products', '=', 'block' ),
		'required' => array( 'slider_title_show_02', '=', 'block' ),
		'subtitle'        => 'لینک آرشیو محصولات را وارد کنید..',
		'type'        => 'text',
		'validate' => 'url',
		'default'  => ''.get_site_url().'/shop'
	),
	array(
		'id'          => 'slider02_product_count',
		'required' => array( 'slider02_products', '=', 'block' ),
		'title'       => 'تعداد محصولات جهت نمایش در اسلایدر دوم',
		'subtitle'        => 'از این قسمت می توانید تعداد محصولات سایت را مشخص کنید.',
		'type'        => 'text',
		'default'         => '12'
	),
	
	array(
		'id'          => 'slider02_product_cat',
		'required' => array( 'slider02_products', '=', 'block' ),
		'type'        => 'select',
		'title'       => 'دسته بندی دلخواه برای نمایش در اسلایدر دوم محصولات',
		'subtitle'        => 'از این قسمت می توانید دسته بندی دلخواه برای نمایش در اسلایدر دوم محصولات را مشخص کنید.',
		'multi'		=> true,			
		'data' => 'tags',
			'args' => array(
				'taxonomy' => array( 'product_cat','product_tag' ),
		),
		'default'         => '15'
	),
	)
		) );


	Redux::setSection( $opt_name, array(
			'title'=> 'تنظیمات اسلایدر سوم',
			'subsection'       => true,
			'id'   => 'slider03_options',
			'customizer_width' => '400px',
			'fields'           => array(
	array(
		'id'       => 'section_slider03_settings',
		 'title'=> 'تنظیمات اسلایدر سوم',
		'type'     => 'section',
		'indent' => true,
	),
	array(
		'id'       => 'section_slider_show_03',
		 'type'    => 'switch',
		'title'    => 'فعال سازی اسلایدر سوم',
		'subtitle' => 'از این بخش میتوانید اسلایدر سوم سایت را فعال یا غیرفعال کنید.',
		'default'  => 1,
        'on'       => 'فعال',
        'off'      => 'غیرفعال',
	),
	array(
		'id'       => 'slider03_promo',
		'type'    => 'button_set',
		'required' => array( 'section_slider_show_03', '=', '1' ),
		'title'    => 'فعال سازی حالت تبلیغی اسلایدر',
		'subtitle' => 'از این بخش میتوانید اسلایدر سوم سایت را فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		), 
	),
	array(
		'id'          => 'slider03_promo_images',
		'type'        => 'slides',
		'required' => array( 'slider03_promo', '=', 'block' ),
		'subtitle'    => 'اسلایدهای موجود را از این قسمت مدیریت کنید.',
		'placeholder' => array(
			'title'       => 'عنوان اسلایدر را وارد کنید.!',
			'subtitleription' => 'توضیحات اسلایدر را وارد کنید.!',
			'url'         => 'لینک اسلایدر را وارد کنید.!',
		),
	), 
	
	array(
		'id'    => 'info_warning',
		'type'  => 'info',
		'title' => 'یکی از حالت های اسلایدر را انتخاب کنید',
		'style' => 'warning',
		'desc'  => 'برای استفاده بهتر اسلایدر تنها یکی از حالت ها را انتخاب کنید'
	), 
	
	array(
		'id'       => 'slider03_products',
		'type'     => 'button_set',
		'title'    => 'فعال سازی محصولات اسلایدر',
		'required' => array( 'section_slider_show_03', '=', '1' ),
		'subtitle' => 'از این بخش می توانید محصولات اسلایدر را فعال سازی کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),
	),
	array(
		'id'       => 'slider03_products_title',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی عنوان محصولات',
		'required' => array( 'slider03_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید عنوان محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider03_products_price',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی قیمت محصولات',
		'required' => array( 'slider03_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید قیمت محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider_title_show_03',
		'type'     => 'button_set',
		'title'    => 'فعال سازی عنوان اسلایدر سوم',
		'required' => array( 'section_slider_show_03', '=', '1' ),
		'subtitle' => 'از این بخش میتوانید عنوان اسلایدر سوم را فعال یا غیرفعال کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),    
	),
	array(
		'id'          => 'slider03_title',
		'title'       => 'عنوان اسلایدر سوم',
		'required' => array( 'slider03_products', '=', 'block' ),
		'required' => array( 'slider_title_show_03', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'فیلم های برگزیده'
	),
	array(
		'id'          => 'slider03_archive_title',
		'title'       => 'متن لینک آرشیو',
		'required' => array( 'slider03_products', '=', 'block' ),
		'required' => array( 'slider_title_show_03', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'بیشتر'
	),
	array(
		'id'          => 'slider03_product_archive_cat',
		'title'       => 'تغییر لینک آرشیو محصولات اسلایدر سوم',
		'required' => array( 'slider03_products', '=', 'block' ),
		'required' => array( 'slider_title_show_03', '=', 'block' ),
		'subtitle'        => 'لینک آرشیو محصولات را وارد کنید..',
		'type'        => 'text',
		'validate' => 'url',
		'default'  => ''.get_site_url().'/shop'
	),
	array(
		'id'          => 'slider03_product_count',
		'required' => array( 'slider03_products', '=', 'block' ),
		'title'       => 'تعداد محصولات جهت نمایش در اسلایدر سوم',
		'subtitle'        => 'از این قسمت می توانید تعداد محصولات سایت را مشخص کنید.',
		'type'        => 'text',
		'default'         => '12'
	),
	
	array(
		'id'          => 'slider03_product_cat',
		'required' => array( 'slider03_products', '=', 'block' ),
		'type'        => 'select',
		'title'       => 'دسته بندی دلخواه برای نمایش در اسلایدر سوم محصولات',
		'subtitle'        => 'از این قسمت می توانید دسته بندی دلخواه برای نمایش در اسلایدر سوم محصولات را مشخص کنید.',
		'multi'		=> true,			
		'data' => 'tags',
			'args' => array(
				'taxonomy' => array( 'product_cat','product_tag' ),
		),
		'default'         => '15'
	),
	)
		) );
	
	Redux::setSection( $opt_name, array(
			'title'=> 'تنظیمات اسلایدر چهارم',
			'subsection'       => true,
			'id'   => 'slider04_options',
			'customizer_width' => '400px',
			'fields'           => array(
	array(
		'id'       => 'section_slider04_settings',
		 'title'=> 'تنظیمات اسلایدر چهارم',
		'type'     => 'section',
		'indent' => true,
	),
	array(
		'id'       => 'section_slider_show_04',
		 'type'    => 'switch',
		'title'    => 'فعال سازی اسلایدر چهارم',
		'subtitle' => 'از این بخش میتوانید اسلایدر چهارم سایت را فعال یا غیرفعال کنید.',
		'default'  => 1,
        'on'       => 'فعال',
        'off'      => 'غیرفعال',
	),
	array(
		'id'       => 'slider04_promo',
		'type'    => 'button_set',
		'required' => array( 'section_slider_show_04', '=', '1' ),
		'title'    => 'فعال سازی حالت تبلیغی اسلایدر',
		'subtitle' => 'از این بخش میتوانید اسلایدر چهارم سایت را فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		), 
	),
	array(
		'id'          => 'slider04_promo_images',
		'type'        => 'slides',
		'required' => array( 'slider04_promo', '=', 'block' ),
		'subtitle'    => 'اسلایدهای موجود را از این قسمت مدیریت کنید.',
		'placeholder' => array(
			'title'       => 'عنوان اسلایدر را وارد کنید.!',
			'subtitleription' => 'توضیحات اسلایدر را وارد کنید.!',
			'url'         => 'لینک اسلایدر را وارد کنید.!',
		),
	), 
	
	array(
		'id'    => 'info_warning',
		'type'  => 'info',
		'title' => 'یکی از حالت های اسلایدر را انتخاب کنید',
		'style' => 'warning',
		'desc'  => 'برای استفاده بهتر اسلایدر تنها یکی از حالت ها را انتخاب کنید'
	), 
	
	array(
		'id'       => 'slider04_products',
		'type'     => 'button_set',
		'title'    => 'فعال سازی محصولات اسلایدر',
		'required' => array( 'section_slider_show_04', '=', '1' ),
		'subtitle' => 'از این بخش می توانید محصولات اسلایدر را فعال سازی کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),
	),
	array(
		'id'       => 'slider04_products_title',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی عنوان محصولات',
		'required' => array( 'slider04_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید عنوان محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider04_products_price',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی قیمت محصولات',
		'required' => array( 'slider04_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید قیمت محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider_title_show_04',
		'type'     => 'button_set',
		'title'    => 'فعال سازی عنوان اسلایدر چهارم',
		'required' => array( 'section_slider_show_04', '=', '1' ),
		'subtitle' => 'از این بخش میتوانید عنوان اسلایدر چهارم را فعال یا غیرفعال کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),    
	),
	array(
		'id'          => 'slider04_title',
		'title'       => 'عنوان اسلایدر چهارم',
		'required' => array( 'slider04_products', '=', 'block' ),
		'required' => array( 'slider_title_show_04', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'فیلم های برگزیده'
	),
	array(
		'id'          => 'slider04_archive_title',
		'title'       => 'متن لینک آرشیو',
		'required' => array( 'slider04_products', '=', 'block' ),
		'required' => array( 'slider_title_show_04', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'بیشتر'
	),
	array(
		'id'          => 'slider04_product_archive_cat',
		'title'       => 'تغییر لینک آرشیو محصولات اسلایدر چهارم',
		'required' => array( 'slider04_products', '=', 'block' ),
		'required' => array( 'slider_title_show_04', '=', 'block' ),
		'subtitle'        => 'لینک آرشیو محصولات را وارد کنید..',
		'type'        => 'text',
		'validate' => 'url',
		'default'  => ''.get_site_url().'/shop'
	),
	array(
		'id'          => 'slider04_product_count',
		'required' => array( 'slider04_products', '=', 'block' ),
		'title'       => 'تعداد محصولات جهت نمایش در اسلایدر چهارم',
		'subtitle'        => 'از این قسمت می توانید تعداد محصولات سایت را مشخص کنید.',
		'type'        => 'text',
		'default'         => '12'
	),
	
	array(
		'id'          => 'slider04_product_cat',
		'required' => array( 'slider04_products', '=', 'block' ),
		'type'        => 'select',
		'title'       => 'دسته بندی دلخواه برای نمایش در اسلایدر چهارم محصولات',
		'subtitle'        => 'از این قسمت می توانید دسته بندی دلخواه برای نمایش در اسلایدر چهارم محصولات را مشخص کنید.',
		'multi'		=> true,			
		'data' => 'tags',
			'args' => array(
				'taxonomy' => array( 'product_cat','product_tag' ),
		),
		'default'         => '15'
	),
	)
		) );
	
	
	Redux::setSection( $opt_name, array(
			'title'=> 'تنظیمات اسلایدر پنجم',
			'subsection'       => true,
			'id'   => 'slider05_options',
			'customizer_width' => '400px',
			'fields'           => array(
	array(
		'id'       => 'section_slider05_settings',
		 'title'=> 'تنظیمات اسلایدر پنجم',
		'type'     => 'section',
		'indent' => true,
	),
	array(
		'id'       => 'section_slider_show_05',
		 'type'    => 'switch',
		'title'    => 'فعال سازی اسلایدر پنجم',
		'subtitle' => 'از این بخش میتوانید اسلایدر پنجم سایت را فعال یا غیرفعال کنید.',
		'default'  => 1,
        'on'       => 'فعال',
        'off'      => 'غیرفعال',
	),
	array(
		'id'       => 'slider05_promo',
		'type'    => 'button_set',
		'required' => array( 'section_slider_show_05', '=', '1' ),
		'title'    => 'فعال سازی حالت تبلیغی اسلایدر',
		'subtitle' => 'از این بخش میتوانید اسلایدر پنجم سایت را فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		), 
	),
	array(
		'id'          => 'slider05_promo_images',
		'type'        => 'slides',
		'required' => array( 'slider05_promo', '=', 'block' ),
		'subtitle'    => 'اسلایدهای موجود را از این قسمت مدیریت کنید.',
		'placeholder' => array(
			'title'       => 'عنوان اسلایدر را وارد کنید.!',
			'subtitleription' => 'توضیحات اسلایدر را وارد کنید.!',
			'url'         => 'لینک اسلایدر را وارد کنید.!',
		),
	), 
	
	array(
		'id'    => 'info_warning',
		'type'  => 'info',
		'title' => 'یکی از حالت های اسلایدر را انتخاب کنید',
		'style' => 'warning',
		'desc'  => 'برای استفاده بهتر اسلایدر تنها یکی از حالت ها را انتخاب کنید'
	), 
	
	array(
		'id'       => 'slider05_products',
		'type'     => 'button_set',
		'title'    => 'فعال سازی محصولات اسلایدر',
		'required' => array( 'section_slider_show_05', '=', '1' ),
		'subtitle' => 'از این بخش می توانید محصولات اسلایدر را فعال سازی کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),
	),
	array(
		'id'       => 'slider05_products_title',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی عنوان محصولات',
		'required' => array( 'slider05_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید عنوان محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider05_products_price',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی قیمت محصولات',
		'required' => array( 'slider05_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید قیمت محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider_title_show_05',
		'type'     => 'button_set',
		'title'    => 'فعال سازی عنوان اسلایدر پنجم',
		'required' => array( 'section_slider_show_05', '=', '1' ),
		'subtitle' => 'از این بخش میتوانید عنوان اسلایدر پنجم را فعال یا غیرفعال کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),    
	),
	array(
		'id'          => 'slider05_title',
		'title'       => 'عنوان اسلایدر پنجم',
		'required' => array( 'slider05_products', '=', 'block' ),
		'required' => array( 'slider_title_show_05', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'فیلم های برگزیده'
	),
	array(
		'id'          => 'slider05_archive_title',
		'title'       => 'متن لینک آرشیو',
		'required' => array( 'slider05_products', '=', 'block' ),
		'required' => array( 'slider_title_show_05', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'بیشتر'
	),
	array(
		'id'          => 'slider05_product_archive_cat',
		'title'       => 'تغییر لینک آرشیو محصولات اسلایدر پنجم',
		'required' => array( 'slider05_products', '=', 'block' ),
		'required' => array( 'slider_title_show_05', '=', 'block' ),
		'subtitle'        => 'لینک آرشیو محصولات را وارد کنید..',
		'type'        => 'text',
		'validate' => 'url',
		'default'  => ''.get_site_url().'/shop'
	),
	array(
		'id'          => 'slider05_product_count',
		'required' => array( 'slider05_products', '=', 'block' ),
		'title'       => 'تعداد محصولات جهت نمایش در اسلایدر پنجم',
		'subtitle'        => 'از این قسمت می توانید تعداد محصولات سایت را مشخص کنید.',
		'type'        => 'text',
		'default'         => '12'
	),
	
	array(
		'id'          => 'slider05_product_cat',
		'required' => array( 'slider05_products', '=', 'block' ),
		'type'        => 'select',
		'title'       => 'دسته بندی دلخواه برای نمایش در اسلایدر پنجم محصولات',
		'subtitle'        => 'از این قسمت می توانید دسته بندی دلخواه برای نمایش در اسلایدر پنجم محصولات را مشخص کنید.',
		'multi'		=> true,			
		'data' => 'tags',
			'args' => array(
				'taxonomy' => array( 'product_cat','product_tag' ),
		),
		'default'         => '15'
	),
	)
		) );
		
		
	Redux::setSection( $opt_name, array(
			'title'=> 'تنظیمات اسلایدر ششم',
			'subsection'       => true,
			'id'   => 'slider06_options',
			'customizer_width' => '400px',
			'fields'           => array(
	array(
		'id'       => 'section_slider06_settings',
		 'title'=> 'تنظیمات اسلایدر ششم',
		'type'     => 'section',
		'indent' => true,
	),
	array(
		'id'       => 'section_slider_show_06',
		 'type'    => 'switch',
		'title'    => 'فعال سازی اسلایدر ششم',
		'subtitle' => 'از این بخش میتوانید اسلایدر ششم سایت را فعال یا غیرفعال کنید.',
		'default'  => 1,
        'on'       => 'فعال',
        'off'      => 'غیرفعال',
	),
	array(
		'id'       => 'slider06_promo',
		'type'    => 'button_set',
		'required' => array( 'section_slider_show_06', '=', '1' ),
		'title'    => 'فعال سازی حالت تبلیغی اسلایدر',
		'subtitle' => 'از این بخش میتوانید اسلایدر ششم سایت را فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		), 
	),
	array(
		'id'          => 'slider06_promo_images',
		'type'        => 'slides',
		'required' => array( 'slider06_promo', '=', 'block' ),
		'subtitle'    => 'اسلایدهای موجود را از این قسمت مدیریت کنید.',
		'placeholder' => array(
			'title'       => 'عنوان اسلایدر را وارد کنید.!',
			'subtitleription' => 'توضیحات اسلایدر را وارد کنید.!',
			'url'         => 'لینک اسلایدر را وارد کنید.!',
		),
	), 
	
	array(
		'id'    => 'info_warning',
		'type'  => 'info',
		'title' => 'یکی از حالت های اسلایدر را انتخاب کنید',
		'style' => 'warning',
		'desc'  => 'برای استفاده بهتر اسلایدر تنها یکی از حالت ها را انتخاب کنید'
	), 
	
	array(
		'id'       => 'slider06_products',
		'type'     => 'button_set',
		'title'    => 'فعال سازی محصولات اسلایدر',
		'required' => array( 'section_slider_show_06', '=', '1' ),
		'subtitle' => 'از این بخش می توانید محصولات اسلایدر را فعال سازی کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),
	),
	array(
		'id'       => 'slider06_products_title',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی عنوان محصولات',
		'required' => array( 'slider06_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید عنوان محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider06_products_price',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی قیمت محصولات',
		'required' => array( 'slider06_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید قیمت محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider_title_show_06',
		'type'     => 'button_set',
		'title'    => 'فعال سازی عنوان اسلایدر ششم',
		'required' => array( 'section_slider_show_06', '=', '1' ),
		'subtitle' => 'از این بخش میتوانید عنوان اسلایدر ششم را فعال یا غیرفعال کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),    
	),
	array(
		'id'          => 'slider06_title',
		'title'       => 'عنوان اسلایدر ششم',
		'required' => array( 'slider06_products', '=', 'block' ),
		'required' => array( 'slider_title_show_06', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'فیلم های برگزیده'
	),
	array(
		'id'          => 'slider06_archive_title',
		'title'       => 'متن لینک آرشیو',
		'required' => array( 'slider06_products', '=', 'block' ),
		'required' => array( 'slider_title_show_06', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'بیشتر'
	),
	array(
		'id'          => 'slider06_product_archive_cat',
		'title'       => 'تغییر لینک آرشیو محصولات اسلایدر ششم',
		'required' => array( 'slider06_products', '=', 'block' ),
		'required' => array( 'slider_title_show_06', '=', 'block' ),
		'subtitle'        => 'لینک آرشیو محصولات را وارد کنید..',
		'type'        => 'text',
		'validate' => 'url',
		'default'  => ''.get_site_url().'/shop'
	),
	array(
		'id'          => 'slider06_product_count',
		'required' => array( 'slider06_products', '=', 'block' ),
		'title'       => 'تعداد محصولات جهت نمایش در اسلایدر ششم',
		'subtitle'        => 'از این قسمت می توانید تعداد محصولات سایت را مشخص کنید.',
		'type'        => 'text',
		'default'         => '12'
	),
	
	array(
		'id'          => 'slider06_product_cat',
		'required' => array( 'slider06_products', '=', 'block' ),
		'type'        => 'select',
		'title'       => 'دسته بندی دلخواه برای نمایش در اسلایدر ششم محصولات',
		'subtitle'        => 'از این قسمت می توانید دسته بندی دلخواه برای نمایش در اسلایدر ششم محصولات را مشخص کنید.',
		'multi'		=> true,			
		'data' => 'tags',
			'args' => array(
				'taxonomy' => array( 'product_cat','product_tag' ),
		),
		'default'         => '15'
	),
	)
		) );
		
		
	Redux::setSection( $opt_name, array(
			'title'=> 'تنظیمات اسلایدر هفتم',
			'subsection'       => true,
			'id'   => 'slider07_options',
			'customizer_width' => '400px',
			'fields'           => array(
	array(
		'id'       => 'section_slider07_settings',
		 'title'=> 'تنظیمات اسلایدر هفتم',
		'type'     => 'section',
		'indent' => true,
	),
	array(
		'id'       => 'section_slider_show_07',
		 'type'    => 'switch',
		'title'    => 'فعال سازی اسلایدر هفتم',
		'subtitle' => 'از این بخش میتوانید اسلایدر هفتم سایت را فعال یا غیرفعال کنید.',
		'default'  => 1,
        'on'       => 'فعال',
        'off'      => 'غیرفعال',
	),
	array(
		'id'       => 'slider07_promo',
		'type'    => 'button_set',
		'required' => array( 'section_slider_show_07', '=', '1' ),
		'title'    => 'فعال سازی حالت تبلیغی اسلایدر',
		'subtitle' => 'از این بخش میتوانید اسلایدر هفتم سایت را فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		), 
	),
	array(
		'id'          => 'slider07_promo_images',
		'type'        => 'slides',
		'required' => array( 'slider07_promo', '=', 'block' ),
		'subtitle'    => 'اسلایدهای موجود را از این قسمت مدیریت کنید.',
		'placeholder' => array(
			'title'       => 'عنوان اسلایدر را وارد کنید.!',
			'subtitleription' => 'توضیحات اسلایدر را وارد کنید.!',
			'url'         => 'لینک اسلایدر را وارد کنید.!',
		),
	), 
	
	array(
		'id'    => 'info_warning',
		'type'  => 'info',
		'title' => 'یکی از حالت های اسلایدر را انتخاب کنید',
		'style' => 'warning',
		'desc'  => 'برای استفاده بهتر اسلایدر تنها یکی از حالت ها را انتخاب کنید'
	), 
	
	array(
		'id'       => 'slider07_products',
		'type'     => 'button_set',
		'title'    => 'فعال سازی محصولات اسلایدر',
		'required' => array( 'section_slider_show_07', '=', '1' ),
		'subtitle' => 'از این بخش می توانید محصولات اسلایدر را فعال سازی کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),
	),
	array(
		'id'       => 'slider07_products_title',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی عنوان محصولات',
		'required' => array( 'slider07_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید عنوان محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider07_products_price',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی قیمت محصولات',
		'required' => array( 'slider07_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید قیمت محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider_title_show_07',
		'type'     => 'button_set',
		'title'    => 'فعال سازی عنوان اسلایدر هفتم',
		'required' => array( 'section_slider_show_07', '=', '1' ),
		'subtitle' => 'از این بخش میتوانید عنوان اسلایدر هفتم را فعال یا غیرفعال کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),    
	),
	array(
		'id'          => 'slider07_title',
		'title'       => 'عنوان اسلایدر هفتم',
		'required' => array( 'slider07_products', '=', 'block' ),
		'required' => array( 'slider_title_show_07', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'فیلم های برگزیده'
	),
	array(
		'id'          => 'slider07_archive_title',
		'title'       => 'متن لینک آرشیو',
		'required' => array( 'slider07_products', '=', 'block' ),
		'required' => array( 'slider_title_show_07', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'بیشتر'
	),
	array(
		'id'          => 'slider07_product_archive_cat',
		'title'       => 'تغییر لینک آرشیو محصولات اسلایدر هفتم',
		'required' => array( 'slider07_products', '=', 'block' ),
		'required' => array( 'slider_title_show_07', '=', 'block' ),
		'subtitle'        => 'لینک آرشیو محصولات را وارد کنید..',
		'type'        => 'text',
		'validate' => 'url',
		'default'  => ''.get_site_url().'/shop'
	),
	array(
		'id'          => 'slider07_product_count',
		'required' => array( 'slider07_products', '=', 'block' ),
		'title'       => 'تعداد محصولات جهت نمایش در اسلایدر هفتم',
		'subtitle'        => 'از این قسمت می توانید تعداد محصولات سایت را مشخص کنید.',
		'type'        => 'text',
		'default'         => '12'
	),
	
	array(
		'id'          => 'slider07_product_cat',
		'required' => array( 'slider07_products', '=', 'block' ),
		'type'        => 'select',
		'title'       => 'دسته بندی دلخواه برای نمایش در اسلایدر هفتم محصولات',
		'subtitle'        => 'از این قسمت می توانید دسته بندی دلخواه برای نمایش در اسلایدر هفتم محصولات را مشخص کنید.',
		'multi'		=> true,			
		'data' => 'tags',
			'args' => array(
				'taxonomy' => array( 'product_cat','product_tag' ),
		),
		'default'         => '15'
	),
	)
		) );
		
	
	Redux::setSection( $opt_name, array(
			'title'=> 'تنظیمات اسلایدر هشتم',
			'subsection'       => true,
			'id'   => 'slider08_options',
			'customizer_width' => '400px',
			'fields'           => array(
	array(
		'id'       => 'section_slider08_settings',
		 'title'=> 'تنظیمات اسلایدر هشتم',
		'type'     => 'section',
		'indent' => true,
	),
	array(
		'id'       => 'section_slider_show_08',
		 'type'    => 'switch',
		'title'    => 'فعال سازی اسلایدر هشتم',
		'subtitle' => 'از این بخش میتوانید اسلایدر هشتم سایت را فعال یا غیرفعال کنید.',
		'default'  => 1,
        'on'       => 'فعال',
        'off'      => 'غیرفعال',
	),
	array(
		'id'       => 'slider08_promo',
		'type'    => 'button_set',
		'required' => array( 'section_slider_show_08', '=', '1' ),
		'title'    => 'فعال سازی حالت تبلیغی اسلایدر',
		'subtitle' => 'از این بخش میتوانید اسلایدر هشتم سایت را فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		), 
	),
	array(
		'id'          => 'slider08_promo_images',
		'type'        => 'slides',
		'required' => array( 'slider08_promo', '=', 'block' ),
		'subtitle'    => 'اسلایدهای موجود را از این قسمت مدیریت کنید.',
		'placeholder' => array(
			'title'       => 'عنوان اسلایدر را وارد کنید.!',
			'subtitleription' => 'توضیحات اسلایدر را وارد کنید.!',
			'url'         => 'لینک اسلایدر را وارد کنید.!',
		),
	), 
	
	array(
		'id'    => 'info_warning',
		'type'  => 'info',
		'title' => 'یکی از حالت های اسلایدر را انتخاب کنید',
		'style' => 'warning',
		'desc'  => 'برای استفاده بهتر اسلایدر تنها یکی از حالت ها را انتخاب کنید'
	), 
	
	array(
		'id'       => 'slider08_products',
		'type'     => 'button_set',
		'title'    => 'فعال سازی محصولات اسلایدر',
		'required' => array( 'section_slider_show_08', '=', '1' ),
		'subtitle' => 'از این بخش می توانید محصولات اسلایدر را فعال سازی کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),
	),
	array(
		'id'       => 'slider08_products_title',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی عنوان محصولات',
		'required' => array( 'slider08_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید عنوان محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider08_products_price',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی قیمت محصولات',
		'required' => array( 'slider08_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید قیمت محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider_title_show_08',
		'type'     => 'button_set',
		'title'    => 'فعال سازی عنوان اسلایدر هشتم',
		'required' => array( 'section_slider_show_08', '=', '1' ),
		'subtitle' => 'از این بخش میتوانید عنوان اسلایدر هشتم را فعال یا غیرفعال کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),    
	),
	array(
		'id'          => 'slider08_title',
		'title'       => 'عنوان اسلایدر هشتم',
		'required' => array( 'slider08_products', '=', 'block' ),
		'required' => array( 'slider_title_show_08', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'فیلم های برگزیده'
	),
	array(
		'id'          => 'slider08_archive_title',
		'title'       => 'متن لینک آرشیو',
		'required' => array( 'slider08_products', '=', 'block' ),
		'required' => array( 'slider_title_show_08', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'بیشتر'
	),
	array(
		'id'          => 'slider08_product_archive_cat',
		'title'       => 'تغییر لینک آرشیو محصولات اسلایدر هشتم',
		'required' => array( 'slider08_products', '=', 'block' ),
		'required' => array( 'slider_title_show_08', '=', 'block' ),
		'subtitle'        => 'لینک آرشیو محصولات را وارد کنید..',
		'type'        => 'text',
		'validate' => 'url',
		'default'  => ''.get_site_url().'/shop'
	),
	array(
		'id'          => 'slider08_product_count',
		'required' => array( 'slider08_products', '=', 'block' ),
		'title'       => 'تعداد محصولات جهت نمایش در اسلایدر هشتم',
		'subtitle'        => 'از این قسمت می توانید تعداد محصولات سایت را مشخص کنید.',
		'type'        => 'text',
		'default'         => '12'
	),
	
	array(
		'id'          => 'slider08_product_cat',
		'required' => array( 'slider08_products', '=', 'block' ),
		'type'        => 'select',
		'title'       => 'دسته بندی دلخواه برای نمایش در اسلایدر هشتم محصولات',
		'subtitle'        => 'از این قسمت می توانید دسته بندی دلخواه برای نمایش در اسلایدر هشتم محصولات را مشخص کنید.',
		'multi'		=> true,			
		'data' => 'tags',
			'args' => array(
				'taxonomy' => array( 'product_cat','product_tag' ),
		),
		'default'         => '15'
	),
	)
		) );
		
		
	Redux::setSection( $opt_name, array(
			'title'=> 'تنظیمات اسلایدر نهم',
			'subsection'       => true,
			'id'   => 'slider09_options',
			'customizer_width' => '400px',
			'fields'           => array(
	array(
		'id'       => 'section_slider09_settings',
		 'title'=> 'تنظیمات اسلایدر نهم',
		'type'     => 'section',
		'indent' => true,
	),
	array(
		'id'       => 'section_slider_show_09',
		 'type'    => 'switch',
		'title'    => 'فعال سازی اسلایدر نهم',
		'subtitle' => 'از این بخش میتوانید اسلایدر نهم سایت را فعال یا غیرفعال کنید.',
		'default'  => 1,
        'on'       => 'فعال',
        'off'      => 'غیرفعال',
	),
	array(
		'id'       => 'slider09_promo',
		'type'    => 'button_set',
		'required' => array( 'section_slider_show_09', '=', '1' ),
		'title'    => 'فعال سازی حالت تبلیغی اسلایدر',
		'subtitle' => 'از این بخش میتوانید اسلایدر نهم سایت را فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		), 
	),
	array(
		'id'          => 'slider09_promo_images',
		'type'        => 'slides',
		'required' => array( 'slider09_promo', '=', 'block' ),
		'subtitle'    => 'اسلایدهای موجود را از این قسمت مدیریت کنید.',
		'placeholder' => array(
			'title'       => 'عنوان اسلایدر را وارد کنید.!',
			'subtitleription' => 'توضیحات اسلایدر را وارد کنید.!',
			'url'         => 'لینک اسلایدر را وارد کنید.!',
		),
	), 
	
	array(
		'id'    => 'info_warning',
		'type'  => 'info',
		'title' => 'یکی از حالت های اسلایدر را انتخاب کنید',
		'style' => 'warning',
		'desc'  => 'برای استفاده بهتر اسلایدر تنها یکی از حالت ها را انتخاب کنید'
	), 
	
	array(
		'id'       => 'slider09_products',
		'type'     => 'button_set',
		'title'    => 'فعال سازی محصولات اسلایدر',
		'required' => array( 'section_slider_show_09', '=', '1' ),
		'subtitle' => 'از این بخش می توانید محصولات اسلایدر را فعال سازی کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),
	),
	array(
		'id'       => 'slider09_products_title',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی عنوان محصولات',
		'required' => array( 'slider09_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید عنوان محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider09_products_price',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی قیمت محصولات',
		'required' => array( 'slider09_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید قیمت محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider_title_show_09',
		'type'     => 'button_set',
		'title'    => 'فعال سازی عنوان اسلایدر نهم',
		'required' => array( 'section_slider_show_09', '=', '1' ),
		'subtitle' => 'از این بخش میتوانید عنوان اسلایدر نهم را فعال یا غیرفعال کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),    
	),
	array(
		'id'          => 'slider09_title',
		'title'       => 'عنوان اسلایدر نهم',
		'required' => array( 'slider09_products', '=', 'block' ),
		'required' => array( 'slider_title_show_09', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'فیلم های برگزیده'
	),
	array(
		'id'          => 'slider09_archive_title',
		'title'       => 'متن لینک آرشیو',
		'required' => array( 'slider09_products', '=', 'block' ),
		'required' => array( 'slider_title_show_09', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'بیشتر'
	),
	array(
		'id'          => 'slider09_product_archive_cat',
		'title'       => 'تغییر لینک آرشیو محصولات اسلایدر نهم',
		'required' => array( 'slider09_products', '=', 'block' ),
		'required' => array( 'slider_title_show_09', '=', 'block' ),
		'subtitle'        => 'لینک آرشیو محصولات را وارد کنید..',
		'type'        => 'text',
		'validate' => 'url',
		'default'  => ''.get_site_url().'/shop'
	),
	array(
		'id'          => 'slider09_product_count',
		'required' => array( 'slider09_products', '=', 'block' ),
		'title'       => 'تعداد محصولات جهت نمایش در اسلایدر نهم',
		'subtitle'        => 'از این قسمت می توانید تعداد محصولات سایت را مشخص کنید.',
		'type'        => 'text',
		'default'         => '12'
	),
	
	array(
		'id'          => 'slider09_product_cat',
		'required' => array( 'slider09_products', '=', 'block' ),
		'type'        => 'select',
		'title'       => 'دسته بندی دلخواه برای نمایش در اسلایدر نهم محصولات',
		'subtitle'        => 'از این قسمت می توانید دسته بندی دلخواه برای نمایش در اسلایدر نهم محصولات را مشخص کنید.',
		'multi'		=> true,			
		'data' => 'tags',
			'args' => array(
				'taxonomy' => array( 'product_cat','product_tag' ),
		),
		'default'         => '15'
	),
	)
		) );
		
		
	Redux::setSection( $opt_name, array(
			'title'=> 'تنظیمات اسلایدر دهم',
			'subsection'       => true,
			'id'   => 'slider10_options',
			'customizer_width' => '400px',
			'fields'           => array(
	array(
		'id'       => 'section_slider10_settings',
		 'title'=> 'تنظیمات اسلایدر دهم',
		'type'     => 'section',
		'indent' => true,
	),
	array(
		'id'       => 'section_slider_show_10',
		 'type'    => 'switch',
		'title'    => 'فعال سازی اسلایدر دهم',
		'subtitle' => 'از این بخش میتوانید اسلایدر دهم سایت را فعال یا غیرفعال کنید.',
		'default'  => 1,
        'on'       => 'فعال',
        'off'      => 'غیرفعال',
	),
	array(
		'id'       => 'slider10_promo',
		'type'    => 'button_set',
		'required' => array( 'section_slider_show_10', '=', '1' ),
		'title'    => 'فعال سازی حالت تبلیغی اسلایدر',
		'subtitle' => 'از این بخش میتوانید اسلایدر دهم سایت را فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		), 
	),
	array(
		'id'          => 'slider10_promo_images',
		'type'        => 'slides',
		'required' => array( 'slider10_promo', '=', 'block' ),
		'subtitle'    => 'اسلایدهای موجود را از این قسمت مدیریت کنید.',
		'placeholder' => array(
			'title'       => 'عنوان اسلایدر را وارد کنید.!',
			'subtitleription' => 'توضیحات اسلایدر را وارد کنید.!',
			'url'         => 'لینک اسلایدر را وارد کنید.!',
		),
	), 
	
	array(
		'id'    => 'info_warning',
		'type'  => 'info',
		'title' => 'یکی از حالت های اسلایدر را انتخاب کنید',
		'style' => 'warning',
		'desc'  => 'برای استفاده بهتر اسلایدر تنها یکی از حالت ها را انتخاب کنید'
	), 
	
	array(
		'id'       => 'slider10_products',
		'type'     => 'button_set',
		'title'    => 'فعال سازی محصولات اسلایدر',
		'required' => array( 'section_slider_show_10', '=', '1' ),
		'subtitle' => 'از این بخش می توانید محصولات اسلایدر را فعال سازی کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),
	),
	array(
		'id'       => 'slider10_products_title',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی عنوان محصولات',
		'required' => array( 'slider10_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید عنوان محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider10_products_price',
		'type'     => 'button_set',
		'title'    => 'فعال یا غیرفعال سازی قیمت محصولات',
		'required' => array( 'slider10_products', '=', 'block' ),
		'subtitle' => 'از این بخش می توانید قیمت محصولات را در اسلایدر فعال یا غیرفعال کنید.',
		'default' => 'none',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),       
	),
	array(
		'id'       => 'slider_title_show_10',
		'type'     => 'button_set',
		'title'    => 'فعال سازی عنوان اسلایدر دهم',
		'required' => array( 'section_slider_show_10', '=', '1' ),
		'subtitle' => 'از این بخش میتوانید عنوان اسلایدر دهم را فعال یا غیرفعال کنید.',
		'default' => 'block',
		'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),    
	),
	array(
		'id'          => 'slider10_title',
		'title'       => 'عنوان اسلایدر دهم',
		'required' => array( 'slider10_products', '=', 'block' ),
		'required' => array( 'slider_title_show_10', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'فیلم های برگزیده'
	),
	array(
		'id'          => 'slider10_archive_title',
		'title'       => 'متن لینک آرشیو',
		'required' => array( 'slider10_products', '=', 'block' ),
		'required' => array( 'slider_title_show_10', '=', 'block' ),
		'subtitle'        => 'از این قسمت می توانید این متن را تغییر دهید.',
		'type'        => 'text',
		'default'         => 'بیشتر'
	),
	array(
		'id'          => 'slider10_product_archive_cat',
		'title'       => 'تغییر لینک آرشیو محصولات اسلایدر دهم',
		'required' => array( 'slider10_products', '=', 'block' ),
		'required' => array( 'slider_title_show_10', '=', 'block' ),
		'subtitle'        => 'لینک آرشیو محصولات را وارد کنید..',
		'type'        => 'text',
		'validate' => 'url',
		'default'  => ''.get_site_url().'/shop'
	),
	array(
		'id'          => 'slider10_product_count',
		'required' => array( 'slider10_products', '=', 'block' ),
		'title'       => 'تعداد محصولات جهت نمایش در اسلایدر دهم',
		'subtitle'        => 'از این قسمت می توانید تعداد محصولات سایت را مشخص کنید.',
		'type'        => 'text',
		'default'         => '12'
	),
	
	array(
		'id'          => 'slider10_product_cat',
		'required' => array( 'slider10_products', '=', 'block' ),
		'type'        => 'select',
		'title'       => 'دسته بندی دلخواه برای نمایش در اسلایدر دهم محصولات',
		'subtitle'        => 'از این قسمت می توانید دسته بندی دلخواه برای نمایش در اسلایدر دهم محصولات را مشخص کنید.',
		'multi'		=> true,			
		'data' => 'tags',
			'args' => array(
				'taxonomy' => array( 'product_cat','product_tag' ),
		),
		'default'         => '15'
	),
	)
		) );
		
    Redux::setSection( $opt_name, array(
			'title'=> 'تنظیمات اسلایدر یازدهم (اینماد و ساماندهی)',
			'subsection'       => true,
			'id'   => 'slider11_options',
			'customizer_width' => '400px',
			'fields'           => array(
	array(
		'id'       => 'section_slider11_settings',
		 'title'=> 'تنظیمات اسلایدر یازدهم',
		'type'     => 'section',
		'indent' => true,
	),
	array(
		'id'       => 'slider11_settings',
		'type'     => 'button_set',
		'default' => 'inherit',
		'title'    => 'فعال یا غیرفعال سازی اسلایدر',
		'subtitle' => 'نمایش یا مخفی سازی اسلایدر یازدهم را مشخص کنید.!',
		'options'  => array(
			'inherit' => 'فعال',
			'none' => 'غیرفعال',
		),    
	),    
	array(
		'id'          => 'slider_enamad_samandehi',
		'type'        => 'slides',
		'required' => array( 'slider11_settings', '=', 'inherit' ),
		'subtitle'    => 'اسلایدهای موجود را از این قسمت مدیریت کنید.',
		'placeholder' => array(
			'title'       => 'عنوان اسلایدر را وارد کنید.!',
			'subtitleription' => 'توضیحات اسلایدر را وارد کنید.!',
			'url'         => 'لینک اسلایدر را وارد کنید.!',
		),
	), 
	)
		) );			
	
	// -> START Header Setting AND Styling

	Redux::setSection( $opt_name, array(
			'title'=> 'تنظیمات سربرگ',
			'id'   => 'header',
			'customizer_width' => '400px',
			'icon' => 'el el-list',
			'fields'     =>array(   
	array(
		'id'       => 'header_settings',
		'type'     => 'section',
		'indent'   => true,    
		'title'    => ' تنظیمات عمومی سربرگ',
	),
	array(
			'id'          => 'search_box',
			'title'       => 'نمایش باکس جستجو در سربرگ ',
			'type'        => 'button_set',
			'default'         => 'inherit',
			'subtitle'        => 'از این بخش می توانید باکس جستجو در سربرگ را نمایش یا پنهان کنید..',
			'options'  => array(
				'inherit' => 'فعال',
				'none' => 'غیرفعال',
		),    
	), 	
	array(
		'id'          => 'serch_text',
		'title'       => 'متن باکس جستجوی سربرگ',
		'required' => array( 'search_box', '=', 'inherit' ),
		'subtitle'    => 'متن پیش فرض باکس جستجو را در این قسمت وارد نمایید.',
		'type'        => 'text',
		'default'     => 'جستجو'		
	),	
	array(
	  'id'          => 'register_text_homepage',
	  'title'       => 'متن دکمه عضویت',
	  'type'        => 'text',
	  'subtitle'        => 'از این بخش می توانید متن دکمه عضویت را وارد کنید.',
	  'default'         => 'عضویت'		
	),	
	array(
	  'id'          => 'login_text_homepage',
	  'title'       => 'متن دکمه ورود',
	  'type'        => 'text',
	  'subtitle'        => 'از این بخش می توانید متن دکمه ورود را وارد کنید.',
	  'default'         => 'ورود'		
	),		
	array(
			'id'          => 'logo',
			'title'       => 'لوگو',
			'subtitle'        => 'شما میتوانید یک لوگو سفارشی از کتابخانه پرونده چند رسانه ای خود انتخاب کنید.. ',
			'type'     => 'media',
			'url'      => true,
			'compiler' => 'true',   
			'default' => array( 'url'=>''.get_template_directory_uri().'/assets/img/logo.png'),
	),
	array(
		'id'       => 'heaader_style',
		'type'     => 'section',
		'indent' => true,  
		'title'    => ' تنظیمات استایل دهی سربرگ',
	),
	array(
			'id'          => 'header_bg',
			'title'       => 'رنگ پس زمینه سربرگ',
			'type'     => 'background',
			'background-repeat'      => false,
			'background-position'      => false,
			'background-size'      => false,
			'background-attachment'      => false,
			'background-image'      => false,
			'default'  => array(
			'background-color' => '#ffffff', ),
	),
	array(
			'id'          => 'input_serch_back',
			'title'       => 'رنگ پس زمینه اینپوت جستجو',
			'type'     => 'background',
			'background-repeat'      => false,
			'background-position'      => false,
			'background-size'      => false,
			'background-attachment'      => false,
			'background-image'      => false,
			'default'  => array(
			'background-color' => '#f0f0f0', ),	
	),
	array(
			'id'          => 'btn_serch_icon',
			'title'       => 'رنگ آیکون دکمه جستجو',
			'type'        => 'color',
			'default'     => '#212529'
	),
	array(
			'id'          => 'input_serch_border',
			'title'       => 'رنگ حاشیه اینپوت جستجو',
			'type'        => 'color',
			'default'     => '#fff'
	),
)
));		
		
	// -> START Footer Setting

	Redux::setSection( $opt_name, array(
			'title'=> 'تنظیمات فوتر',
			'id'   => 'footer_setting',
			'customizer_width' => '400px',
			'icon' => 'el el-indent-left',
			'fields'     =>array(  
	array(
		'id'       => 'footer_section',
		'type'     => 'section',
		'indent' => true,  
		'title'    => ' تنظیمات فوتر',
	),		
	array(
			'id'       => 'bg_footer',
			'title'    => 'رنگ پس زمینه فوتر',
			'type'     => 'background',
			'background-repeat'      => false,
			'background-position'      => false,
			'background-size'      => false,
			'background-attachment'      => false,
			'background-image'      => false,
			'default'  => array(
			'background-color' => '#f3f3f2', ),
	),
	array(
			'id'          => 'footer_back_topbtn',
			'title'       => 'رنگ پس زمینه دکمه بازگشت به بالا',
			 'type'     => 'background',
			'background-repeat'      => false,
			'background-position'      => false,
			'background-size'      => false,
			'background-attachment'      => false,
			'background-image'      => false,
			'default'  => array(
			'background-color' => '#fff', ),
	),
	array(
			'id'          => 'footer_border_topbtn',
			'title'       => 'رنگ حاشیه دکمه بازگشت به بالا',
			'type'        => 'color',
			'default'     => '#28a745'
	),
	array(
			'id'          => 'footer_icon_color_topbtn',
			'title'       => 'رنگ آیکون دکمه بازگشت به بالا',
			'type'        => 'color',
			'default'     => '#28a745'
	),
	array(
			'id'          => 'footer_hover_back_topbtn',
			'title'       => 'رنگ پس زمینه دکمه بازگشت به بالا در حالت هاور',
			 'type'     => 'background',
			'background-repeat'      => false,
			'background-position'      => false,
			'background-size'      => false,
			'background-attachment'      => false,
			'background-image'      => false,
			'default'  => array(
			'background-color' => '#28a745', ),
	),
	array(
			'id'          => 'footer_hover_border_topbtn',
			'title'       => 'رنگ حاشیه دکمه بازگشت به بالا در حالت هاور',
			'type'        => 'color',
			'default'     => '#28a745'
	),
	array(
			'id'          => 'footer_hover_icon_color_topbtn',
			'title'       => 'رنگ آیکون دکمه بازگشت به بالا در حالت هاور',
			'type'        => 'color',
			'default'     => '#fff'
	),
	array(
			'id'          => 'instagram_color',
			'title'       => 'رنگ آیکون اینستاگرام',
			'subtitle'        => 'رنگ آیکون اینستاگرام موجود در فوتر را مشخص کنید.',
			'type'        => 'color',
			'default'         => '#a1a1a6'
	),	
	array(
			'id'          => 'telegram_color',
			'title'       => 'رنگ آیکون تلگرام',
			'subtitle'        => 'رنگ آیکون تلگرام موجود در فوتر را مشخص کنید.',
			'type'        => 'color',
			'default'         => '#a1a1a6'
	),		
	),
		
		) );    

	
	// -> START Font Setting
	Redux::setSection( $opt_name, array(
			'title'=> 'تنظیمات فونت',
			'id'   => 'font_setting',
			'customizer_width' => '400px',
			'icon' => 'el el-fontsize',
			 'fields'  => array (
	array(
		'id'       => 'font_family_section',
		'title'    => 'تنظیمات تایپوگرافی قالب',
			'type'     => 'section',
			'indent' => true
	),				 
	array(
    'id'       => 'body_font',
    'type'     => 'select',
    'title'    => 'فونت بدنه سایت',
    'subtitle' => 'از بین فونت های موجود یک فونت را برای فونت بدنه سایت خود انتخاب کنید!',
    'options'  => array(
      'IranSans' => 'ایرانسنس',
      'iranyekan' => 'ایران یکان',
      'naskhfont'   => 'دریود نسخ',
      'tahoma'   => 'تاهوما',
      'samimwp'   => 'سمیم',
      'yekanwp'   => 'یکان',
      'shabnamwp'   => 'شبنم',
      'vazirwp'   => 'وزیر',
      'koodakwp'   => 'کودک',
      'mitrawp'   => 'میترا',
      'nazaninwp'   => 'نازنین',
    ),    
    'default'  => 'IranSans'
	),
	array(
    'id'       => 'head_font',
    'type'     => 'select',
    'title'    => 'فونت سربرگ',
    'subtitle' => 'از بین فونت های موجود فونت را برای سربرگ سایت خود انتخاب کنید!',
    'options'  => array(
      'IranSans' => 'ایرانسنس',
      'iranyekan' => 'ایران یکان',
      'naskhfont'   => 'دریود نسخ',
      'tahoma'   => 'تاهوما',
      'samimwp'   => 'سمیم',
      'yekanwp'   => 'یکان',
      'shabnamwp'   => 'شبنم',
      'vazirwp'   => 'وزیر',
      'koodakwp'   => 'کودک',
      'mitrawp'   => 'میترا',
      'nazaninwp'   => 'نازنین',
    ),    
    'default'  => 'IranSans'
	),	 
	array(
    'id'       => 'lists_font',
    'type'     => 'select',
    'title'    => 'فونت لیست ها یا منوهای سایت',
    'subtitle' => 'از بین فونت های موجود یک فونت را برای فونت لیست ها یا منوهای سایت خود انتخاب کنید!',
    'options'  => array(
      'IranSans' => 'ایرانسنس',
      'iranyekan' => 'ایران یکان',
      'naskhfont'   => 'دریود نسخ',
      'tahoma'   => 'تاهوما',
      'samimwp'   => 'سمیم',
      'yekanwp'   => 'یکان',
      'shabnamwp'   => 'شبنم',
      'vazirwp'   => 'وزیر',
      'koodakwp'   => 'کودک',
      'mitrawp'   => 'میترا',
      'nazaninwp'   => 'نازنین',
    ),    
    'default'  => 'IranSans'
	),	
	array(
		'id'       => 'font_size_section',
		'title'    => 'اندازه فونت قسمت های مختلف سایت',
			'type'     => 'section',
			'indent' => true
	),		
	array(
    'id'       => 'p_font_size',
    'type'     => 'select',
    'title'    => 'فونت پاراگراف های سایت',
    'subtitle' => 'از بین فونت های موجود اندازه فونت پاراگراف های سایت خود انتخاب کنید!',
    'options'  => array(
      '10px' => '10px',
      '11px' => '11px',
      '12px' => '12px',
      '13px' => '13px',
      '14px' => '14px',
      '15px' => '15px',
      '16px' => '16px',
      '17px' => '17px',
      '18px' => '18px',
      '19px' => '19px',
      '20px' => '20px',
      '21px' => '21px',
      '22px' => '22px',
      '23px' => '23px',
      '24px' => '24px',
      '25px' => '25px',
      '26px' => '26px',
	  '27px' => '27px',
	  '28px' => '28px',
	  '29px' => '29px',
	  '30px' => '30px',
	  '31px' => '31px',
	  '32px' => '32px',
	  '33px' => '33px',
	  '34px' => '34px',
	  '35px' => '35px',
	  '36px' => '36px',
	  '37px' => '37px',
	  '38px' => '38px',
	  '39px' => '39px',
	  '40px' => '40px',
	  '41px' => '41px',
	  '42px' => '42px',
	  '43px' => '43px',
	  '44px' => '44px',
	  '45px' => '45px',
	  '46px' => '46px',
	  '47px' => '47px',
	  '48px' => '48px',
	  '49px' => '49px',
	  '50px' => '50px',	  
    ),    
    'default'  => '14px'
	),	
	array(
    'id'       => 'H6_font_size',
    'type'     => 'select',
    'title'    => 'اندازه فونت H6 ',
    'subtitle' => 'از بین فونت های موجود اندازه فونت H6 های سایت خود انتخاب کنید!',
    'options'  => array(
      '10px' => '10px',
      '11px' => '11px',
      '12px' => '12px',
      '13px' => '13px',
      '14px' => '14px',
      '15px' => '15px',
      '16px' => '16px',
      '17px' => '17px',
      '18px' => '18px',
      '19px' => '19px',
      '20px' => '20px',
      '21px' => '21px',
      '22px' => '22px',
      '23px' => '23px',
      '24px' => '24px',
      '25px' => '25px',
      '26px' => '26px',
	  '27px' => '27px',
	  '28px' => '28px',
	  '29px' => '29px',
	  '30px' => '30px',
	  '31px' => '31px',
	  '32px' => '32px',
	  '33px' => '33px',
	  '34px' => '34px',
	  '35px' => '35px',
	  '36px' => '36px',
	  '37px' => '37px',
	  '38px' => '38px',
	  '39px' => '39px',
	  '40px' => '40px',
	  '41px' => '41px',
	  '42px' => '42px',
	  '43px' => '43px',
	  '44px' => '44px',
	  '45px' => '45px',
	  '46px' => '46px',
	  '47px' => '47px',
	  '48px' => '48px',
	  '49px' => '49px',
	  '50px' => '50px',	  
    ),    
    'default'  => '12px'
	),	
	array(
    'id'       => 'H5_font_size',
    'type'     => 'select',
    'title'    => 'اندازه فونت H5 ',
    'subtitle' => 'از بین فونت های موجود اندازه فونت H5 های سایت خود انتخاب کنید!',
    'options'  => array(
      '10px' => '10px',
      '11px' => '11px',
      '12px' => '12px',
      '13px' => '13px',
      '14px' => '14px',
      '15px' => '15px',
      '16px' => '16px',
      '17px' => '17px',
      '18px' => '18px',
      '19px' => '19px',
      '20px' => '20px',
      '21px' => '21px',
      '22px' => '22px',
      '23px' => '23px',
      '24px' => '24px',
      '25px' => '25px',
      '26px' => '26px',
	  '27px' => '27px',
	  '28px' => '28px',
	  '29px' => '29px',
	  '30px' => '30px',
	  '31px' => '31px',
	  '32px' => '32px',
	  '33px' => '33px',
	  '34px' => '34px',
	  '35px' => '35px',
	  '36px' => '36px',
	  '37px' => '37px',
	  '38px' => '38px',
	  '39px' => '39px',
	  '40px' => '40px',
	  '41px' => '41px',
	  '42px' => '42px',
	  '43px' => '43px',
	  '44px' => '44px',
	  '45px' => '45px',
	  '46px' => '46px',
	  '47px' => '47px',
	  '48px' => '48px',
	  '49px' => '49px',
	  '50px' => '50px',	  
    ),    
    'default'  => '13px'
	),	
	array(
    'id'       => 'H4_font_size',
    'type'     => 'select',
    'title'    => 'اندازه فونت H4 ',
    'subtitle' => 'از بین فونت های موجود اندازه فونت H4 های سایت خود انتخاب کنید!',
    'options'  => array(
      '10px' => '10px',
      '11px' => '11px',
      '12px' => '12px',
      '13px' => '13px',
      '14px' => '14px',
      '15px' => '15px',
      '16px' => '16px',
      '17px' => '17px',
      '18px' => '18px',
      '19px' => '19px',
      '20px' => '20px',
      '21px' => '21px',
      '22px' => '22px',
      '23px' => '23px',
      '24px' => '24px',
      '25px' => '25px',
      '26px' => '26px',
	  '27px' => '27px',
	  '28px' => '28px',
	  '29px' => '29px',
	  '30px' => '30px',
	  '31px' => '31px',
	  '32px' => '32px',
	  '33px' => '33px',
	  '34px' => '34px',
	  '35px' => '35px',
	  '36px' => '36px',
	  '37px' => '37px',
	  '38px' => '38px',
	  '39px' => '39px',
	  '40px' => '40px',
	  '41px' => '41px',
	  '42px' => '42px',
	  '43px' => '43px',
	  '44px' => '44px',
	  '45px' => '45px',
	  '46px' => '46px',
	  '47px' => '47px',
	  '48px' => '48px',
	  '49px' => '49px',
	  '50px' => '50px',	  
    ),    
    'default'  => '14px'
	),	
	array(
    'id'       => 'H3_font_size',
    'type'     => 'select',
    'title'    => 'اندازه فونت H3 ',
    'subtitle' => 'از بین فونت های موجود اندازه فونت H3 های سایت خود انتخاب کنید!',
    'options'  => array(
      '10px' => '10px',
      '11px' => '11px',
      '12px' => '12px',
      '13px' => '13px',
      '14px' => '14px',
      '15px' => '15px',
      '16px' => '16px',
      '17px' => '17px',
      '18px' => '18px',
      '19px' => '19px',
      '20px' => '20px',
      '21px' => '21px',
      '22px' => '22px',
      '23px' => '23px',
      '24px' => '24px',
      '25px' => '25px',
      '26px' => '26px',
	  '27px' => '27px',
	  '28px' => '28px',
	  '29px' => '29px',
	  '30px' => '30px',
	  '31px' => '31px',
	  '32px' => '32px',
	  '33px' => '33px',
	  '34px' => '34px',
	  '35px' => '35px',
	  '36px' => '36px',
	  '37px' => '37px',
	  '38px' => '38px',
	  '39px' => '39px',
	  '40px' => '40px',
	  '41px' => '41px',
	  '42px' => '42px',
	  '43px' => '43px',
	  '44px' => '44px',
	  '45px' => '45px',
	  '46px' => '46px',
	  '47px' => '47px',
	  '48px' => '48px',
	  '49px' => '49px',
	  '50px' => '50px',	  
    ),    
    'default'  => '15px'
	),	
	array(
    'id'       => 'H2_font_size',
    'type'     => 'select',
    'title'    => 'اندازه فونت H2 ',
    'subtitle' => 'از بین فونت های موجود اندازه فونت H2 های سایت خود انتخاب کنید!',
    'options'  => array(
      '10px' => '10px',
      '11px' => '11px',
      '12px' => '12px',
      '13px' => '13px',
      '14px' => '14px',
      '15px' => '15px',
      '16px' => '16px',
      '17px' => '17px',
      '18px' => '18px',
      '19px' => '19px',
      '20px' => '20px',
      '21px' => '21px',
      '22px' => '22px',
      '23px' => '23px',
      '24px' => '24px',
      '25px' => '25px',
      '26px' => '26px',
	  '27px' => '27px',
	  '28px' => '28px',
	  '29px' => '29px',
	  '30px' => '30px',
	  '31px' => '31px',
	  '32px' => '32px',
	  '33px' => '33px',
	  '34px' => '34px',
	  '35px' => '35px',
	  '36px' => '36px',
	  '37px' => '37px',
	  '38px' => '38px',
	  '39px' => '39px',
	  '40px' => '40px',
	  '41px' => '41px',
	  '42px' => '42px',
	  '43px' => '43px',
	  '44px' => '44px',
	  '45px' => '45px',
	  '46px' => '46px',
	  '47px' => '47px',
	  '48px' => '48px',
	  '49px' => '49px',
	  '50px' => '50px',	  
    ),    
    'default'  => '17px'
	),							
	array(
    'id'       => 'H1_font_size',
    'type'     => 'select',
    'title'    => 'اندازه فونت H1 ',
    'subtitle' => 'از بین فونت های موجود اندازه فونت H1 های سایت خود انتخاب کنید!',
    'options'  => array(
      '10px' => '10px',
      '11px' => '11px',
      '12px' => '12px',
      '13px' => '13px',
      '14px' => '14px',
      '15px' => '15px',
      '16px' => '16px',
      '17px' => '17px',
      '18px' => '18px',
      '19px' => '19px',
      '20px' => '20px',
      '21px' => '21px',
      '22px' => '22px',
      '23px' => '23px',
      '24px' => '24px',
      '25px' => '25px',
      '26px' => '26px',
	  '27px' => '27px',
	  '28px' => '28px',
	  '29px' => '29px',
	  '30px' => '30px',
	  '31px' => '31px',
	  '32px' => '32px',
	  '33px' => '33px',
	  '34px' => '34px',
	  '35px' => '35px',
	  '36px' => '36px',
	  '37px' => '37px',
	  '38px' => '38px',
	  '39px' => '39px',
	  '40px' => '40px',
	  '41px' => '41px',
	  '42px' => '42px',
	  '43px' => '43px',
	  '44px' => '44px',
	  '45px' => '45px',
	  '46px' => '46px',
	  '47px' => '47px',
	  '48px' => '48px',
	  '49px' => '49px',
	  '50px' => '50px',	  
    ),    
    'default'  => '19px'
	),		
	 )  )  );
			 
		     
		// -> START Menu Setting
		Redux::setSection( $opt_name, array(
			'title'=> 'تنظیمات منو',
			'id'   => 'menu_setting',
			'customizer_width' => '400px',
			'icon' => 'el el-home' 
		) );
		Redux::setSection( $opt_name, array(
			'title'=> ' منوی اصلی ',
			'subsection'       => true,
			'id'   => 'menu_setting_main',
			'customizer_width' => '400px',
			'fields'  => array (	         
	array(
		'id'       => 'section_main_menu',
		'title'    => 'تنظیمات منوی اصلی',
		'type'     => 'section',
		'indent' => true,
	),                  		
		array(
			'id'          => 'menu_header_item_color',
			'title'       => 'رنگ آیتم های منوی سربرگ',
			'subtitle'        => 'این رنگ برای تمام آیتم های منوی اصلی اعمال می شود.',
			'type'        => 'color',
			'default'         => '#2a2a2a'
		),
		array(
			'id'          => 'menu_header_item_hover_color',
			'title'       => 'رنگ هاور آیتم های منوی سربرگ',
			'subtitle'        => 'این رنگ برای تمام آیتم های منوی اصلی اعمال می شود.',
			'type'        => 'color',
			'default'         => '#2b2b2b'
		),    
		array(
			'id'          => 'menu_header_dropdown_bg',
			'type'     => 'background',
			'background-repeat'      => false,
			'background-position'      => false,
			'background-size'      => false,
			'background-attachment'      => false,
			'background-image'      => false,
			'default'  => array(
			'background-color' => '#ffffff'),
			'title'       => 'رنگ پس زمینه زیرمنوی سربرگ',
			'subtitle'        => 'این رنگ برای زیرمنوهای آبشاری سربرگ اعمال می شود.',
		), 
		array(
			'id'          => 'menu_header_dropdown_border',
			'title'       => 'رنگ حاشیه زیرمنوی سربرگ',
			'subtitle'        => 'این رنگ برای تمامی زیرمنوهای سربرگ اعمال می شود.',
			'type'        => 'color',
			'default'         => '#e0e0e0'
		), 
		array(
			'id'          => 'menu_header_dropdown_item_color',
			'title'       => 'رنگ آیتم های زیرمنوی سربرگ',
			'subtitle'        => 'این رنگ برای تمامی زیرمنوهای سربرگ اعمال می شود.',
			'type'        => 'color',
			'default'         => '#6b6b6b'
		), 
		array(
			'id'          => 'menu_header_dropdown_item_hover_color',
			'title'       => 'رنگ هاور آیتم های زیرمنوی سربرگ',
			'subtitle'        => 'این رنگ برای تمامی زیرمنوهای سربرگ اعمال می شود.',
			'type'        => 'color',
			'default'         => '#6b6b6b'
		),   		
		),         
		) );
		Redux::setSection( $opt_name, array(
			'title'=> ' منوی فوتر',
			'subsection'       => true,
			'id'   => 'menu_setting_footer',
			'customizer_width' => '400px',			
		  'fields'  => array (
		array(
			'id'          => 'footer_menu_item_color',
			'title'       => 'رنگ آیتم های منوی فوتر',
			'subtitle'        => 'این رنگ برای تمای آیتم های منوی فوتر اعمال می شود.',
			'type'        => 'color',
			'default'         => '#37474f'
		),
		array(
			'id'          => 'footer_menu_item_hover_color',
			'title'       => 'رنگ هاور آیتم های منوی فوتر',
			'subtitle'        => 'این رنگ برای تمای آیتم های منوی فوتر اعمال می شود.',
			'type'        => 'color',
			'default'         => '#0ea960'
		),      
		),            
		) );
		 

		
	// -> START Social Setting
		Redux::setSection( $opt_name, array(
			'title'=> ' شبکه های اجتماعی ',
			'id'   => 'social_setting',
			'customizer_width' => '400px',
			'icon' => 'el el-share',
			'fields'  => array (
	array(
		'id'       => 'section_menu_social',
		'title'    => 'تنظیمات شبکه های اجتماعی  ',
		'type'     => 'section',
		'indent' => true,
	),              
	array(
			'id'          => 'footer_shareboxes',
			'title'       => 'نمایش باکس شبکه های اجتماعی در سربرگ',
			'subtitle'        => 'با غیرفعال سازی این بخش نمایش تمامی آیکون های شبکه های اجتماعی در سایت را غیرفعال میگردد.',
			'type'        => 'button_set',
			'default'         => 'block',
			'options'  => array(
			'block' => 'فعال',
			'none' => 'غیرفعال',
		),    
	),
	array(
		'id'          => 'footer_instagram_box',
		'title'       => 'فعال سازی نمایش آیکون اینستاگرام در سایت',
		'required' 	  => array( 'footer_shareboxes', '=', 'block' ),
		'subtitle'    => 'از این بخش می توانید آیکون اینستاگرام را نمایش یا پنهان کنید.',
		'type'        => 'button_set',
		'default'     => 'inline-block',
		'options'  	  => array(
			'inline-block' => 'فعال',
			'none' => 'غیرفعال',
		),    
	),
	array(
			'id'          => 'footer_instagram_icon',
			'required' => array( 'footer_shareboxes', '=', 'block' ),
			'required' => array( 'footer_instagram_box', '=', 'inline-block' ),        
			'title'       => 'تغییر آیکون اینستاگرام ',
			'subtitle'        => 'از این قسمت می توانید آیکون " اینستاگرام " را در سربرگ تغییر دهید.',
			'type'        => 'text',
			'default'         => 'fa fa-instagram '
	),
	array(
			'id'          => 'footer_instagram_link',
			'required' => array( 'footer_instagram_icon', '=', 'block' ),
			'required' => array( 'footer_instagram_box', '=', 'inline-block' ),        
			'title'       => 'لینک صفحه اینستاگرام',
			'subtitle'        => 'لینک صفحه اینستاگرام را انتخاب این آیکون در سربرگ سایت شما و در کنار منوی اصلی نمایش داده میشود.',
			'type'        => 'text',
			'validate' => 'url',
			'default'         => 'https://instagram.com/cafecommerce'
	),
	array(
			'id'          => 'footer_telegram_box',
			'required' 	  => array( 'footer_shareboxes', '=', 'block' ),
			'title'       => 'فعال سازی نمایش آیکون تلگرام در سایت',
			'subtitle'    => 'از این بخش می توانید آیکون تلگرام را نمایش یا پنهان کنید.',
			'type'        => 'button_set',
			'default'     => 'inline-block',
			'options'  	  => array(
			'inline-block' => 'فعال',
			'none' => 'غیرفعال',
		),    
	),
	array(
			'id'          => 'footer_telegram_icon',
			'required' => array( 'footer_shareboxes', '=', 'block' ),
			'required' => array( 'footer_telegram_box', '=', 'inline-block' ),   
			'title'       => 'تغییر آیکون تلگرام ',
			'subtitle'        => 'از این قسمت می توانید آیکون " تلگرام " را در سربرگ تغییر دهید.',
			'type'        => 'text',
			'default'         => 'fa fa-telegram '
	),
	array(
			'id'          => 'footer_telegram_link',
			'required' => array( 'footer_shareboxes', '=', 'block' ),
			'required' => array( 'footer_telegram_box', '=', 'inline-block' ),    
			'title'       => 'لینک صفحه تلگرام',
			'subtitle'        => 'لینک صفحه تلگرام را انتخاب این آیکون در سربرگ سایت شما و در کنار منوی اصلی نمایش داده میشود.',
			'type'        => 'text',
			'validate' => 'url',	
			'default'         => 'https://telegram.me/cafecommerce'	
	),
	)
		) );        
		   
	// -> START 404 Setting
	Redux::setSection( $opt_name, array(
			'title'=> 'تنظیمات صفحه 404',
			'id'   => '404_setting',
			'customizer_width' => '400px',
			'icon' => 'el el-remove',
			'fields'     => array(
	array(
		'id'       => 'section_menu_404',
		'title'    => 'تنظیمات صفحه 404 ',
		'type'     => 'section',
		'indent' => true,
	),             
	array(
			'id'          => '404_image',
			'title'       => 'اعمال تصویر برای پس زمینه صفحه 404',
			'subtitle'        => 'تصویر برای پس زمینه صفحه 404 را انتخاب کنید.',
			'type'     => 'media',
			'url'      => true,
			'compiler' => 'true',   
			'default' => array( 'url'=>''.get_template_directory_uri().'/assets/img/icon-dissatisfied.svg'),
	),
	array(
			'id'          => '404_image_width',
			'title'       => 'طول تصویر صفحه 404',
			'subtitle'        => 'طول تصویر صفحه 404 را وارد کنید.',
			'type'        => 'text',
			'default'         => '80px'
	),
		array(
			'id'          => '404_image_height',
			'title'       => 'عرض تصویر صفحه 404',
			'subtitle'        => 'عرض تصویر صفحه 404 را وارد کنید.',
			'type'        => 'text',
			'default'         => '80px'
	),
	array(
			'id'          => '404_title',
			'title'       => 'عنوان صفحه 404',
			'subtitle'        => 'عنوان صفحه 404 را وارد کنید.',
			'type'        => 'text',
			'default'         => 'متأسفانه برنامه مورد نظر شما یافت نشد.'
	),
	array(
			'id'          => '404_btn_link',
			'title'       => 'لینک دکمه بازگشت به صفحه اصلی',
			'subtitle'        => 'لینک دکمه بازگشت به صفحه اصلی صفحه 404 را وارد کنید..',
			'type'        => 'text',
			'validate' => 'url',
			'default'  => ''.get_site_url().'/'
	),
	array(
			'id'          => '404_btn_title',
			'title'       => 'عنوان دکمه بازگشت به صفحه اصلی',
			'subtitle'        => 'عنوان دکمه بازگشت به صفحه اصلی صفحه 404 را وارد کنید..',
			'type'        => 'text',
			'default'         => 'بازگشت به صفحه اصلی'
				),               
		)        
		) );         


	// -> START Custom Code Setting
	Redux::setSection( $opt_name, array(
			'title'=> 'کدهای سفارشی',
			'id'   => 'custom_code_setting',
			'customizer_width' => '400px',
			'icon' => 'el el-css',
			'fields'     => array(
	array(
		'id'       => 'section_menu_code',
		'title'    => 'کدهای سفارشی',
		'type'     => 'section',
		'indent' => true,
	),         

				array(
					'id'       => 'custom_css',
					'type'     => 'ace_editor',
					'title'    => 'کدهای سفارشی css',
					'subtitle' => 'کدهای CSS خود را در این قسمت بنویسید',
					'mode'     => 'css',
					'theme'    => 'monokai',
				),
				array(
					'id'       => 'custom_js',
					'type'     => 'ace_editor',
					'title'    => 'کدهای سفارشی js',
					'subtitle' => 'کدهای جاوا اسکریپت خود را در این قسمت بنویسید.',
					'mode'     => 'javascript',
					'theme'    => 'monokai',
				),            
			)
			
		) );
		
	   
		/*
		 * <--- END SECTIONS
		 */


		/*
		 *
		 * YOU MUST PREFIX THE FUNCTIONS BELOW AND ACTION FUNCTION CALLS OR ANY OTHER CONFIG MAY OVERRIDE YOUR CODE.
		 *
		 */

		/*
		*
		* --> Action hook examples
		*
		*/

		// If Redux is running as a plugin, this will remove the demo notice and links
		//add_action( 'redux/loaded', 'remove_demo' );

		// Function to test the compiler hook and demo CSS output.
		// Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
		//add_filter('redux/options/' . $opt_name . '/compiler', 'compiler_action', 10, 3);

		// Change the arguments after they've been declared, but before the panel is created
		//add_filter('redux/options/' . $opt_name . '/args', 'change_arguments' );

		// Change the default value of a field after it's been set, but before it's been useds
		//add_filter('redux/options/' . $opt_name . '/defaults', 'change_defaults' );

		// Dynamically add a section. Can be also used to modify sections/fields
		//add_filter('redux/options/' . $opt_name . '/sections', 'dynamic_section');

		/**
		 * This is a test function that will let you see when the compiler hook occurs.
		 * It only runs if a field    set with compiler=>true is changed.
		 * */
		if ( ! function_exists( 'compiler_action' ) ) {
			function compiler_action( $options, $css, $changed_values ) {
	echo '<h1>The compiler hook has run!</h1>';
	echo "<pre>";
	print_r( $changed_values ); // Values that have changed since the last save
	echo "</pre>";
	//print_r($options); //Option values
	//print_r($css); // Compiler button_setor CSS values  compiler => array( CSS button_setORS )
			}
		}

		/**
		 * Custom function for the callback validation referenced above
		 * */
		if ( ! function_exists( 'redux_validate_callback_function' ) ) {
			function redux_validate_callback_function( $field, $value, $existing_value ) {
	$error   = false;
	$warning = false;

	//do your validation
	if ( $value == 1 ) {
		$error = true;
		$value = $existing_value;
	} elseif ( $value == 2 ) {
		$warning = true;
		$value   = $existing_value;
	}

	$return['value'] = $value;

	if ( $error == true ) {
		$field['msg']    = 'your custom error message';
		$return['error'] = $field;
	}

	if ( $warning == true ) {
		$field['msg']      = 'your custom warning message';
		$return['warning'] = $field;
	}

	return $return;
			}
		}

		/**
		 * Custom function for the callback referenced above
		 */
		if ( ! function_exists( 'redux_my_custom_field' ) ) {
			function redux_my_custom_field( $field, $value ) {
	print_r( $field );
	echo '<br/>';
	print_r( $value );
			}
		}

		/**
		 * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
		 * Simply include this function in the child themes functions.php file.
		 * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
		 * so you must use get_template_directory_uri() if you want to use any of the built in icons
		 * */
		if ( ! function_exists( 'dynamic_section' ) ) {
			function dynamic_section( $sections ) {
	//$sections = array();
	$sections[] = array(
		'title'  => __( 'Section via hook', 'redux-framework-demo' ),
		'subtitle'   => __( '<p class="subtitleription">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo' ),
		'icon'   => 'el el-paper-clip',
		// Leave this as a blank section, no options just some intro text set above.
		'fields' => array()
	);

	return $sections;
			}
		}

		/**
		 * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
		 * */
		if ( ! function_exists( 'change_arguments' ) ) {
			function change_arguments( $args ) {
	//$args['dev_mode'] = false;

	return $args;
			}
		}

		/**
		 * Filter hook for filtering the default value of any given field. Very useful in development mode.
		 * */
		if ( ! function_exists( 'change_defaults' ) ) {
			function change_defaults( $defaults ) {
	$defaults['str_replace'] = 'Testing filter hook!';

	return $defaults;
			}
		}

		/**
		 * Removes the demo link and the notice of integrated demo from the redux-framework plugin
		 */
		if ( ! function_exists( 'remove_demo' ) ) {
			function remove_demo() {
	// Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
	if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
		remove_filter( 'plugin_row_meta', array(
			ReduxFrameworkPlugin::instance(),
			'plugin_metalinks'
		), null, 2 );

		// Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
		remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
	}
			}
		}

