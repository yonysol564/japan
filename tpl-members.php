<?php /* Template Name: Members */ get_header(); 
	$members_form = get_field('members_form');
	$members_form_title = get_field('members_form_title');
	$members_content = get_field('members_content');
?>
    <main class="main_container space_holder">
		<section class="members_sec">
		    <div class="row page_header_title">
		        <div class="large-12 columns">
		            <h1 class="page_title"><span></span> <?php echo get_the_title(); ?></h1>
		        </div>
		    </div>
			<div class="row">
		        <div class="large-8 column">
					<div class="row">
						<div class="large-12 column">
							<div class="contact_form">
				      			<?php the_content(); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="large-5 column members_content">
							<?php echo $members_content; ?>
						</div>	
						<div class="large-7 column members_img">
							<?php the_post_thumbnail(); ?>
						</div>
					</div>
		        </div>
		        <div class="large-4 columns">
		        	<div class="members_form">
		        		<div>
		        			<h3><?php echo $members_form_title; ?></h3>
		        		</div>
						<?php echo do_shortcode($members_form); ?>
		        	</div>
		        </div>
		    </div>
		</section>
	</main>
<?php get_footer(); ?>
