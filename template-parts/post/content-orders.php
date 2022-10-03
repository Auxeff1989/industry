<?php
	global $post;
	$restaurant_id = get_field('restaurant');
	$restaurant = get_post( $restaurant_id );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-thumbnail text-center">
		<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid' ) ); ?>
	</div><!-- .post-thumbnail -->
	
	<header class="entry-header text-center">
		<?php
		if ( is_single() ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
		} elseif ( is_front_page() && is_home() ) {
			the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
		} else {
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}
		?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<div class="restaurant-thumbnail col text-center">
			<?php
			echo get_the_post_thumbnail(
				$restaurant_id,
				'medium',
				array( 
					'class' => 'img-fluid',
					'srcset' => wp_get_attachment_image_url( get_post_thumbnail_id($restaurant_id), 'thumbnail' ) . ' 480w, ' .
						wp_get_attachment_image_url( get_post_thumbnail_id($restaurant_id), 'medium' ) . ' 640w, ' .
						wp_get_attachment_image_url( get_post_thumbnail_id($restaurant_id), 'large') . ' 960w'
				)
			);
			?>
			<span><?php echo $restaurant->post_title; ?></span></a>
		</div>
		<div class="order-info col">
			<p><label>STATUS:</label> <?php echo get_field('status'); ?></p>
			<p><label>TOTAL:</label> $<?php echo get_field('total'); ?></p>
			<p><label>FEES:</label> $<?php echo get_field('fees'); ?></p>
			<p><label>TRANSFER:</label> $<?php echo get_field('transfer'); ?></p>
			<p><label>ORDERS:</label> <?php echo get_field('orders'); ?></p>
		</div>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
