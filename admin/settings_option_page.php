<?php
/**
 * @internal    never define functions inside callbacks.
 *              these functions could be run multiple times; this would result in a fatal error.
 */
 
/**
 * custom option and settings
 */
function tsapi_theshire_api_settings_init()
{
    // register a new setting for "theshire_api" page
    register_setting('theshire_api', 'theshire_api_options');
    register_setting('theshire_image_api', 'theshire_api_image_options');

    // register a new section in the "theshire_api" page
    add_settings_section(
        'theshire_api_section_developers',
        __('Your Thershire API Settings.', 'theshire_api'),
        'tsapi_theshire_api_section_developers_cb',
        'theshire_api'
    );

    // register a new field in the "theshire_api_section_developers" section, inside the "theshire_api" page
    add_settings_field(
        'theshire_api_field_username', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __('Email / Username :', 'theshire_api'),
        'tsapi_theshire_api_field_username_cb',
        'theshire_api',
        'theshire_api_section_developers',
        [
            'label_for'         => 'theshire_api_field_username',
            'class'             => 'theshire_api_row',
            'theshire_api_custom_data' => 'custom',
        ]
    );

    // register a new field in the "theshire_api_section_developers" section, inside the "theshire_api" page
    add_settings_field(
        'theshire_api_field_privatekey', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __('Private Key :', 'theshire_api'),
        'tsapi_theshire_api_field_privatekey_cb',
        'theshire_api',
        'theshire_api_section_developers',
        [
            'label_for'         => 'theshire_api_field_privatekey',
            'class'             => 'theshire_api_row',
            'theshire_api_custom_data' => 'custom',
        ]
    );

     // register a new field in the "theshire_api_section_developers" section, inside the "theshire_api" page
    add_settings_field(
        'theshire_api_field_publickey', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __('Public Key :', 'theshire_api'),
        'tsapi_theshire_api_field_publickey_cb',
        'theshire_api',
        'theshire_api_section_developers',
        [
            'label_for'         => 'theshire_api_field_publickey',
            'class'             => 'theshire_api_row',
            'theshire_api_custom_data' => 'custom',
        ]
    );


    // register a new section in the "theshire_api" page
    add_settings_section(
        'theshire_api_section_image_upload',
        __('Default Image Settings.', 'theshire_image_api'),
        'tsapi_theshire_api_section_image_upload_cb',
        'theshire_image_api'
    );

    // register a new field in the "theshire_api_section_developers" section, inside the "theshire_api" page
    add_settings_field(
        'theshire_api_field_imageupload', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __('Default Image :', 'theshire_image_api'),
        'tsapi_theshire_api_field_imageupload_cb',
        'theshire_image_api',
        'theshire_api_section_image_upload',
        [
            'label_for'         => 'theshire_api_field_imageupload',
            'class'             => 'theshire_api_image_row',
            'theshire_api_image_custom_data' => 'custom',
        ]
    );

}
 
/**
 * register our theshire_api_settings_init to the admin_init action hook
 */
add_action('admin_init', 'tsapi_theshire_api_settings_init');
 
/**
 * custom option and settings:
 * callback functions
 */
 
// developers section cb
 
// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function tsapi_theshire_api_section_developers_cb($args)
{
    ?>
    <p id="<?= esc_attr($args['id']); ?>"><?= esc_html__('Provide Email address, Private key and Public Key of your theshire account. If you do not have those detail, please ask from theshire support person.', 'theshire_api'); ?></p>
    <?php
}

function tsapi_theshire_api_section_image_upload_cb($args)
{
    ?>
    <p id="<?= esc_attr($args['id']); ?>"><?= esc_html__('Set default image that help to create article from blog post(blog post that do not have feature image.)', 'theshire_image_api'); ?></p>
    <?php
}

// pill field cb
 
// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function tsapi_theshire_api_field_username_cb($args)
{
    // get the value of the setting we've registered with register_setting()
    $tsapi_options = get_option('theshire_api_options');
    // output the field
    ?>
    <div class="form-group">
      <input type="email" class="form-control" id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['theshire_api_custom_data']); ?>" name="theshire_api_options[<?= esc_attr($args['label_for']); ?>]" 
      value="<?= $tsapi_options[$args['label_for']]; ?>" placeholder="Enter email">
    </div>
    <?php
}

