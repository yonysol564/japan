<?php /* Template Name: Gallery */ get_header();
	$gallery_rep    = get_field('gallery_rep');
	$gallery_length = count($gallery_rep);
	$posts_per_page = 8;
?>
    <main class="main_container space_holder">
		<section class="gallery_sec">
		    <div class="row page_header_title">
		        <div class="large-12 columns">
		            <h1 class="page_title"><span></span><?php echo get_the_title(); ?></h1>
		        </div>
		    </div>
			<div class="row gallery">
				<?php
				if ($gallery_rep){
					$paged      = isset($_GET["gallery_paged"]) ? sanitize_text_field($_GET["gallery_paged"]) : 0;
					$start_from = $paged < 8 ? $paged :$paged * $posts_per_page;
					$counter    = 0;
					while($counter < $posts_per_page && isset($gallery_rep[$start_from])) {
						$gallery = $gallery_rep[$start_from];
						$img 	 = $gallery['gallery_img'];
						$title 	 = $gallery['gallery_title'];
					?>
				        <div class="large-3 medium-6 column gallery_col end">
				        	<a class="gallery-item" href="<?php echo $img['url']; ?>" title="<?php echo $title; ?>">
								<div class="img_wrap" style="background-image: url(<?php echo $img['url']; ?>)">
									<div class="title_div">
										<div class="inner_title"><?php echo $title; ?></div>
									</div>
								</div>
				        	</a>
				        </div>
					<?php
						$start_from++;
						$counter++;
					}
				}
				?>
		    </div>

    		<div class="row">
    			<div class="large-12 column">
    				<div class="gallery_pagination">
    					<div class="inner_pagination">

								<?php
									$total       = $gallery_length / $posts_per_page;
									$total_pages = round($total);
								?>

		    					<?php if( ( $paged ) != $total_pages ):?>
								<ul class="next_page_list">
									<li class="next_page">
										<a href="<?php echo esc_url( add_query_arg( 'gallery_paged', ( $paged + 1) , get_permalink() ) ) ?>" title="">
											< הבא
										</a>
									</li>
								</ul>
								<?php endif; ?>

								<ul class="pagination_page_list">
				    				<?php
				    					for ($i=1; $i <= $total_pages ; $i++) {
				    						$active ='';
				    						if($paged == $i){
				    							$active = 'active_page';
				    						}
				    						?>
											<li class="<?php echo $active; ?>">
						    					<a href="<?php echo esc_url( add_query_arg( 'gallery_paged', $i, get_permalink() ) ); ?>" title="">
													<?php echo $i; ?>
												</a>
											</li>
				    						<?php
				    					}
				    				?>
								</ul>

			    				<?php if( ( $paged - 1 ) != 0 ):?>
								<ul class="prev_page_list">
									<li class="prev_page">
										<a href="<?php echo esc_url( add_query_arg( 'gallery_paged', ( $paged - 1) , get_permalink() ) ) ?>" title="">
											קודם >
										</a>
									</li>
								</ul>
								<?php endif; ?>
							</ul>
						</div>
    				</div>
    			</div>
			</div>

		</section>
	</main>
<?php get_footer(); ?>
