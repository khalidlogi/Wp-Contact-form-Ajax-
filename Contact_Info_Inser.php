<?php

if (!defined('ABSPATH')) {
    wp_die(__('Error'));
}


class Contact_Info_Inser {


    public function __construct() {
       
        add_action( 'wp_ajax_my_user_insert', array($this,'my_user_insert') );
        add_action( 'wp_ajax_nopriv_my_user_insert', array($this,'my_user_insert' ));
        add_shortcode( 'display_user_form', array($this,'display_user_form'));

    }


 
      

      
function display_user_form(){
   ob_start();
    
   require_once(plugin_dir_path( __FILE__).'html/form.php');

  $output = ob_get_clean();

  return $output;
}


//Create a PHP function to handle the AJAX request to insert user data

function my_user_insert() {

    global $wpdb;
    $response[]= array();
    $response['status'] = true;
    $data = array();



    // Get data from form submission
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];

   

        // validate name
        if (empty($name)) {
            //$response['status'] = false;
            $data["name"] = "Please enter your name";
           // $response['message'] = 'Please fill in the name correctly!';
        }

        // validate email
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data["email"] = "Please enter a valid email";

        }
     


        // validate phone number
        if (empty($phone)) {
            $data["phone"] = "Please enter a valid phone number";

        } else {
            $phone = preg_replace('/[^0-9]/', '', $phone);  // remove non-numeric characters
            if (strlen($phone) !== 10) {
                $data["phone"] = "Please enter a valid phone number";
            }
        }

      

        
         // Check if all required fields exist and are not empty
    if(empty($data['name']) && empty($data['email']) && empty($data['phone'])) {
        $data['success'] = true; 
        $data['message'] = 'Thanks ...';
        //wp_send_json($data);
    }
    else {
        $data['success'] = false;
        $data['message'] = 'Error ...';
        wp_send_json($data);
    }
   

    // After Validation complete
        // Insert data into database
        $table_name = $wpdb->prefix . 'kh_contact';
        $status = $wpdb->insert(
            $table_name,
            array(
                'name' => sanitize_text_field($name),
                'email' => sanitize_email($email),
                'phone' => sanitize_text_field($phone),
                'city' => sanitize_text_field($city),
            )
        );

        if($status === false) {
            $data['status'] = false;
            $data['message'] = 'Error Database';
            wp_send_json($data);
        } else {
        // $response['status'] = false;
            $data['message'] = 'Data has been saved successfully';
           wp_send_json($data);
        }

        

        

       

    }
    
//Finally, use the shortcode [display_user_form] where you want to display the form in any page or post.
//Note: Make sure to enqueue the my-custom-scripts.js file, including it after jQuery in the footer section of your theme.


}