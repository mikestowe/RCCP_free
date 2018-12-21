<?php 
/*
 Plugin Name: A RingCentral
 Plugin URI: https://ringcentral.com/
 Description: RingCentral Plugin
 Author: Peter MacIntyre / Mike Stowe
 Version: 0.0.1
 Author URI: https://paladin-bs.com
*/
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);

function ringcentral_js_add_script() {
    $js_path = plugins_url('js/', __FILE__) . 'ringcentral-scripts.js' ;
    wp_enqueue_script('ringcentral-js', $js_path) ;
}
add_action('init', 'ringcentral_js_add_script');

function ringcentral_css_add_script() {    
    $styles_path = plugins_url('css/', __FILE__) . 'ringcentral-custom.css' ;
    wp_enqueue_style('ringcentral-css', $styles_path) ;
}

add_action('init', 'ringcentral_css_add_script');

// call add action func on menu building function above.
add_action('admin_menu', 'ringcentral_menu'); 

/* ========================================= */
/* Make top level menu                       */
/* ========================================= */
function ringcentral_menu(){
    add_menu_page(
        'RingCentral Plugin Management Page',       // Page & tab title
        'RingCentral',                              // Menu title
        'manage_options',                           // Capability option
        'ringcentral_Admin',                        // Menu slug
        'ringcentral_config_page',                  // menu destination function call
        plugin_dir_url(__FILE__) . 'images/ringcentral-icon.jpg', // menu icon path
//         'dashicons-phone', // menu icon path from dashicons library
        40 );                                       // menu position level         
     
    add_submenu_page(
        'ringcentral_Admin',              // parent slug
        'RingCentral Configuration Page', // page title
        'Settings',                       // menu title - can be different than parent
        'manage_options',                 // options
        'ringcentral_Admin' );            // menu slug to match top level (go to the same link)
    
    add_submenu_page(
        'ringcentral_Admin',                // parent menu slug
        'RingCentral Add Subscribers Page', // page title
        'Add Subscribers',                  // menu title
        'manage_options',                   // capability
        'ringcentral_add_subs',             // menu slug
        'ringcentral_add_subscribers'       // callable function
        );    
    add_submenu_page(
        'ringcentral_Admin',                   // parent menu slug
        'RingCentral Manage Subscribers Page', // page title
        'List Subscribers',                    // menu title
        'manage_options',                      // capability
        'ringcentral_list_subs',               // menu slug
        'ringCentral_list_subscribers'         // callable function
        );    
    add_submenu_page(
        'ringcentral_Admin',                // parent menu slug
        'RingCentral CallMe Requests Page', // page title
        'Call Me Requests',                 // menu title
        'manage_options',                   // capability
        'ringcentral_list_callme',          // menu slug
        'ringCentral_list_callme_requests'  // callable function
        );    

}  
 
$logo_path = plugins_url('images/', __FILE__) . 'ringcentral-logo.png' ;
 
/* ========================================= */
/* page / menu calling functions             */
/* ========================================= */
// function for default Admin page
function ringcentral_config_page() {
    // check user capabilities
    if (!current_user_can('manage_options')) { return; }
    global $logo_path ;
?>    
    <div class="wrap">
        <img id='page_title_img' title="RingCentral Plugin" src="<?= $logo_path ;?>">
        <h1 id='page_title'><?= esc_html(get_admin_page_title()); ?></h1>
        
        <?php require_once("includes/ringcentral-config-page.inc"); ?>
        
    </div>
    <?php
}

// function for adding new subscribers page
function ringcentral_add_subscribers() {
    // check user capabilities
    if (!current_user_can('manage_options')) { return; }
    global $logo_path ;
    ?>
    <div class="wrap">
        <img id='page_title_img' title="RingCentral Plugin" src="<?= $logo_path ;?>">
        <h1 id='page_title'>RingCentral Add Subscribers</h1>
        
       <?php require_once("includes/ringcentral-add-subscribers.inc"); ?>
       
    </div>
    <?php
}

