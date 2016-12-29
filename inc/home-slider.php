<?php $slider_repeater = get_field('slider_repeater'); ?>

<?php if( $slider_repeater ) : ?>
    <section class="section" id="homepage_slider">
        <div class="homepage_slider_inner">
            <?php foreach($slider_repeater as $slide): ?>
                <div class="home_slide">
                    <picture>
                        <source srcset="<?php echo $slide['image']['sizes']['home_slider_mobile']; ?>" media="(max-width: 768px)">
                            <source srcset="<?php echo $slide['image']['sizes']['home_slider_mobile_small']; ?>" media="(max-width: 480px)">
                        <img srcset="<?php echo $slide['image']['sizes']['home_slider']; ?>">
                    </picture>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
