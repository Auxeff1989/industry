<?php
if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

function panalo_setup() {

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

	add_image_size( 'twentyseventeen-featured-image', 2000, 1200, true );
	add_image_size( 'twentyseventeen-thumbnail-avatar', 100, 100, true );

	register_nav_menus( array(
		'top'    => __( 'Top Menu', 'panalo' ),
		'main' => __( 'Main Menu', 'panalo' ),
		'pages' => __( 'Page Menu', 'panalo' ),
		'social' => __( 'Social Links Menu', 'panalo' ),
	) );

	add_theme_support(
		'post-formats',
		array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
			'audio',
		)
	);
	
	add_theme_support(
		'custom-logo',
		array(
			'width'      => 250,
			'height'     => 250,
			'flex-width' => true,
		)
	);

	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );

}
add_action( 'after_setup_theme', 'panalo_setup' );

function panalo_widgets_init() {

	register_sidebar(
		array(
			'name'          => __( 'Footer 1', 'panalo' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your footer.', 'panalo' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 2', 'panalo' ),
			'id'            => 'sidebar-2',
			'description'   => __( 'Add widgets here to appear in your footer.', 'panalo' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'panalo_widgets_init' );

function panalo_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}

	$link = sprintf(
		'<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Post title. */
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'panalo' ), get_the_title( get_the_ID() ) )
	);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'panalo_excerpt_more' );

function panalo_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'panalo_javascript_detection', 0 );

function panalo_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'panalo_pingback_header' );

function panalo_scripts() {

	wp_enqueue_style( 'paanalo-style', get_stylesheet_uri(), array(), '' );

	if ( is_customize_preview() ) {
		wp_enqueue_style( 'panalo-ie9', get_theme_file_uri( '/assets/css/ie9.css' ), array( 'panalo-style' ), '' );
		wp_style_add_data( 'panalo-ie9', 'conditional', 'IE 9' );
	}

	wp_enqueue_style( 'panalo-ie8', get_theme_file_uri( '/assets/css/ie8.css' ), array( 'panalo-style' ), '' );
	wp_style_add_data( 'panalo-ie8', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'html5', get_theme_file_uri( '/assets/js/html5.js' ), array(), '' );
	wp_script_add_data( 'html5', 'conditional', 'lt IE 9' );

	if ( has_nav_menu( 'top' ) ) {
		wp_enqueue_script( 'panalo-navigation', get_theme_file_uri( '/assets/js/navigation.js' ), array( 'jquery' ), '20161203', true );
		$twentyseventeen_l10n['expand']   = __( 'Expand child menu', 'panalo' );
		$twentyseventeen_l10n['collapse'] = __( 'Collapse child menu', 'panalo' );
	}

	//wp_enqueue_script( 'jquery-scrollto', get_theme_file_uri( '/assets/js/jquery.scrollTo.js' ), array( 'jquery' ), '2.1.2', true );

	//if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
	//	wp_enqueue_script( 'comment-reply' );
	//}
}
add_action( 'wp_enqueue_scripts', 'panalo_scripts' );

function panalo_block_editor_styles() {
	//wp_enqueue_style( 'panalo-block-editor-style', get_theme_file_uri( '/assets/css/editor-blocks.css' ), array(), '' );
}
add_action( 'enqueue_block_editor_assets', 'panalo_block_editor_styles' );

function panalo_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( is_archive() || is_search() || is_home() ) {
		$attr['sizes'] = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
	} else {
		$attr['sizes'] = '100vw';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'panalo_post_thumbnail_sizes_attr', 10, 3 );

function panalo_front_page_template( $template ) {
	return is_home() ? '' : $template;
}
add_filter( 'frontpage_template', 'panalo_front_page_template' );

function panalo_unique_id( $prefix = '' ) {
	static $id_counter = 0;
	if ( function_exists( 'wp_unique_id' ) ) {
		return wp_unique_id( $prefix );
	}
	return $prefix . (string) ++$id_counter;
}

require get_parent_theme_file_path( '/inc/icon-functions.php' );

function awesome_script_enqueue() {
	
	wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), '3.3.7', 'all');
	wp_enqueue_style('custom', get_template_directory_uri() . '/assets/css/custom.css', array(), '', 'all');
	
	wp_enqueue_script('jquery');
	wp_enqueue_script('bootstrapjs', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array(), '3.3.7', true);
	wp_enqueue_script('customjs', get_template_directory_uri() . '/assets/js/plugin.js', array(), '', true);
	
}
add_action( 'wp_enqueue_scripts', 'awesome_script_enqueue');

function custom_add_class_to_images($class){
    $class .= ' img-responsive';
    return $class;
}
add_filter('get_image_tag_class','custom_add_class_to_images');

