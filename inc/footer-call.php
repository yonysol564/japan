<?php
    $call_title        = get_field('call_title','options');
    $call_phone_number = get_field('call_phone_number','options');
?>

<?php if( $call_title && $call_phone_number ): ?>

<div class="footer_call">
    <div class="footer_call_inner">
        <div class="call_to_action">
            <?php echo $call_title; ?> <a href="tel:<?php echo $call_phone_number; ?>"><?php echo $call_phone_number; ?></a>
        </div>
    </div>
</div>

<?php endif; ?>
