<?php global $header_left_menu,$order_button_title; ?>

<div class="header_right_section desktop_div">
    <a href="#branches_popup" class="inline-popup-link"><?php echo $order_button_title; ?></a>
</div>

<?php if($header_left_menu): ?>
<div class="header_left_section desktop_div">
    <ul>
        <?php foreach($header_left_menu as $nav_item):
            $link               = $nav_item['link'];
            $desktop_icon       = $nav_item['icon']['url'];
            $desktop_icon_hover = $nav_item['icon_desktop_hover']['url'];
            $title              = $nav_item['title'];
        ?>
            <li>
                <a href="<?php echo $link; ?>">
                    <img src="<?php echo $desktop_icon; ?>" data-hover="<?php echo $desktop_icon_hover; ?>" data-unhover="<?php echo $desktop_icon; ?>" />
                    <?php echo $title; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
