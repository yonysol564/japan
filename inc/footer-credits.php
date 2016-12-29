<?php $footer_credits = get_field('footer_credits','options'); ?>

<?php if($footer_credits): ?>
    <div class="footer_credits"><?php echo $footer_credits; ?></div>
<?php endif; ?>