/***** Shortcodes *****/
function restaurants_custom_post_func($atts) {

	$args  = array(
		'post_type'      	=> 'restaurant',
		'post_status'    	=> 'publish',
		'posts_per_page' 	=> -1,
		'orderby'        	=> 'name',
		'order'          	=> 'DESC',
	);
	
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) :
		$count_post_arr = count( $query->posts );
		
		$html = '<div id="restaurant-container" class="custom-post-restaurant">';
		
		foreach ( $query->posts as $article ) :
			
			$html .= '<article id="post-'.$article->ID.'" class="post-'.$article->ID.' post type-post status-publish">';

			$html .= '<a href="'.get_permalink( $article->ID ).'" class="entry-featured-image-url">'.get_the_post_thumbnail(
                        $article->ID,
                        'large',
                        array( 
							'class' => 'img-fluid',
                            'srcset' => wp_get_attachment_image_url( get_post_thumbnail_id($article->ID), 'thumbnail' ) . ' 480w, ' .
                                wp_get_attachment_image_url( get_post_thumbnail_id($article->ID), 'medium' ) . ' 640w, ' .
                                wp_get_attachment_image_url( get_post_thumbnail_id($article->ID), 'large') . ' 960w'
                        )
                    ).'</a>';
			$html .= '<h2 class="entry-title"><a href="'.get_permalink( $article->ID ).'">'.$article->post_title.'</a></h2>';	

			$html .= '</article>';
			
		endforeach;
	
		if( $count_post_arr % 2 != 0 ) {
        	$html .= '<article class="post-flex-if-length-1"></article>';
		} 
		
		$html .= '</div>';
		
	endif;
	
	wp_reset_postdata();	
	
	return $html;

}
add_shortcode( 'restaurants_custom_post', 'restaurants_custom_post_func' );

function get_orders_by_status( $status ){
	
	$args  = array(
		'post_type'      	=> 'orders',
		'post_status'    	=> 'publish',
		'posts_per_page' 	=> -1,
		'orderby'        	=> 'ID',
		'order'          	=> 'DESC',
		'meta_query' => array(
			array(
				'key'     => 'status',
				'value'   => ucfirst( $status ),
				'compare' => '=',
			),
		),
	);
	$query = new WP_Query( $args );
	$orderid_arr = array();
	if ( $query->have_posts() ) :
		foreach ( $query->posts as $order ) :
			$orderid_arr[]  = $order->ID;
		endforeach;
	endif;
	
	return $orderid_arr;
}

function get_orders_by_search( $keyword ){
	
	$restaurant_arr = get_restaurant_by_keyword( $keyword );
	
	$args  = array(
		'post_type'      	=> 'orders',
		'post_status'    	=> 'publish',
		'posts_per_page' 	=> -1,
		'orderby'        	=> 'ID',
		'order'          	=> 'DESC',
		'meta_query' => array(
			array(
				'key'     => 'restaurant',
				'value'   => $restaurant_arr,
				'compare' => 'IN',
			),
		),
	);
	$query = new WP_Query( $args );
	$orderid_arr = array();
	if ( $query->have_posts() ) :
		foreach ( $query->posts as $order ) :
			$orderid_arr[] = $order->ID;
		endforeach;
	endif;
	
	return $orderid_arr;
}

function get_orders_by_date( $from, $to ){
	
	$from_date = explode("/", $from);
	$to_date = explode("/", $to);
	
	$args  = array(
		'post_type'      	=> 'orders',
		'post_status'    	=> 'publish',
		'posts_per_page' 	=> -1,
		'orderby'        	=> 'ID',
		'order'          	=> 'DESC',
		'date_query' => array(
			array(
				'year'  	=> $from_date[2],
				'month' 	=> $from_date[0],
				'day'   	=> $from_date[1],
				'compare'   => '>=',
			),
			array(
				'year'  	=> $to_date[2],
				'month' 	=> $to_date[0],
				'day'   	=> $to_date[1],
				'compare'   => '<=',
			),
		),
	);
	$query = new WP_Query( $args );
	$orderid_arr = array();
	if ( $query->have_posts() ) :
		foreach ( $query->posts as $order ) :
			$orderid_arr[] = $order->ID;
		endforeach;
	endif;
	
	return $orderid_arr;
}

function get_restaurant_by_keyword( $keyword ){
	$args  = array(
		'post_type'      	=> 'restaurant',
		'post_status'    	=> 'publish',
		'posts_per_page' 	=> -1,
		's' 				=> $keyword,
		'orderby'        	=> 'ID',
		'order'          	=> 'DESC',
	);
	$query = new WP_Query( $args );
	$restaurantid_arr = array();
	if ( $query->have_posts() ) :
		foreach ( $query->posts as $restaurant ) :
			$restaurantid_arr[] = $restaurant->ID;
		endforeach;
	endif;
	
	return $restaurantid_arr;
}