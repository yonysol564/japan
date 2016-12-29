<?php
    $order_titles = get_order_titles();
    if( is_page_template('template-order-step1.php') || is_page_template('template-checkout.php') ) {
        $current_step = 1;
    } elseif( is_page_template('template-order-step2.php') ) {
        $current_step = 2;
    } else {
        $current_step = 3;
    }
?>
<?php if($order_titles):?>
    <div class="order_titles_wrapper clear">
        <?php
            $counter=0;
            foreach($order_titles as $title):
                $counter++;
                $class="";
                if($counter==$current_step){
                    $class="current_step";
                }
        ?>
            <div class="step_circle <?php echo $class; ?>">
                <div class="circle_title"><?php echo $title; ?></div>
                <div class="circle_element">
                    <div class="circle_element_inner"><?php echo $counter; ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
