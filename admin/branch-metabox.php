<?php
/* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'branch_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'branch_post_meta_boxes_setup' );

/* Meta box setup function. */
function branch_post_meta_boxes_setup() {
  /* Add meta boxes on the 'add_meta_boxes' hook. */
  add_action( 'add_meta_boxes', 'branch_add_products_meta_boxes' );
}
/* Create one or more meta boxes to be displayed on the post editor screen. */
function branch_add_products_meta_boxes() {

  add_meta_box(
    'all-woo-products-class',      // Unique ID
    esc_html__( 'All Products', 'example' ),    // Title
    'branch_products_class_meta_box',   // Callback function
    'branch',         // Admin page (or post type)
    'normal',         // Context
    'default'         // Priority
  );
}
/* Display the post meta box. */
function branch_products_class_meta_box( $object, $box ) {
    global $post;
    $branch_id = $post->ID;
    wp_nonce_field( basename( __FILE__ ), 'smashing_post_class_nonce' );

    $product_args = array(
        'post_type'=>'product',
        'posts_per_page'=>-1
    );
    $products = new WP_Query($product_args);
    if($products->have_posts()):
    ?>

    <div class="data_table_wrapper">

        <table id="example" class="display" cellspacing="0" width="100%" dir="rtl">
                <thead>
                    <tr>
                        <th>מזהה</th>
                        <th>שם מוצר</th>
                        <th>קטגוריה</th>
                        <th>מחיר</th>
                        <th>אין במלאי</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while( $products->have_posts() ) : $products->the_post();
                        $the_term = get_the_terms($post->ID,'product_cat');
                        if(is_array($the_term)){
                            $the_term = reset($the_term);
                        }
                        $price        = get_post_meta( $branch_id, "_regular_price_{$post->ID}", true);
                        $stock_status = get_post_meta( $branch_id,"_stock_status_{$post->ID}", true );
                        $value = 0;
                        $checked = '';
                        if($stock_status && $stock_status=='outofstock') {
                            $value = 1;
                            $checked = 'checked="true"';
                        }
                    ?>
                        <tr>
                            <td><?php echo $post->ID; ?></td>
                            <td style="min-width:180px;">
                                [<a href="<?php echo get_edit_post_link($post->ID); ?>" target="_blank">עריכה</a>]
                                <strong><span class="admin_product_title"><?php the_title(); ?></span></strong>
                            </td>
                            <td style="text-align:center;">
                                <?php echo isset($the_term) ? $the_term->name : ''; ?>
                            </td>
                            <td>
                                <input type="number" data-id="<?php echo $post->ID; ?>" class="product_price" value="<?php echo $price; ?>" />
                            </td>
                            <td>
                                <label>
                                    <input type="checkbox" data-id="<?php echo $post->ID; ?>" class="product_not_in" value="<?php echo $value; ?>" <?php echo $checked; ?> />
                                    <span class="stock_status_label <?php echo $stock_status; ?>">
                                        <?php echo $stock_status; ?>
                                    </span>
                                </label>
                            </td>
                        </tr>
                    <?php endwhile; wp_reset_query(); ?>
                </tbody>
            </table>

    </div>

    <?php else: ?>
        <h3>Has no products yet =)</h3>
    <?php endif; ?>

<?php }


//Update Product Price
add_action( 'wp_ajax_update_product_price_metabox', 'update_product_price_metabox' );
function update_product_price_metabox(){
    $product_price = isset($_POST['product_price']) ? sanitize_text_field($_POST['product_price']): '';
    $product_id    = isset($_POST['product_id']) ? sanitize_text_field($_POST['product_id'])      : '';
    $branch_id     = isset($_POST['branch_id']) ? sanitize_text_field($_POST['branch_id'])      : '';
    if($product_id && $product_price && $branch_id){
        update_post_meta( $branch_id, "_regular_price_{$product_id}", $product_price );
        echo json_encode("ok");
    }
    die();
}
//Update Product Stock status
add_action( 'wp_ajax_update_product_stock_status_metabox', 'update_product_stock_status_metabox' );
function update_product_stock_status_metabox(){

    $result     = array();
    $product_id = isset($_POST['product_id']) ? sanitize_text_field($_POST['product_id']) : '';
    $branch_id =  isset($_POST['branch_id']) ? sanitize_text_field($_POST['branch_id']) : '';
    $status     = empty($_POST['status']) ? 'outofstock' : 'instock';
    if($product_id && $branch_id){
        update_post_meta( $branch_id, "_stock_status_{$product_id}", $status );
        $result['status'] = 'ok';
        $result['stock'] = $status;
    }
    echo json_encode($result);
    die();
}
