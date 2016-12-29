<?php /* Template Name: Franchise */ get_header();
	$franchise_form = get_field('franchise_form');
	$franchise_form_title = get_field('franchise_form_title');
?>
    <main class="main_container space_holder">
		<section class="franchise_sec">

		    <div class="row page_header_title">
		        <div class="large-12 columns">
		            <h1 class="page_title"><span></span> <?php echo get_the_title(); ?></h1>
		        </div>
		    </div>

			<div class="row">
		        <div class="large-8 columns">
					<div class="contact_form">
		      			<?php while( have_posts() ): the_post(); ?>
							<?php the_content(); ?>
						<?php endwhile; ?>
					</div>

					<div class="img_div">
					 	<?php $youtube_id = get_field('youtube_id'); ?>
				  		<iframe src="https://www.youtube.com/embed/<?php echo $youtube_id; ?>" width="660" height="373" frameborder="0" allowfullscreen>
	                    </iframe>
				 	</div>
		        </div>
		        <div class="large-4 columns">
		        	<div class="franchise_form">
		        		<div>
		        			<h3><?php echo $franchise_form_title; ?></h3>
		        		</div>
						<?php echo do_shortcode($franchise_form); ?>
		        	</div>
		        </div>
		    </div>
		</section>
	</main>
<?php get_footer(); ?>
