<?php
get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<?php
						while ( have_posts() ) :
							the_post();

							get_template_part( 'template-parts/post/content', get_post_format() );

						endwhile;
						?>
					</div>
				</div>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php
get_footer();
