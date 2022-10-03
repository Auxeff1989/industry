<?php
get_header();
$icon_date = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
	<path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
</svg>';
$icon_download = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-download" viewBox="0 0 16 16">
	<path d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"/>
		<path d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z"/>
</svg>';
$icon_arrow = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
	<path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
</svg>';

?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main site-pages" role="main">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<?php while ( have_posts() ) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<header class="entry-header">
									<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
								</header><!-- .entry-header -->
								<div class="entry-content">
								<?php
								$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
								if( isset( $_GET['status'] ) && $_GET['status'] != "all" ) {
									$args  = array(
										'post_type'      	=> 'invoice',
										'post_status'    	=> 'publish',
										'posts_per_page' 	=> 10,
										'paged' 			=> $paged,
										'orderby'        	=> 'ID',
										'order'          	=> 'DESC',
										'meta_query' => array(
											array(
												'key'     => 'order',
												'value'   => get_orders_by_status( $_GET['status'] ),
												'compare' => 'IN',
											),
										),
									);
									
								} elseif( isset( $_POST['search'] ) && !empty( $_POST['search'] ) ) {
									$args  = array(
										'post_type'      	=> 'invoice',
										'post_status'    	=> 'publish',
										'posts_per_page' 	=> 10,
										'paged' 			=> $paged,
										'orderby'        	=> 'ID',
										'order'          	=> 'DESC',
										'meta_query' => array(
											array(
												'key'     => 'order',
												'value'   => get_orders_by_search( $_POST['search'] ),
												'compare' => 'IN',
											),
										),
									);
									
								} elseif( isset( $_POST['filter_date'] ) ) {
									$args  = array(
										'post_type'      	=> 'invoice',
										'post_status'    	=> 'publish',
										'posts_per_page' 	=> 10,
										'paged' 			=> $paged,
										'orderby'        	=> 'ID',
										'order'          	=> 'DESC',
										'meta_query' => array(
											array(
												'key'     => 'order',
												'value'   => get_orders_by_date( $_POST['from_date'], $_POST['to_date'] ),
												'compare' => 'IN',
											),
										),
									);
									
								} elseif( isset( $_POST['update_status'] ) && !empty( $_POST['update_status'] ) ) {
									if( !empty( $_POST['mark'] ) ) {
										foreach( $_POST['mark'] as $selected ) {
											update_post_meta( $selected, 'status', 'Verified' );
										}
										
										$args  = array(
											'post_type'      	=> 'invoice',
											'post_status'    	=> 'publish',
											'posts_per_page' 	=> 10,
											'paged' 			=> $paged,
											'orderby'        	=> 'ID',
											'order'          	=> 'DESC',
										);
									}
									
								} else {
									$args  = array(
										'post_type'      	=> 'invoice',
										'post_status'    	=> 'publish',
										'posts_per_page' 	=> 10,
										'paged' 			=> $paged,
										'orderby'        	=> 'ID',
										'order'          	=> 'DESC',
									);
								}
								
								$query = new WP_Query( $args );
								if ( $query->have_posts() ) : ?>
									<form method="post" name="date_form" action="<?php echo esc_url( home_url( '/invoices/' ) ); ?>">
										<div id="orders-container" class="custom-post-orders">
											<div class="table-filters">
												<div class="filter-status">
													<a href="?status=all" <?php echo ( (isset($_GET['status']) && $_GET['status']=='all') || !isset($_GET['status']) ? 'class="active"' : ''); ?>>ALL</a>
													<a href="?status=ongoing" <?php echo (isset($_GET['status']) && $_GET['status']=="ongoing" ? 'class="active"' : ''); ?>>ONGOING</a>
													<a href="?status=verified" <?php echo (isset($_GET['status']) && $_GET['status']=="verified" ? 'class="active"' : ''); ?>>VERIFIED</a>
													<a href="?status=pending" <?php echo (isset($_GET['status']) && $_GET['status']=="pending" ? 'class="active"' : ''); ?>>PENDING</a>
												</div>
												<div class="filter-date">
													<div class="date-input">
														<span class="icon-date"><?php echo $icon_date; ?></span>
														<input type="text" name="from_date" class="form-control from-date" placeholder="<?php echo date('m/d/Y'); ?>" value="<?php echo $_POST['from_date']; ?>">
														<span class="icon-arrow"><?php echo $icon_arrow; ?></span>
														<input type="text" name="to_date" class="form-control to-date" placeholder="<?php echo date('m/d/Y'); ?>" value="<?php echo $_POST['to_date']; ?>">
														<input type="submit" value="Filter" name="filter_date">
													</div>
												</div>
												<div class="filter-search">
													<input type="search" name="search" class="form-control" placeholder="Search">
												</div>
												<div class="mark-btn">
													<input type="submit" value="Mark as paid" name="update_status">
												</div>
											</div>
											<table id="tableInvoice" class="table">
												<thead>
													<tr>
													  <th scope="col"><input type="checkbox" class="form-control" name="mark[]"></th>
													  <th scope="col">ID</th>
													  <th scope="col">Restaurant</th>
													  <th scope="col">Status</th>
													  <th scope="col">Start Date</th>
													  <th scope="col">End Date</th>
													  <th scope="col">Total</th>
													  <th scope="col">Fees</th>
													  <th scope="col">Transfer</th>
													  <th scope="col">Orders</th>
													  <th scope="col"></th>
													</tr>
												</thead>
												<tbody>
												<?php			
												foreach ( $query->posts as $invoice ) :
													$order_id = get_field( 'order', $invoice->ID );
													$restaurant_id = get_field( 'restaurant', $order_id );
													$order = get_post( $order_id );
													$restaurant = get_post( $restaurant_id );
													$status = get_field( 'status', $order_id );
												?>	
													<tr>
														<th scope="row"><input type="checkbox" class="form-control" name="mark[]" value="<?php echo $order_id; ?>" <?php echo ($status=="Verified" ? "disabled" : ""); ?>></th>
														<td><a href="<?php echo get_permalink( $order_id ); ?>">#<?php echo $invoice->post_title; ?></a></td>
														<td>
															<a href="<?php echo get_permalink( $restaurant_id ); ?>" class="entry-featured-image-url"><?php echo get_the_post_thumbnail(
																$restaurant_id,
																'thumbnail',
																array( 
																	'class' => 'img-fluid',
																	'srcset' => wp_get_attachment_image_url( get_post_thumbnail_id($restaurant_id), 'thumbnail' ) . ' 480w, ' .
																		wp_get_attachment_image_url( get_post_thumbnail_id($restaurant_id), 'medium' ) . ' 640w, ' .
																		wp_get_attachment_image_url( get_post_thumbnail_id($restaurant_id), 'large') . ' 960w'
																)
															); ?><span><?php echo $restaurant->post_title; ?></span></a>
														</td>
														<td><span class="status status-<?php echo strtolower( $status ); ?>"><?php echo get_field( 'status', $order_id ); ?></span></td>
														<td><?php echo date('m/d/Y', strtotime( $order->post_date )); ?></td>
														<td><?php echo date('m/d/Y', strtotime( $order->post_date )); ?></td>
														<td>$<?php echo get_field( 'total', $order_id ); ?></td>
														<td>$<?php echo get_field( 'fees', $order_id ); ?></td>
														<td>$<?php echo get_field( 'transfer', $order_id ); ?></td>
														<td><?php echo get_field( 'orders', $order_id ); ?></td>
														<td><?php echo $icon_download; ?></td>
													</tr>	
												<?php endforeach; ?>
												</tbody>
											</table>
											<div class="paginate-nav">
												<?php
													$big = 99999999;
													echo paginate_links( array(
														'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
														'format' => '?paged=%#%',
														'current' => max( 1, get_query_var('paged') ),
														'total' => $query->max_num_pages,
														'add_fragment' => '',
														'prev_text' => '&laquo;',
														'next_text' => '&raquo;',
													) );
												?>
											</div>
										</div>
									</form>
								<?php endif; ?>
									
								</div><!-- .entry-content -->
							</article><!-- #post-<?php the_ID(); ?> -->

						<?php endwhile; ?>
					</div>
				</div>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php
get_footer();
