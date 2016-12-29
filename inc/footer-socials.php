<?php $footer_socials = get_field('footer_socials','options'); ?>

<?php if( $footer_socials ) : ?>
    <div class="footer_socials">
        <ul class="no-bullet">
            <?php foreach($footer_socials as $item): ?>
                <?php if( $item['icon'] && $item['link'] ): ?>
                    <?php
                        $link              = $item['link'];
                        $footer_icon       = $item['icon']['url'];
                        $footer_icon_hover = $item['icon_hover']['url'];
                        $target            = '_self';
                        if( $item['open_in_new_window'] ) {
                            $target = '_blank';
                        }
                    ?>
                    <li>
                        <a href="<?php echo $link; ?>" target="<?php echo $target; ?>">
                            <img src="<?php echo $footer_icon; ?>" data-hover="<?php echo $footer_icon_hover; ?>" data-unhover="<?php echo $footer_icon; ?>" />
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
