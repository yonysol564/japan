<?php
/*****************************************
**  Define
*****************************************/

if( !defined('THEME') )
    define("THEME", get_template_directory_uri());
//if wpml
// define("LANG",ICL_LANGUAGE_CODE);
define("LANG", "he");
if(is_rtl()){
    define("FLOAT", 'right');
    define("FOUNDATION", THEME.'/foundation-6.2.3-rtl');
}
else{
    define("FLOAT", 'left');
    define("FOUNDATION", THEME.'/foundation-6.2.1-ltr');
}

if( !defined('TEMPLATEPATH') )
	define( 'TEMPLATEPATH', get_template_directory() );

get_template_part("admin/branch","metabox");

/*****************************************
**  Languages
*****************************************/
// Localisation Support
add_action('after_setup_theme', 'qstheme_textdomain');
function qstheme_textdomain(){
    load_theme_textdomain('qstheme', THEME . '/languages');
}
/*****************************************
**  Front AJAX
*****************************************/
    get_template_part("functions/front","ajax");
    get_template_part("admin/woocommerce");
/*****************************************
**  Hooks && Filters
*****************************************/
    get_template_part("functions/hooks");
/*****************************************
**  ACF Framework
*****************************************/
    get_template_part("admin/options");
/*****************************************
**  Includes
*****************************************/
    get_template_part("functions/core-functions");
    get_template_part("functions/functions");
    get_template_part("admin/types");
    get_template_part("admin/ajax_function");
/*****************************************
**  Widgets INIT
*****************************************/
    get_template_part("functions/widgets");
/*****************************************
**  Plugin
*****************************************/
//Page children menu
get_template_part("functions/plugins/get_page_children");
// Call to function with:  build_menu_list($post, 'page',true);

//Get Image Plugin
get_template_part("functions/plugins/get_image");
// Call to function with:  get_image( array() ) // for more info https://github.com/nivnoiman/get_image

/*****************************************
**  Global
*****************************************/
if (!isset($content_width)) {
    $content_width = 1170;
}

if (function_exists('add_theme_support')){

    // Add Theme Support
    add_theme_support('menus');
    add_theme_support('post-thumbnails');

    add_image_size('large', 800, '', true); // Large Thumbnail
    add_image_size('medium', 250, '', true); // Medium Thumbnail
    add_image_size('small', 120, '', true); // Small Thumbnail

    add_image_size('home_slider', 1920, 580, true);
    add_image_size('home_slider_mobile', 768, 580, true);
    add_image_size('home_slider_mobile_small', 375, 580, true);

    add_image_size('small_product_thumbnail', 100, 100, true);
    add_image_size('large_product_thumbnail', 360, 250, true);
    add_image_size('incart_product_thumbnail', 80, 80, true);

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');
}

//Remove WORDPRESS
function disable_srcset( $sources ) {
    return false;
}
add_filter( 'wp_calculate_image_srcset', 'disable_srcset' );

//Woocommerce Support
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

//Get all branches list
function get_japan_branches() {

    global $post;

    $args = array( 'posts_per_page' => -1, 'post_type'=> 'branch' );
    $branches = array();
    $myposts = get_posts( $args );
        foreach ( $myposts as $post ) : setup_postdata( $post );
            $branches[$post->ID] = $post->post_title;
        endforeach;
    wp_reset_postdata();

    return $branches;

}

function checkout_access(){
    global $post;
    $bargs = array(
        'post_type'      => 'branch',
        'posts_per_page' => -1
    );
    $branches       = new WP_Query($bargs);
    $branches_array = array();

    while($branches->have_posts()):$branches->the_post();
        $branches_array[] = $post->ID;
    endwhile; wp_reset_query();

    $bid = isset($_GET['bid']) ? sanitize_text_field($_GET['bid']): '';
    if(!$bid || !in_array($bid,$branches_array)){
        wp_redirect( home_url() );
        exit();
    }
}

function get_order_titles(){

    $order_titles = array();

    $order_step_1_title = get_field('order_step_1_title','option');
    $order_step_2_title = get_field('order_step_2_title','option');
    $order_step_3_title = get_field('order_step_3_title','option');

    $order_titles[] = $order_step_1_title;
    $order_titles[] = $order_step_2_title;
    $order_titles[] = $order_step_3_title;

    return $order_titles;

}

function block_user_access_to_page(){
    global $post;
    if( is_singular('product') && !is_admin() ) {
        wp_redirect( home_url() );
        exit;
    }
}

add_action("template_redirect", 'redirection_function');
function redirection_function(){
    global $woocommerce;
    if( is_cart() ){
        wp_redirect( home_url() );
        exit;
    }
}

$result = add_role( 'branch_manager', __('מנהל סניף' ),

    array(
        'read'              => true, // true allows this capability
        'edit_posts'        => true, // Allows user to edit their own posts
        'edit_pages'        => true, // Allows user to edit pages
        'edit_others_posts' => true, // Allows user to edit others posts not just their own
        'create_posts'      => true, // Allows user to create new posts
        'manage_categories' => false, // Allows user to manage post categories
        'publish_posts'     => true, // Allows the user to publish, otherwise posts stays in draft mode
        'edit_themes'       => false, // false denies this capability. User can’t edit your theme
        'install_plugins'   => false, // User cant add new plugins
        'update_plugin'     => false, // User can’t update any plugins
        'update_core'       => false, // user cant perform core updates
    )

);



function get_role_by_id( $id ) {

    if ( !is_user_logged_in() ) { return false; }

    $oUser = get_user_by( 'id', $id );
    $aUser = get_object_vars( $oUser );
    $sRole = $aUser['roles'][0];
    return $sRole;

}

add_action( 'admin_init', 'branch_manager_remove_menu_pages' );
function branch_manager_remove_menu_pages() {

    global $user_ID;

    $user_role = get_role_by_id( $user_ID );

    if( $user_role == 'branch_manager' ) {
        remove_menu_page( 'theme-general-settings' );
        remove_menu_page( 'customtaxorder' );
        remove_menu_page( 'tools.php' );
        remove_menu_page( 'wpcf7' );
        remove_menu_page( 'edit-comments.php' );
    }

}

//custom CF7 element
wpcf7_add_shortcode('term_box', 'create_term_checkbox', true);

function create_term_checkbox($element) {
    global $post;

    if($element['attr']){
        $attrs     = explode('|',$element['attr']);
        $label_arr = explode('=',$attrs[0]);
        $link_arr  = explode('=',$attrs[1]);

        $label     = $label_arr[1];
        $label     = str_replace('"','',$label);
        $link      = $link_arr[1];
        $link_html = str_replace('התקנון','<a href="'.$link.'">התקנון</a>',$label);
    }

    ob_start();
    ?>
        <span class="wpcf7-list-item first last term_box_wrap">
            <input type="checkbox" name="term_box[]" value="<?php echo $label; ?>">
            <span class="wpcf7-list-item-label"><?php echo $link_html; ?></span>
        </span>
    <?php
    $result = ob_get_clean();

    return $result;
}
