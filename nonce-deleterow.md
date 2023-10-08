## button
  ```php
echo '<button class="delete-btn" data-form-id="' . esc_attr($id) . '"
                             data-nonce="' . wp_create_nonce('ajax-nonce') . '">
                             <i class="fas fa-trash"></i></button>';
```

 ## Ajax
 ```php
$(".delete-btn").on("click", function () {
    var form_id = $(this).data("form-id");
    var id = $(this).data("form-id");
    var nonce = $(this).data("nonce");

    if (confirm("Are you sure you want to delete this?")) {
      var data = {
        action: "delete_form_row",
        form_id: form_id,
        id: id,
        nonce: nonce,
      };
```

  ## php
    ```php
    function delete_form_row()
        {
            global $wpdb;

            $id = intval($_POST['form_id']);

            if (!$id) {
                wp_send_json_error('Invalid ID');
                exit;
            }

            // Check permissions
            if (!current_user_can('delete_posts')) {
                wp_send_json_error('Insufficient permissions');
                exit;
            }

            // Check for nonce security      
            if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
                die('Busted!');
            }

            $this->mydb->delete_data($id);
            if (!$wpdb->delete()) {
                wp_send_json_error('Error deleting');
                exit;

            }
            wp_send_json_success('deleted successfully');
            exit;

        }
        ```
