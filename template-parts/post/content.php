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
		<?php the_content(); ?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
