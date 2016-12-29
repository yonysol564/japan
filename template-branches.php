<?php
    /* Template Name: Brachnes list */
    get_header();
    $bargs = array(
        'post_type'      => 'branch',
        'posts_per_page' => -1
    );
    $branch = new WP_Query($bargs);
?>

<main class="main_container space_holder">

    <section class="section" id="all_branches">

        <div class="row page_header_title">
	        <div class="large-12 columns">
	            <h1 class="page_title"><span></span> <?php echo get_the_title(); ?></h1>
	        </div>
	    </div>

        <?php if( $branch->have_posts() ) : ?>
            <div class="row large-up-4 medium-up-3 branches_row_wrapper">
                <?php while($branch->have_posts()):$branch->the_post(); ?>
                    <div class="column end">
                        <article class="branch_item">
                            <h3><?php the_title(); ?></h3>
                            <div class="branch_content"><?php the_content(); ?></div>
                            <div class="branch_permalink">
                                <a class="inline-popup-link" data-bid="<?php echo $post->ID; ?>" href="#">הזמן מהסניף ></a>
                            </div>
                        </article>
                    </div>
                <?php endwhile; wp_reset_query(); ?>
            </div>
        <?php endif; ?>

    </section>

</main>

<?php get_template_part("inc/popup","branches"); ?>

<?php get_footer(); ?>
