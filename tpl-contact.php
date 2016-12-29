<?php /* Template Name: Contact */ get_header();
	$locations  = get_field('contact_form');
?>
    <main class="main_container space_holder">
		<section class="contact_sec">
		    <div class="row page_header_title">
		        <div class="large-12 columns">
		            <h1 class="page_title"><span></span> <?php echo get_the_title(); ?></h1>
		        </div>
		    </div>
			<div class="row">
		        <div class="large-2 columns con_col_2">
					<?php the_post_thumbnail(); ?>
		        </div>
		        <div class="large-4 columns con_col_4">
					<?php if( have_posts() ): ?>
						<div class="contact_form">
							<?php while( have_posts() ) : the_post(); ?>
			      				<?php the_content(); ?>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
		        </div>
		        <div class="large-6 columns con_col_map">
					<?php if($locations): ?>
						<script>
							var locations = [ "<?php echo $locations['address']; ?>", <?php echo $locations['lat']; ?> , <?php echo $locations['lng']; ?> ];
						</script>
						<div class="map_div" id="contact_map"></div>
					<?php endif; ?>
		        </div>
		    </div>
		</section>
	</main>

<?php get_footer(); ?>