function tsapi_theshire_api_field_privatekey_cb($args)
{
    $tsapi_options = get_option('theshire_api_options');
    ?>
    <div class="form-group">
      <input type="text" class="form-control" id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['theshire_api_custom_data']); ?>" name="theshire_api_options[<?= esc_attr($args['label_for']); ?>]" 
      value="<?= $tsapi_options[$args['label_for']]; ?>" placeholder="Enter Private Key">
    </div>
    <?php
}

function tsapi_theshire_api_field_publickey_cb($args)
{
    $tsapi_options = get_option('theshire_api_options');
    ?>
    <div class="form-group">
      <input type="text" class="form-control" id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['theshire_api_custom_data']); ?>" name="theshire_api_options[<?= esc_attr($args['label_for']); ?>]" 
      value="<?= $tsapi_options[$args['label_for']]; ?>" placeholder="Enter Public Key">
    </div>
    <?php
}

//
function tsapi_theshire_api_field_imageupload_cb($args)
{
    $tsapi_image_options = get_option('theshire_api_image_options');
    $imageurl=$tsapi_image_options[$args['label_for']];
    ?>
    <div class="form-group">
     <?php // jQuery
        wp_enqueue_script('jquery');
        // This will enqueue the Media Uploader script
        wp_enqueue_media();
        ?>
        <div>
            <input type="text" class="form-control regular-text" id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['theshire_api_custom_data']); ?>" name="theshire_api_image_options[<?= esc_attr($args['label_for']); ?>]" value="<?= $imageurl ?>" placeholder="Default Image">
            <input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Upload Image">

            <?php if(($imageurl !="") || ($imageurl != null)){ ?>
            <img src="<?= $imageurl ?>" alt="Default Image" style="width: 50%;height: auto;margin-top: 2%;">
            <?php }   ?>
        </div>
        <script type="text/javascript">
        jQuery(document).ready(function($){
            $('#upload-btn').click(function(e) {
                e.preventDefault();
                var image = wp.media({ 
                    title: 'Upload Image',
                    // mutiple: true if you want to upload multiple files at once
                    multiple: false
                }).open()
                .on('select', function(e){
                    // This will return the selected image from the Media Uploader, the result is an object
                    var uploaded_image = image.state().get('selection').first();
                    // We convert uploaded_image to a JSON object to make accessing it easier
                    // Output to the console uploaded_image
                    console.log(uploaded_image);
                    var image_url = uploaded_image.toJSON().url;
                    // Let's assign the url value to the input field
                    $('#theshire_api_field_imageupload').val(image_url);
                });
            });
        });
        </script>

    </div>
    <?php
}

/**
 * top level menu
 */
function tsapi_theshire_api_options_page()
{
    // add top level menu page
    add_menu_page(
        'Theshire API Settings',
        'Theshire API',
        'manage_options',
        'theshire_api',
        'tsapi_theshire_api_options_page_html'
    );
}
 
/**
 * register our theshire_api_options_page to the admin_menu action hook
 */
add_action('admin_menu', 'tsapi_theshire_api_options_page');
 
/**
 * top level menu:
 * callback functions
 */
function tsapi_theshire_api_options_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
 
    // add error/update messages
 
    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('theshire_api_messages', 'theshire_api_message', __('Settings Saved', 'theshire_api'), 'updated');
    }
 
    // show error/update messages
    settings_errors('theshire_api_messages');
    ?>
    <div class="wrap container">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "theshire_api"
            settings_fields('theshire_api');
            // output setting sections and their fields
            // (sections are registered for "theshire_api", each field is registered to a specific section)
            do_settings_sections('theshire_api');
            // output save settings button
            submit_button('Save API Settings');
            ?>
        </form>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "theshire_api"
            settings_fields('theshire_image_api');
            // output setting sections and their fields
            // (sections are registered for "theshire_api", each field is registered to a specific section)
            do_settings_sections('theshire_image_api');
            // output save settings button
            submit_button('Save Image');
            ?>
        </form>
    </div>
    <?php
}

// function test_handle_post(){

//         // First check if the file appears on the _FILES array
//         if(isset($_FILES[])){
//                 $pdf = $_FILES['test_upload_pdf'];
 
//                 // Use the wordpress function to upload
//                 // test_upload_pdf corresponds to the position in the $_FILES array
//                 // 0 means the content is not associated with any other posts
//                 $uploaded=media_handle_upload('test_upload_pdf', 0);
//                 // Error checking using WP functions
//                 if(is_wp_error($uploaded)){
//                         echo "Error uploading file: " . $uploaded->get_error_message();
//                 }else{
//                         echo "File upload successful!";
//                 }
//         }
// }