// function for editing existing subscribers page
function ringCentral_list_subscribers() {
    // check user capabilities
    if (!current_user_can('manage_options')) { return; }
    global $logo_path ;
    ?>
        
    <div class="wrap">
        <img id='page_title_img' title="RingCentral Plugin" src="<?= $logo_path ;?>">
        <h1 id='page_title'>RingCentral Manage Subscribers</h1>
        
       <?php require_once("includes/ringcentral-list-subscribers.inc"); ?>
       
    </div>
    <?php
}
// function for editing existing subscribers page
function ringCentral_list_callme_requests() {
    // check user capabilities
    if (!current_user_can('manage_options')) { return; }
    global $logo_path ;
    ?>
        
    <div class="wrap">
        <img id='page_title_img' title="RingCentral Plugin" src="<?= $logo_path ;?>">
        <h1 id='page_title'>RingCentral Call Me Requests</h1>
        
       <?php require_once("includes/ringcentral-list-callme.inc"); ?>
       
    </div>
    <?php
}

/* ========================================================== */ 
/* Add action for the ringcentral Embedded Phone app toggle   */
/* ========================================================== */
add_action('admin_footer', 'ringcentral_embed_phone');	

/* =============================================== */
/* Add custom footer action                        */
/* This toggles the ringcentral Embedded Phone app */
/* =============================================== */
function ringcentral_embed_phone() {
    global $wpdb;    
    $result_rc = $wpdb->get_row( $wpdb->prepare("SELECT `embedded_phone` 
        FROM `ringcentral_control`
        WHERE `ringcentral_control_id` = %d", 1)
    );    
    if ($result_rc->embedded_phone == 1) { ?>
    	<script src="https://ringcentral.github.io/ringcentral-embeddable-voice/adapter.js"></script>
    <?php } 
}
/* ================================== */
/* Add action for the contacts widget */
/* ================================== */
add_action('widgets_init', 'ringcentral_register_contacts_widget');

/* ============================================== */
/* Add contacts widget function                   */
/* This registers the ringcentral_contacts_widget */
/* ============================================== */
function ringcentral_register_contacts_widget() {
  register_widget('ringcentral_contacts_widget') ;   
}

require_once("includes/ringcentral-contacts-widget.inc"); 

/* ================================= */
/* Add action for the Call Me widget */
/* ================================= */
add_action('widgets_init', 'ringcentral_register_callme_widget');

/* ============================================ */
/* Add Call Me widget function                  */
/* This registers the ringcentral_callme_widget */
/* ============================================ */
function ringcentral_register_callme_widget() {
     register_widget('ringcentral_callme_widget') ;
}

require_once("includes/ringcentral-callme-widget.inc"); 

/* ============================================== */
/* Add action hook for correspondence on new post */
/* ============================================== */
add_action( 'pending_to_publish', 'ringcentral_new_post_send_notifications');
add_action( 'draft_to_publish', 'ringcentral_new_post_send_notifications');

function ringcentral_new_post_send_notifications( $post ) {
    global $wpdb ;    
    $result_rc = $wpdb->get_row( $wpdb->prepare("SELECT `email_updates`, `mobile_updates`
        FROM `ringcentral_control`
        WHERE `ringcentral_control_id` = %d", 1)
    );    
    // If this is a revision, don't send the correspondence.
    if (wp_is_post_revision( $post->ID )) return;
    
    // this is also triggered on a page publishing, so ensure that the type is a Post and then carry on    
    if (get_post_type($post->ID) === 'post') {    
        // only send out correspondence if set in control / admin
        if ($result_rc->email_updates) { require_once("includes/ringcentral-send-mass-email.inc"); }    
        if ($result_rc->mobile_updates) { require_once("includes/ringcentral-send-mass-mobile.inc"); }
    }
}
/* ============================================= */
/* Add registration hook for plugin installation */
/* ============================================= */
function ringcentral_install() {
   require_once("includes/ringcentral-install.inc");
}
/* ========================================= */
/* Create default pages on plugin activation */
/* ========================================= */
function install_default_pages(){
   require_once("includes/ringcentral-activation.inc");
}

register_activation_hook(__FILE__, 'ringcentral_install');
register_activation_hook(__FILE__, 'install_default_pages');

/* ====================================== */
/* bring in generic ringcentral functions */
/* ====================================== */
require_once("includes/ringcentral-functions.inc");

?>