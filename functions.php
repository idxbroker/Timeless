<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Timeless', 'timeless' ) );
define( 'CHILD_THEME_URL', 'http://www.agentevolution.com/shop/timeless/' );
define( 'CHILD_THEME_VERSION', '1.5.9' );

//* Prevent update check with .org theme repo
add_filter('http_request_args', 'equity_dont_update_theme', 5, 2);

//* Set Localization (do not remove)
load_child_theme_textdomain( 'timeless', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'timeless' ) );

//* Add Theme Support
add_theme_support( 'equity-off-canvas-menu' );
add_theme_support( 'equity-after-entry-widget-area' );
add_theme_support( 'equity-menus', array(
	'off-canvas'   => __( 'Main Menu', 'timeless' ),
	'quick-search' => __( 'Search Bar Menu', 'timeless' ),
) );
add_theme_support( 'equity-structural-wraps', array( 'header', 'footer-widgets', 'footer', ) );

//* Remove header right widget area
remove_action( 'after_setup_theme', 'equity_register_header_right_widget_area' );

//* Add rectangular size image for featured posts/pages
add_image_size( 'featured-post', '700', '370', true );

//* Create additional color style options
add_theme_support( 'equity-style-selector', array(
	'timeless-green'   => __( 'Green', 'timeless' ),
	'timeless-red'     => __( 'Red', 'timeless' ),
	'timeless-aqua'    => __( 'Aqua', 'timeless' ),
	'timeless-black'   => __( 'Black', 'timeless' ),
	'timeless-custom'  => __( 'Use Customizer', 'timeless' ),
) );

//* Load fonts
add_filter( 'equity_google_fonts', 'timeless_fonts' );
function timeless_fonts( $equity_google_fonts ) {
	$equity_google_fonts = 'Raleway:400,700|Arimo:400,700';
	return $equity_google_fonts;
}

// Add class to body for easy theme identification.
add_filter( 'body_class', 'add_theme_body_class' );
function add_theme_body_class( $classes ) {
	$classes[] = 'home-theme--timeless';
	return $classes;
}

//* Enqueue scripts
add_action( 'wp_enqueue_scripts', 'timeless_enqueue_scripts' );
function timeless_enqueue_scripts() {
	wp_enqueue_script( 'jquery-backstretch', get_stylesheet_directory_uri() . '/lib/js/jquery.backstretch.min.js', array( 'jquery' ), false, true );

	//* Enable sticky header if checked in customizer
	if ( true === get_theme_mod( 'enable_sticky_header', true ) ) {
		wp_enqueue_script( 'sticky-header', get_stylesheet_directory_uri() . '/lib/js/sticky-header.js', array( 'jquery' ), '1.0', true );
	}

	//* Enable sticky search if checked in customizer
	if ( is_home() && get_theme_mod( 'enable_search_animation' ) === true ) {
		wp_enqueue_script( 'sticky-search', get_stylesheet_directory_uri() . '/lib/js/sticky-search.js', array( 'jquery' ), '1.0', true );
	}

	// Load video script based on customizer selection
	if ( get_theme_mod( 'homepage_background_option' ) === 'video-youtube' && get_theme_mod( 'background_video_id' ) !== '' && ! wp_is_mobile() ) {
		wp_enqueue_script( 'jquery-youtubebackground', get_stylesheet_directory_uri() . '/lib/js/jquery.youtubebackground.js', array( 'jquery' ), false, true );
		$video_script = '
		jQuery(".site-container").YTPlayer({
			videoId: "' . get_theme_mod( 'background_video_id', 'nB6qHeTkCE8' ) . '",
			fitToBackground: true
		});
		';
		wp_add_inline_script( 'jquery-youtubebackground', $video_script );
		$video_style = '
		.site-container {
			position: relative;
			background: transparent;
		}

		.ytplayer-container {
			position: fixed;
			top: 0;
			z-index: -1;
		}';
		wp_add_inline_style( 'timeless', $video_style );
	} elseif ( get_theme_mod( 'homepage_background_option' ) === 'video' && get_theme_mod( 'homepage_background_video' ) !== '' ) {
		wp_enqueue_script( 'jquery-vide', get_stylesheet_directory_uri() . '/lib/js/jquery.vide.min.js', array( 'jquery' ), false, true );
		add_filter( 'equity_attr_body', 'timeless_video_attributes_body' );
	}
}

