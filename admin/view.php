<?php
// Add Meta box in Post "post-type"
add_action( 'add_meta_boxes', 'theshire_category_meta_box' );
function theshire_category_meta_box(){
    
    $screens = ['post']; // Add custom post type, if required.
    foreach ($screens as $screen){
        add_meta_box( '_theshire_category_box_select',
            'Theshire.co Categories',
            'theshire_category_meta_box_cb',
            'post',
            'normal', 'high'
            );
        }
}

// Add Category dropdown in Meta box.
function theshire_category_meta_box_cb( $post ){
    
    $tsapi_post_meta = get_post_custom( $post->ID,'_theshire_category_box_select',true);
    $tsapi_cat_id=$tsapi_post_meta['_theshire_category_box_select'][0];
    ?>
    <p>Select Theshire.co news category</p>
    <p>
        <label for="theshire_category_box_selects">Categories : </label>
        <select name="theshire_category_box_selects" id="theshire_category_box_selects">
        <?php 
            $tsapi_api_Siteurl="http://phpstack-26476-56796-195904.cloudwaysapps.com/CategoryAPI";
            $response = wp_remote_post( $tsapi_api_Siteurl, $args );
            $res_body = wp_remote_retrieve_body( $response );
            $tsapi_categories=json_decode($res_body);
            foreach ($tsapi_categories as $cat) {
                echo "<option value='$cat->id' ".selected($tsapi_cat_id,$cat->id)." >".esc_html($cat->category_name )."</option>";
            }
        ?>
        </select>
    </p>
    <?php
}

function theshire_meta_box_save( $post_id )
{
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return $post_id;
    }

    $metavals=$_POST['theshire_category_box_selects'];
    update_post_meta( $post_id, $key = '_theshire_category_box_select', $value = $metavals );
}

add_action( 'save_post', 'theshire_meta_box_save');
?>