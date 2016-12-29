<?php /* Template Name: About */ get_header(); ?>
    <main class="main_container space_holder">
		<section class="about_sec">

            <div class="row page_header_title">
		        <div class="large-12 columns">
		            <h1 class="page_title"><span></span> <?php echo get_the_title(); ?></h1>
		        </div>
		    </div>

			<div class="row">
		        <div class="large-6 columns">
		      		<?php while( have_posts() ) : the_post(); ?>
                        <?php the_content(); ?>
                    <?php endwhile; ?>
		        </div>
		        <div class="large-6 columns">
					<div class="aboutbg_img">
						<?php the_post_thumbnail(); ?>
					</div>
		        </div>
		    </div>
		</section>
	</main>
<?php get_footer(); ?>
