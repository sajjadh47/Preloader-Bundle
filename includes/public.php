<?php

if ( ! defined( 'ABSPATH' ) )
{
	exit( 'restricted access' );
}

/**
 * Class to handle plugin public functionality
 */
if ( ! class_exists( 'PRELOADER_BUNDLE_PUBLIC' ) )
{
	class PRELOADER_BUNDLE_PUBLIC
	{
		static public function run()
		{
			add_action( 'wp_head', array( 'PRELOADER_BUNDLE_PUBLIC', 'wp_head' ) );
			
			add_action( 'wp_footer', array( 'PRELOADER_BUNDLE_PUBLIC', 'wp_footer' ) );
		}

		static public function wp_head()
		{
			$enabled = PRELOADER_BUNDLE_ADMIN_SETTINGS::get_option( 'enable_preloader', 'preloader_bundle_basic_settings' );

			if ( $enabled == 'on' )
			{
				$enable_preloader_page = PRELOADER_BUNDLE_ADMIN_SETTINGS::get_option( 'enable_preloader_page', 'preloader_bundle_basic_settings' );
				
				$preloader_style = PRELOADER_BUNDLE_ADMIN_SETTINGS::get_option( 'preloader_style', 'preloader_bundle_basic_settings' );

				$gif = PRELOADER_BUNDLE_PLUGIN_URL . 'assets/gifs/' . $preloader_style;

				// valid tags
				$allowed_html = array(
			        'style' => array(
			            'type' => array(),
			        ),
				);

				$stylesheet = "<style type='text/css'> " . esc_html( 'body,html{overflow: hidden!important;}div#preloader-bundle{position:fixed;width:100%;height:100%;top:0;bottom:0;left:0;right:0;background:#ffffff;z-index:99999999999;background-image:url( ' . $gif . ' );background-repeat: no-repeat;background-size:auto;background-position:center;}' ) . " </style>";

				if ( $enable_preloader_page == '1' )
				{
					if ( ( is_front_page() && is_home() ) || ( is_front_page() ) || ( is_home() ) )
					{
						echo wp_kses( $stylesheet, $allowed_html );
					}
				}
				elseif( $enable_preloader_page == '0' )
				{
					echo wp_kses( $stylesheet, $allowed_html );
				}
			}
		}

		static public function wp_footer()
		{
			$enabled = PRELOADER_BUNDLE_ADMIN_SETTINGS::get_option( 'enable_preloader', 'preloader_bundle_basic_settings' );

			if ( $enabled == 'on' )
			{
				$enable_preloader_page = PRELOADER_BUNDLE_ADMIN_SETTINGS::get_option( 'enable_preloader_page', 'preloader_bundle_basic_settings' );
				
				$close_preloader = PRELOADER_BUNDLE_ADMIN_SETTINGS::get_option( 'close_preloader', 'preloader_bundle_basic_settings' );

				$script = '';

				if ( $close_preloader == '0' )
				{
					$script = '<script type="text/javascript">
						jQuery( document ).ready( function( $ )
						{
							$( "#preloader-bundle" ).remove();
						});
					</script>';
				}
				elseif( $close_preloader == '1' )
				{
					$seconds_to_close_the_preloader = PRELOADER_BUNDLE_ADMIN_SETTINGS::get_option( 'seconds_to_close_the_preloader', 'preloader_bundle_basic_settings', '10' );
					
					$script = '<script type="text/javascript">
						setTimeout( function()
						{
							document.getElementById( "preloader-bundle" ).remove();

						}, ' . intval( $seconds_to_close_the_preloader ) * 1000 . ' );
					</script>';
				}

				// valid tags
				$allowed_html = array(
					'div' => array(
			            'id' => array(),
			        ),
			        'script' => array(
			            'type' => array(),
			        ),
				);

				if ( $enable_preloader_page == '1' )
				{
					if ( ( is_front_page() && is_home() ) || ( is_front_page() ) || ( is_home() ) )
					{
						echo wp_kses( '<div id="preloader-bundle"></div>' . $script, $allowed_html );
					}
				}
				elseif( $enable_preloader_page == '0' )
				{					
					echo wp_kses( '<div id="preloader-bundle"></div>' . $script, $allowed_html );
				}
			}
		}
	}

	PRELOADER_BUNDLE_PUBLIC::run();
}
