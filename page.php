<?php get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main site-pages" role="main">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<?php
						while ( have_posts() ) :
							the_post();

							get_template_part( 'template-parts/page/content', 'page' );

						endwhile; // End the loop.
						?>
					</div>
				</div>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php
get_footer();
