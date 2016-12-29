<?php global $header_left_menu,$order_button_title; ?>

<div class="mobile_header_bottom_wrapper mobile_div">
    <div class="mobile_header_left_section mobile_div">
        <a href="#branches_popup" class="inline-popup-link"><?php echo $order_button_title; ?></a>
    </div>

    <?php if($header_left_menu): ?>
    <div class="mobile_header_right_section mobile_div">
        <ul>
            <?php foreach($header_left_menu as $nav_item): ?>
                <li>
                    <a href="<?php echo $nav_item['link']; ?>">
                        <img src="<?php echo $nav_item['icon_mobile']['url']; ?>" />
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>
