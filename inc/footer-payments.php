<?php $footer_payments_logo = get_field('footer_payments_logo','options'); ?>

<?php if($footer_payments_logo): ?>
    <div class="footer_payments_logo">
        <img src="<?php echo $footer_payments_logo['url']; ?>" alt="<?php echo $footer_payments_logo['alt']; ?>" />
    </div>
<?php endif; ?>
