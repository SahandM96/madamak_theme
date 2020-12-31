<?php
/**
 * Developer : MahdiY
 * Web Site  : MahdiY.IR
 * E-Mail    : M@hdiY.IR
 */

class PWS_Admin {

	public function __construct() {

		$this->includes();

		add_action( 'admin_menu', [ $this, 'admin_menu' ], 20 );

		add_filter( 'parent_file', [ $this, 'parent_file' ] );
	}

	public function admin_menu() {

		$capability = apply_filters( 'pws_menu_capability', 'manage_woocommerce' );

		add_menu_page( 'حمل و نقل', 'حمل و نقل', $capability, 'pws-tools', [
			'PWS_Settings_Tools',
			'output'
		], PWS_URL . 'assets/images/pws.png', '55.8' );

		$submenus = [
			10 => [
				'title'      => 'ابزارها',
				'capability' => $capability,
				'slug'       => 'pws-tools',
				'callback'   => [ 'PWS_Settings_Tools', 'output' ],
			],
			20 => [
				'title'      => 'تاپین',
				'capability' => $capability,
				'slug'       => 'pws-tapin',
				'callback'   => [ 'PWS_Settings_Tapin', 'output' ],
			],
			30 => [
				'title'      => 'پیامک',
				'capability' => $capability,
				'slug'       => 'pws-sms',
				'callback'   => [ 'PWS_Settings_SMS', 'output' ],
			],
		];

		if ( ! PWS_Tapin::is_enable() ) {
			$submenus[40] = [
				'title'      => 'شهرها',
				'capability' => $capability,
				'slug'       => 'edit-tags.php?taxonomy=state_city',
				'callback'   => '',
			];
		}

		$submenus = apply_filters( 'pws_submenu', $submenus );

		foreach ( $submenus as $submenu ) {
			add_submenu_page( 'pws-tools', $submenu['title'], $submenu['title'], $submenu['capability'], $submenu['slug'], $submenu['callback'] );

			add_action( 'admin_init', function() use ( $submenu ) {
				if ( class_exists( $submenu['callback'][0] ) ) {
					call_user_func( [ $submenu['callback'][0], 'instance' ] );
				}
			}, 5 );
		}

	}

	public function parent_file( $parent_file ) {

		if ( ! isset( $_GET['taxonomy'] ) || $_GET['taxonomy'] != 'state_city' ) {
			return $parent_file;
		}

		return 'pws-tools';
	}

	public function includes() {
		include 'class-settings.php';
		include 'class-sms.php';
		include 'class-tapin.php';
		include 'class-tools.php';
	}

}

new PWS_Admin();