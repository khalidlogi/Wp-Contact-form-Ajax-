<?php
/*
Plugin Name: Contact Info Plugin
Description: Displays user contact information and adds AJAX search from WPDB.
Version: 1.0
Author: Your Name
Author URI: https://yourwebsite.com
*/

if (!defined('ABSPATH')) {
    wp_die(__('Error'));
}

require_once plugin_dir_path(__FILE__). 'Contact_Info_Inser.php';

class Contact_Info_Plugin {


    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        //add_action( 'the_content', array( $this, 'display_contact_info' ) );
        add_action( 'wp_ajax_contact_info_search', array( $this, 'contact_info_search' ) );
        add_action( 'wp_ajax_nopriv_contact_info_search', array( $this, 'contact_info_search' ) );
        add_shortcode('contact_info', array( $this,'display_contact_info'));
        register_activation_hook(__FILE__,  array($this,'create_custom_table_on_activation'));


    }

    function create_custom_table_on_activation() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'kh_contact';
        $charset_collate = $wpdb->get_charset_collate();
      
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name) {
      
          $sql = "CREATE TABLE $table_name (
              id INT NOT NULL AUTO_INCREMENT,
              name VARCHAR(50) NOT NULL,
              email VARCHAR(100) NOT NULL,
              phone VARCHAR(20) NOT NULL,
              city VARCHAR(50) NOT NULL,
              PRIMARY KEY  (id)
            ) $charset_collate;";
      
          require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
          dbDelta($sql);

          
        }
      }

    // Enqueue jQuery for AJAX
    public function enqueue_scripts() {

        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'my-custom-scripts', plugin_dir_url( __FILE__ ) . 'my-custom-scripts.js', array( 'jquery' ), '1.0.0', true );
        // Localize your script and pass data
        $data = array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'my_nonce' )
        );
        wp_localize_script( 'my-custom-scripts', 'MyCustomScriptsData', $data );
    }

    // Display user contact information
    public function display_contact_info() {
        global $post;
        $user_id = $post->post_author;
        $user_info = get_userdata( $user_id );
        $name = $user_info->display_name;
        $email = $user_info->user_email;
        $phone = get_user_meta( $user_id, 'phone', true );
        $city = get_user_meta( $user_id, 'city', true );

     
    ob_start();
    ?>
    <h3>Contact Information</h3>
    <ul>
        <li>Name: <?php echo $name; ?></li>
        <li>Email: <?php echo $email; ?></li>
        <li>Phone: <?php echo $phone; ?></li>
        <li>City: <?php echo $city; ?></li>
    </ul>
    <?php
    $output = ob_get_clean();

    return $output;
    }

    // AJAX search from WPDB
    public function contact_info_search() {
        global $wpdb;
        $search_query = $_POST['search_query'];
        $results = $wpdb->get_results( "SELECT * FROM $wpdb->users WHERE display_name LIKE '%$search_query%'" );

        $output = '';
        foreach ( $results as $result ) {
            $name = $result->display_name;
            $email = $result->user_email;
            $phone = get_user_meta( $result->ID, 'phone', true );
            $city = get_user_meta( $result->ID, 'city', true );

            $output .= '<div class="search-result">';
            $output .= '<h4>' . $name . '</h4>';
            $output .= '<p>Email: ' . $email . '</p>';
            $output .= '<p>Phone: ' . $phone . '</p>';
            $output .= '<p>City: ' . $city . '</p>';
            $output .= '</div>';
        }

        echo $output;
        wp_die();
    }

}

// Instantiate the plugin
new Contact_Info_Plugin();
new Contact_Info_Inser();