//* Output backstretch call with custom or default image to wp_footer
add_action( 'wp_footer', 'timeless_backstretch_js', 9999 );
function timeless_backstretch_js() {

	//* Return if is a single post and no background is checked in post options
	//* or if it is the homepage and background video is enabled in customizer
	if ( ! is_home() && equity_get_custom_field( '_equity_disable_single_post_background' ) == true )
		return;

	$background_url = equity_get_custom_field( '_equity_single_post_background' );

	// use default if no background image set
	if ( ! $background_url || is_home() && 'image' === get_theme_mod( 'homepage_background_option', 'image' ) ) {
		$background_url = get_theme_mod( 'default_background_image', get_stylesheet_directory_uri() . '/images/bkg-default.jpg' );
	}
	?>
	
	<script>jQuery.backstretch("<?php echo $background_url; ?>");</script>
	<?php
}

//* Add sticky header wrap markup
add_action( 'equity_before_header', 'timeless_sticky_header_open', 10 );
add_action( 'equity_after_header', 'timeless_sticky_header_close' );
function timeless_sticky_header_open() {
	echo '<div class="sticky-header">';
}
function timeless_sticky_header_close() {
	echo '</div><!-- end .sticky-header -->';
}

//* Add filter to add custom video attributes to body
function timeless_video_attributes_body() {
	$attributes['class']        = join( ' ', get_body_class() );
	$attributes['itemscope']    = 'itemscope';
	$attributes['itemtype']     = 'http://schema.org/WebPage';
	$attributes['data-vide-bg'] =  'mp4: ' . get_theme_mod( 'homepage_background_video', get_stylesheet_directory_uri() . '/images/video-default.mp4' ) . ', poster: ' . get_theme_mod( 'default_background_image', get_stylesheet_directory_uri() . '/images/bkg-default.jpg' );
	$attributes['data-vide-options'] = 'className: bg-video, posterType: jpg';

	return $attributes;
}

//* Filter off canvas nav toggle icon
add_filter( 'equity_off_canvas_toggle_text', 'timeless_off_canvas_toggle_text' );
function timeless_off_canvas_toggle_text() {
	$toggle_text = '<span>Menu </span><i class="fas fa-bars"></i>';
	return $toggle_text;
}

//* Filter off canvas display side
add_filter('equity_off_canvas_side', 'timeless_off_canvas_side');
function timeless_off_canvas_side() {
	$side = 'right';
	return $side;
}

//* Filter carousel widget prev/next links
add_filter( 'listing_scroller_prev_link', 'child_carousel_prev_link' );
add_filter( 'idx_listing_carousel_prev_link', 'child_carousel_prev_link' );
add_filter( 'equity_page_carousel_prev_link', 'child_carousel_prev_link' );
function child_carousel_prev_link( $listing_scroller_prev_link_text ) {
	$listing_scroller_prev_link_text = __( '<i class=\"fas fa-caret-left\"></i><span>Prev</span>', 'timeless' );
	return $listing_scroller_prev_link_text;
}
add_filter( 'listing_scroller_next_link', 'child_carousel_next_link' );
add_filter( 'idx_listing_carousel_next_link', 'child_carousel_next_link' );
add_filter( 'equity_page_carousel_next_link', 'child_carousel_next_link' );
function child_carousel_next_link( $listing_scroller_next_link_text ) {
	$listing_scroller_next_link_text = __( '<i class=\"fas fa-caret-right\"></i><span>Next</span>', 'timeless' );
	return $listing_scroller_next_link_text;
}


//* Set default footer widgets to 3
if ( get_theme_mod( 'footer_widgets' ) === '' ) {
	set_theme_mod( 'footer_widgets', 3 );
}

