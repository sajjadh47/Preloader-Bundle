<?php

if ( ! defined( 'ABSPATH' ) )
{
	exit( 'restricted access' );
}

/**
 * Admin Settings Page
 *
 * @author Sajjad Hossain Sagor
 */
class PRELOADER_BUNDLE_ADMIN_SETTINGS
{
	private $settings_api;

	private $timezones;

	function __construct()
	{	
		// add settings api wrapper
		require_once PRELOADER_BUNDLE_PLUGIN_PATH . 'includes/vendor/class.settings-api.php';
		
		$this->settings_api = new PRELOADER_BUNDLE_SETTINGS_API;

		add_action( 'admin_init', array( $this, 'admin_init') );
		
		add_action( 'admin_menu', array( $this, 'admin_menu') );
	}

	public function admin_init()
	{
		//set the settings
		$this->settings_api->set_sections( $this->get_settings_sections() );
		
		$this->settings_api->set_fields( $this->get_settings_fields() );

		//initialize settings
		$this->settings_api->admin_init();
	}

	public function admin_menu()
	{
		add_options_page( __( 'Preloader Bundle', 'preloader-bundle' ), __( 'Preloader Bundle', 'preloader-bundle' ), 'manage_options' , 'preloader-bundle.php', array( $this, 'render_settings_page' ) );
	}

	public function get_settings_sections()
	{
		$sections = array(
			array(
				'id'    => 'preloader_bundle_basic_settings',
				'title' => __( 'General Settings', 'preloader-bundle' )
			),
		);
		
		return $sections;
	}

	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */
	public function get_settings_fields()
	{
		$gifs = get_option( 'PRELOADER_BUNDLE_PLUGIN_GIFS_' . PRELOADER_BUNDLE_PLUGIN_VERSION, false );

		if ( ! $gifs )
		{
			$directory = PRELOADER_BUNDLE_PLUGIN_PATH . 'assets/gifs';

			if ( file_exists( $directory ) )
			{
				foreach ( glob( "$directory/*.gif" ) as $file )
				{
					$gifs[basename( $file )] = basename( $file );
				}
			}

			update_option( 'PRELOADER_BUNDLE_PLUGIN_GIFS_' . PRELOADER_BUNDLE_PLUGIN_VERSION, $gifs, false );
		}

		$settings_fields = array(
			'preloader_bundle_basic_settings' => array(
				array(
					'name'    => 'enable_preloader',
					'label'   => __( 'Enable Preloader', 'preloader-bundle' ),
					'type'    => 'checkbox',
					'desc'    => __( 'Checking this box will enable the Preloader.', 'preloader-bundle' )
				),
				array(
					'name'    => 'enable_preloader_page',
					'label'   => __( 'Enable Preloader For', 'preloader-bundle' ),
					'type'    => 'select',
					'options' => array(
						'0' => 'All Pages',
						'1' => 'Only For Home Page',
					),
					'desc'    => __( 'Select the preloader loading page.', 'preloader-bundle' ),
				),
				array(
					'name'    => 'close_preloader',
					'label'   => __( 'Close Preloader', 'preloader-bundle' ),
					'type'    => 'select',
					'options' => array(
						'0' => 'After Page Loaded Completely',
						'1' => 'After Specific Seconds Later',
					),
					'desc'    => __( 'Select the preloader closing time. If you select <b>After Specific Seconds Later</b> then please add seconds value below. Default 10s.', 'preloader-bundle' ),
				),
				array(
					'name'    => 'seconds_to_close_the_preloader',
					'label'   => __( "Specific Seconds", 'preloader-bundle' ),
					'type'    => 'number',
					'desc'    => __( 'How many seconds after preloader will be closed? Default 10s.', 'preloader-bundle' ),
					'default' => 10
				),
				array(
					'name'    => 'preloader_style',
					'label'   => __( 'Preloader Style', 'preloader-bundle' ),
					'type'    => 'radio_image',
					'options' => $gifs,
				),
			),
		);

		return $settings_fields;
	}

	/**
	 * Render settings fields
	 *
	 */
	public function render_settings_page()
	{    
		echo '<div class="wrap">';

			$this->settings_api->show_navigation();
		   
			$this->settings_api->show_forms();

		echo '</div>';
	}

	/**
	 * Returns option value
	 *
	 * @return string|array option value
	 */
	static public function get_option( $option, $section, $default = '' )
	{
		$options = get_option( $section );

		if ( isset( $options[$option] ) )
		{
			return $options[$option];
		}

		return $default;
	}
}

$PRELOADER_BUNDLE_ADMIN_SETTINGS = new PRELOADER_BUNDLE_ADMIN_SETTINGS();
