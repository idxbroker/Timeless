<?php
/* Adds Customizer options for Timeless
 */

class TIMELESS_Customizer extends EQUITY_Customizer_Base {

	

	/**
	 * Register theme specific customization options
	 */
	public function register( $wp_customize ) {

		$this->colors( $wp_customize );
		$this->homepage( $wp_customize );
		$this->misc( $wp_customize );
		//add_action( 'customize_controls_enqueue_scripts', array( $this, 'timeless_customize_enqueue' ) );
	}

	public function timeless_customize_enqueue() {
		wp_enqueue_script( 'timeless-customize', get_stylesheet_directory_uri() . '/lib/js/admin.js', array( 'jquery', 'customize-controls' ), false, true );
	}
	
	//* Colors
	private function colors( $wp_customize ) {
		$wp_customize->add_section(
			'colors',
			array(
				'title'    => __( 'Custom Colors', 'timeless'),
				'priority' => 200,
			)
		);

		//* Setting key and default value array
		$settings = array(
			'primary_color'       => '',
			'secondary_color'     => '',
			'accent_color'        => '',
		);

		foreach ( $settings as $setting => $default ) {

			$wp_customize->add_setting(
				$setting,
				array(
					'default' => $default,
					// 'sanitize_callback' => 'sanitize_hex_color',
					'type'    => 'theme_mod'
				)
			);
		}

		//* Primary Color
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'primary_color',
				array(
					'label'       => __( 'Primary Color', 'timeless' ),
					'description' => __( 'Used for links, buttons, headings.', 'timeless' ),
					'section'     => 'colors',
					'settings'    => 'primary_color',
					'priority'    => 100
				)
			)
		);

		//* Primary Hover Color
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'secondary_color',
				array(
					'label'       => __( 'Secondary Color', 'timeless' ),
					'description' => __( 'Should be slightly darker than the primary color. Used for header, footer, and sticky search widget.', 'timeless' ),
					'section'     => 'colors',
					'settings'    => 'secondary_color',
					'priority'    => 100
				)
			)
		);

		//* Accent Color
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'accent_color',
				array(
					'label'       => __( 'Accent Color', 'timeless' ),
					'description' => __( 'Accent color for highlights and hover states.', 'timeless' ),
					'section'     => 'colors',
					'settings'    => 'accent_color',
					'priority'    => 100
				)
			)
		);

	}

	//* Homepage
	private function homepage( $wp_customize ) {
		$wp_customize->add_section(
			'homepage',
			array(
				'title'    => __( 'Home Page', 'timeless'),
				'priority' => 201,
			)
		);

		//* Setting key and default value array
		$settings = array(
			'homepage_lead_heading'      => '',
			'background_opacity'         => 0.80,
			'homepage_background_option' => 'video',
			'background_video_id'        => '',
			'homepage_background_video'  => get_stylesheet_directory_uri() . '/images/video-default.mp4',
			'default_background_image'   => get_stylesheet_directory_uri() . '/images/bkg-default.jpg',
			'enable_search_animation'    => true,
			'enable_sticky_search'       => true

		);

		foreach ( $settings as $setting => $default ) {

			$wp_customize->add_setting(
				$setting,
				array(
					'default' => $default,
					'type'    => 'theme_mod'
				)
			);
		}

		//* Homepage Lead Heading
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'homepage_lead_heading',
				array(
					'label'       => __( 'Home Page Heading', 'timeless' ),
					'description' => __( 'This is the lead heading on the home page above the search widget. Use <code>&lt;strong&gt;</code> tags for accent color.', 'timeless' ),
					'section'     => 'homepage',
					'settings'    => 'homepage_lead_heading',
					'type'        => 'textarea',
					'priority'    => 100
				)
			)
		);

		//* Background opacity
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'background_opacity',
				array(
					'label'       => __( 'Widget area background opacity', 'timeless' ),
					'description' => __( 'Adjust the opacity of the home page widget area backgrounds.', 'timeless' ),
					'section'     => 'colors',
					'settings'    => 'background_opacity',
					'type'        => 'range',
					'input_attrs' => array(
							'min'   => 0,
							'max'   => 1,
							'step'  => 0.1,
						),
					'priority'    => 100
				)
			)
		);

		//* Video or Image background on homepage
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'homepage_background_option',
				array(
					'label'       => __( 'Home Page Background', 'timeless' ),
					'description' => __( 'Choose either a video or image for the home page background.', 'timeless' ),
					'section'     => 'homepage',
					'settings'    => 'homepage_background_option',
					'type'        => 'radio',
					'choices'     => array(
						'video-youtube' => 'YouTube Video (recommended)',
						'video'         => 'Upload Video',
						'image'         => 'Image',
					),
					'priority'    => 100,
				)
			)
		);

		//* Homepage Lead Heading
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'background_video_id',
				array(
					'label'       => __( 'YouTube video ID', 'timeless' ),
					'description' => __( '<p>Enter a video ID to use for a YouTube video background.</p> <span style="font-style: normal;">Example: https://youtube.com/watch?v=<strong>nB6qHeTkCE8</strong> - nB6qHeTkCE8 is the video ID</span>', 'timeless' ),
					'section'     => 'homepage',
					'settings'    => 'background_video_id',
					'type'        => 'text',
					'priority'    => 100,
					'active_callback' => array( $this, 'is_youtube_selected' ),
				)
			)
		);

		//* Background video
		$wp_customize->add_control(
			new WP_Customize_Upload_Control(
				$wp_customize,
				'homepage_background_video',
				array(
					'label'       => __( 'Upload Video', 'timeless' ),
					'description' => __( 'Upload a video in MP4 format to use for a background video on the home page. Video should be optimized with as small a file size as possible.', 'timeless' ),
					'section'     => 'homepage',
					'settings'    => 'homepage_background_video',
					'extensions'  => array( 'mp4' ),
					'priority'    => 100,
					'active_callback' => array( $this, 'is_video_upload_selected' ),
				)
			)
		);

		//* Default background image
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'default_background_image',
				array(
					'label'       => __( 'Default Background Image', 'timeless' ),
					'description' => __( 'Used as a fallback image before the video loads. <p style="font-style: normal;">Upload an image in <strong>JPG</strong> format at least 1600x1200 pixels.</p>', 'timeless' ),
					'section'     => 'homepage',
					'settings'    => 'default_background_image',
					'extensions'  => array( 'jpg' ),
					'priority'    => 100
				)
			)
		);

		//* Enable animation on homepage search
		$wp_customize->add_control(
			'enable_search_animation',
			array(
				'label'    => __( 'Enable animation on Home Page Search widget?', 'timeless' ),
				'section'  => 'homepage',
				'type'     => 'checkbox',
				'settings' => 'enable_search_animation',
				'priority' => 300
			)
		);

		//* Enable sticky search on interior pages
		$wp_customize->add_control(
			'enable_sticky_search',
			array(
				'label'    => __( 'Use Search from home page on interior pages?', 'timeless' ),
				'section'  => 'homepage',
				'type'     => 'checkbox',
				'settings' => 'enable_sticky_search',
				'priority' => 300
			)
		);

	}

	//* Misc
	private function misc( $wp_customize ) {

		//* Setting key and default value array
		$settings = array(
			'enable_sticky_header'     => true,
			'disable_site_description' => false,
		);

		foreach ( $settings as $setting => $default ) {

			$wp_customize->add_setting(
				$setting,
				array(
					'default' => $default,
					'type'    => 'theme_mod'
				)
			);
		}

		//* Enable header description
		$wp_customize->add_control(
			'disable_site_description',
			array(
				'label'    => __( 'Show Site Description?', 'timeless' ),
				'section'  => 'title_tagline',
				'type'     => 'checkbox',
				'settings' => 'disable_site_description',
				'priority' => 300
			)
		);

		//* Enable sticky header checkbox
		$wp_customize->add_control(
			'enable_sticky_header',
			array(
				'label'    => __( 'Enable Sticky Header?', 'timeless' ),
				'section'  => 'title_tagline',
				'type'     => 'checkbox',
				'settings' => 'enable_sticky_header',
				'priority' => 300
			)
		);
	}

	//* Render CSS
	public function render() {
		?>
		<!-- begin Child Customizer CSS -->
		<style type="text/css">

			<?php
			//* Site description
			if ( get_theme_mod('disable_site_description') ) 
				echo 'header.site-header .site-description {display: block;}';

			//* Primary color - link color
			self::generate_css( '
				h1, h2, h3, h4, h5, h6,
				.site-inner a,
				.home-lead .leaflet-container a,
				.ae-iconbox i[class*="fa-"],
				.ae-iconbox a i[class*="fa-"],
				.showcase-property span.price,
				.equity-idx-carousel span.price,
				.widget .listing-wrap .listing-thumb-meta span,
				.home-middle h4.widget-title,
				h1.entry-title,
				.sidebar-primary h4.widget-title,
				.IDX-wrapper-standard a
				', 'color', 'primary_color' );

			//* Icon boxes
			self::generate_css( '
				.ae-iconbox.type-2:hover i[class*="fa-"],
				.ae-iconbox.type-2:hover a i[class*="fa-"],
				.ae-iconbox.type-3:hover i[class*="fa-"],
				.ae-iconbox.type-3:hover a i[class*="fa-"]
				', 'color', 'primary_color', '', ' !important' );

			//* Primary color - backgrounds
			self::generate_css('
				.button.secondary:hover,
				button.secondary:focus,
				.button:not(.secondary),
				button:not(.secondary),
				input[type="button"],
				input[type="submit"],
				.equity-idx-carousel .owl-controls .owl-prev,
				.equity-idx-carousel .owl-controls .owl-next,
				.ae-iconbox.type-2 i,
				.ae-iconbox.type-3 i,
				ul.pagination li.current a,
				ul.pagination li.current button,
				.bg-alt,
				.after-entry-widget-area,
				.IDX-wrapper-standard .IDX-btn,
				#IDX-main.IDX-wrapper-standard .IDX-btn-default,
				.IDX-wrapper-standard .IDX-btn-primary,
				.IDX-wrapper-standard .IDX-panel-primary>.IDX-panel-heading,
				.IDX-wrapper-standard .IDX-navbar-default,
				#IDX-mapHeader-Search,
				.IDX-wrapper-standard .IDX-nav-pills>li.IDX-active>a,
				.IDX-wrapper-standard .IDX-nav-pills>li.IDX-active>a:focus,
				.IDX-wrapper-standard .IDX-nav-pills>li.IDX-active>a:hover
				',
				'background-color', 'primary_color', '', '!important'
			);

			//* Primary color - border color
			self::generate_css('
				.button,
				button,
				input[type="button"],
				input[type="submit"],
				footer.site-footer .row,
				.home-search-bar.sticky,
				.home-search-bar.sticky, body.sticky-search .home-search-bar,
				body.sticky-search .home-search-bar,
				.IDX-wrapper-standard .IDX-btn,
				#IDX-main .IDX-btn-primary,
				.IDX-wrapper-standard .IDX-panel-primary>.IDX-panel-heading,
				.IDX-wrapper-standard .IDX-navbar-default,
				.IDX-wrapper-standard .IDX-panel-primary
				',
				'border-color', 'primary_color'
			);

			//* Secondary color - hover color
			self::generate_css('
				.ae-iconbox h4 a:hover,
				.IDX-wrapper-standard a:focus, .IDX-wrapper-standard a:hover,
				.IDX-wrapper-standard .IDX-detailsHotAction a:hover
				',
				'color', 'secondary_color'
			);

			//* Secondary color - background color
			self::generate_css('
				.footer-widgets,
				footer.site-footer,
				.IDX-wrapper-standard .IDX-btn:hover,
				#IDX-main.IDX-wrapper-standard .IDX-btn-default:hover,
				.IDX-wrapper-standard .IDX-btn-primary:hover,
				.IDX-wrapper-standard .IDX-btn:focus,
				#IDX-main.IDX-wrapper-standard .IDX-btn-default:focus,
				.IDX-wrapper-standard .IDX-btn-primary:focus,
				.IDX-wrapper-standard .IDX-navbar-default .IDX-navbar-nav>.IDX-active>a,
				.IDX-wrapper-standard .IDX-navbar-default .IDX-navbar-nav>.IDX-active>a:focus,
				.IDX-wrapper-standard .IDX-navbar-default .IDX-navbar-nav>.IDX-active>a:hover,
				.IDX-wrapper-standard .IDX-navbar-default .IDX-navbar-nav>li>a:focus,
				.IDX-wrapper-standard .IDX-navbar-default .IDX-navbar-nav>li>a:hover
				',
				'background-color', 'secondary_color', '', ' !important'
			);

			//* Accent color - font and hovers
			self::generate_css('
				.site-inner .bg-alt a:hover,
				.site-inner .bg-alt a:focus,
				.bg-alt .equity-idx-carousel span.price:hover,
				.home-lead h3 strong,
				.home-bottom h4.widget-title,
				.home-cta-menu ul.menu li a:hover,
				.footer-widgets .widget-title,
				.footer-widgets .widget.featured-page h2 a,
				.footer-widgets .widget.featured-post h2 a,
				.footer-widgets .widget a:hover,
				footer.site-footer a:hover,
				.site-inner a:hover,
				.site-inner a:focus,
				a.off-canvas-toggle:hover,
				a.off-canvas-toggle:focus,
				ul.off-canvas-menu li a:hover,
				ul.off-canvas-menu li a:focus,
				.home-search-bar ul.menu li a:hover,
				.home-search-bar ul.menu li a:focus,
				.equity-idx-carousel span.price:hover
				',
				'color', 'accent_color', '', ' !important'
			);
			
			//* Accent color - backgrounds
			self::generate_css('
				.home-search-bar .idx-omnibar-form button,
				.button.secondary,
				button.secondary,
				.button:not(.secondary):hover,
				button:not(.secondary):hover,
				input[type="button"]:hover,
				input[type="submit"]:hover,
				.bg-alt .button:hover,
				.bg-alt input[type="button"]:hover,
				.bg-alt input[type="submit"]:hover,
				.button:not(.secondary):focus,
				button:not(.secondary):focus,
				input[type="button"]:focus,
				input[type="submit"]:focus,
				.equity-idx-carousel .owl-controls .owl-prev:hover,
				.equity-idx-carousel .owl-controls .owl-next:hover,
				ul.pagination li.current a:hover,
				ul.pagination li.current a:focus,
				ul.pagination li.current button:hover,
				ul.pagination li.current button:focus
				',
				'background-color', 'accent_color', '', ' !important'
			);	

			//* Primary color - transparent backgrounds

			if(get_theme_mod('primary_color')) {
				$primary_rgba = self::hex2rgba(get_theme_mod('primary_color'), get_theme_mod('background_opacity', '0.80'));
				echo '.home-lead h3,
					.timeless-custom .content-sidebar-wrap.row .bg-alt,
					.home .content-sidebar-wrap.row .bg-alt {
						background-color: ' . $primary_rgba . ' ;
					}';
			}

			if(get_theme_mod('secondary_color')) {
				$secondary_rgba = self::hex2rgba(get_theme_mod('secondary_color'), get_theme_mod('background_opacity', '0.80'));
				echo '.sticky-header.sticky header.site-header,
					.home-search-bar,
					.right-off-canvas-menu {
						background-color: ' . $secondary_rgba . '
					}';
			}

			if(get_theme_mod('background_opacity')) {
				echo '.home-middle {background: rgba(255,255,255,' . get_theme_mod('background_opacity', '0.80') . ');}';
			}

			if(get_theme_mod('secondary_color')) {
				$secondary_color = get_theme_mod('secondary_color');
				$secondary_rgba_solid = self::hex2rgba(get_theme_mod('secondary_color'), 1);
				$secondary_rgba_transparent = self::hex2rgba(get_theme_mod('secondary_color'), '0.00');
				echo 'header.site-header {
						background: ' . $secondary_color . ';
						background: -moz-linear-gradient(top, ' . $secondary_rgba_solid . ' 0%, ' . $secondary_rgba_transparent . ' 100%); /* FF3.6-15 */
						background: -webkit-linear-gradient(top, ' . $secondary_rgba_solid . ' 0%,' . $secondary_rgba_transparent . ' 100%); /* Chrome10-25,Safari5.1-6 */
						background: linear-gradient(to bottom, ' . $secondary_rgba_solid . ' 0%,' . $secondary_rgba_transparent . ' 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
						filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'' . $secondary_color . '\', endColorstr=\'#00' . $secondary_color . '\',GradientType=0 ); /* IE6-9 */
					}';
			}

			?>

		</style>
		<!-- end Child Customizer CSS -->
		<?php
	}

	public function is_youtube_selected() {
		//$setting = $control->manager->get_setting( 'homepage_background_option' );
		$setting = get_theme_mod( 'homepage_background_option' );
		if ( $setting === 'video-youtube' ) {
			return true;
		}
		return false;
	}

	public function is_video_upload_selected() {
		//$setting = $control->manager->get_setting( 'homepage_background_option' );
		$setting = get_theme_mod( 'homepage_background_option' );
		if ( $setting === 'video' ) {
			return true;
		}
		return false;
	}
}

add_action( 'init', 'timeless_customizer_init' );
/**
 * Instantiate TIMELESS_Customizer
 * 
 * @since 1.0
 */
function timeless_customizer_init() {
	new TIMELESS_Customizer;
}