//* Register widget areas
equity_register_widget_area(
	array(
		'id'          => 'home-search',
		'name'        => __( 'Home Search', 'timeless' ),
		'description' => __( 'This is the Search section of the Home page at the top. Recommended to use a Custom Menu and the IDX Omnibar Search widget.', 'timeless' ),
	)
);
equity_register_widget_area(
	array(
		'id'           => 'home-cta-menu',
		'name'         => __( 'Home CTA Menu', 'timeless' ),
		'description'  => __( 'This is the icon menu bar on the homepage. Recommended to use the Equity Custom Menu widget.', 'timeless' ),
	)
);
equity_register_widget_area(
	array(
		'id'          => 'home-middle',
		'name'        => __( 'Home Middle', 'timeless' ),
		'description' => __( 'This is the Middle section of the Home page. Recommended to use an Equity Property widgets.', 'timeless' ),
	)
);
equity_register_widget_area(
	array(
		'id'          => 'home-bottom',
		'name'        => __( 'Home Bottom', 'timeless' ),
		'description' => __( 'This is the Bottom section of the Home page. Recommended to use a Text widget with a testimonial.', 'timeless' ),
	)
);

//* Home page - define home page widget areas for welcome screen display check
add_filter('equity_theme_widget_areas', 'timeless_home_widget_areas');
function timeless_home_widget_areas($active_widget_areas) {
	$active_widget_areas = array( 'home-search' );
	return $active_widget_areas;
}

//* Home page - markup and default widgets
function equity_child_home() {
	?>

	<div class="row home-lead">
		<h3><?php echo get_theme_mod('homepage_lead_heading'); ?></h3>
	</div><!-- end .row -->

	<div class="home-search-bar">
		<div class="row">
			<?php if ( has_nav_menu('quick-search') ) {?>
			<div class="columns small-12 large-6">
				<?php equity_nav_menu($args = array('theme_location' => 'quick-search'), false) ;?>
			</div><!-- end .columns .small-12 .large-6 -->
		
			<div class="columns small-12 large-6">

			<?php } else { ?>

			<div class="columns small-12">
			<?php }
			equity_widget_area( 'home-search' ); ?>
			</div><!-- end .columns .small-12 -->
		</div><!-- end .row -->
	</div><!-- end .home-search -->

	<div class="home-cta-menu bg-alt">
		<div class="row">
			<div class="columns small-12">
				<?php equity_widget_area( 'home-cta-menu' ); ?>
			</div><!-- end .columns .small-12 -->
		</div><!-- end .row -->
	</div><!-- end .home-cta-menu -->

	<div class="home-middle">
		<div class="row">
			<div class="columns small-12">
			<?php equity_widget_area( 'home-middle' ); ?>
			</div><!-- end .columns .small-12 -->
		</div><!-- end .row -->
	</div><!-- end .home-middle -->

	<div class="home-bottom bg-alt">
		<div class="row">
			<div class="columns small-12">
			<?php equity_widget_area( 'home-bottom' ); ?>
			</div><!-- end .columns .small-12 -->
		</div><!-- end .row -->
	</div><!-- end .home-bottom -->

<?php
}

//* Add sticky search if it's enabled in customizer
add_action('equity_after_footer', 'timeless_sticky_search', 1);
function timeless_sticky_search() {
	if ( get_theme_mod('enable_sticky_search') == true && !is_home() ) {
	?>
		<div class="home-search-bar">
			<div class="row">
				<?php if ( has_nav_menu('quick-search') ) {?>
				<div class="columns small-12 large-6">
					<?php equity_nav_menu($args = array('theme_location' => 'quick-search'), false) ;?>
				</div><!-- end .columns .small-12 .large-6 -->
			
				<div class="columns small-12 large-6">

				<?php } else { ?>

				<div class="columns small-12">
				<?php }
				equity_widget_area( 'home-search' ); ?>
				</div><!-- end .columns .small-12 -->
			</div><!-- end .row -->
		</div><!-- end .home-search -->
<?php
	}
}
//* Add body class for sticky search on interior pages
add_filter( 'body_class', 'timeless_sticky_search_class' );
function timeless_sticky_search_class( $classes ) {
	if ( get_theme_mod('enable_sticky_search') == true && !is_home() ) 
		$classes[] = 'sticky-search';
	return $classes;
}

//* Includes

# Theme Customizatons
require_once get_stylesheet_directory() . '/lib/customizer.php';

# Custom metaboxes
require_once get_stylesheet_directory() . '/lib/metaboxes.php';