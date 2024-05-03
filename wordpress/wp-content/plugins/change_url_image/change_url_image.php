<?php 
  
  /*
  Plugin Name: Change Path URL of images
  Plugin URI: http://wordpress.com/
  Version: 1.0.0
  Author: Change Path URL
  Author URI: http://wordpress.com/
  */

add_filter('wp_get_attachment_image_src', 'shella_replace_attachment_image_src', 10, 3);
function shella_replace_attachment_image_src($image,$att_id, $size) {
  
  if ($image[0] != '') {
    $im_temp = explode('/', $image[0]);
    
    
    //var_dump($_wp_additional_image_sizes);
    $path_image = explode('wp-content/uploads', $image[0]);
   
    if ( ! file_exists( ABSPATH .'wp-content/uploads'.end($path_image) )   ) {
    // var_dump($size);  
        if (is_array($size)) {
          $image[1] = $size[0];
          $image[2] = $size[1] = 9999 ? $size[0] : $size[1];
        }else{
            global $_wp_additional_image_sizes;
            $_image_sizes = $_wp_additional_image_sizes[$size];
           // var_dump($_wp_additional_image_sizes);
           
            $image[1] = $_image_sizes['width'];
            //var_dump($_image_sizes['height']);
            if ($_image_sizes['height'] == 0 || $_image_sizes['height'] == 9999) {
                $_image_sizes['height'] = $image[1];
            }
           // $_image_sizes['height'] = $_image_sizes['height'] == 0 || $_image_sizes['height'] == "9999" ? $_image_sizes['width'] : $_image_sizes['height'];
            $image[2] = $_image_sizes['height'];
        }

        $w_h = 'w='.$image[1].'&h='.$image[2].'&zc=1&a=c';
        $w_h_temp = '-'.$image[1].'x'.$image[2];
          
        $im_temp1 = str_replace('-300x300', '', end($path_image));

        $im_temp1 = str_replace($w_h_temp, '', $im_temp1);

        $im_temp1 = str_replace('-768x768', '', $im_temp1);
         
        $im_tem = explode('/',$im_temp1) ;
        
        
        
        $im_temp = array_map("add_string", $im_tem);
        //var_dump($im_temp);
        
        
        
        $im_temp1 = implode('/',$im_temp);
          
        $image[0] = 'https://cdn.'.parse_url( get_site_url(), PHP_URL_HOST ).'/image.php?src='.$im_temp1.'&'.$w_h;
      }
  }
  //var_dump($image)  ;
  return $image;
   
}

function add_string($str) { 
    $dom = str_replace( '.com','' , parse_url( get_site_url(), PHP_URL_HOST ));
    return $dom.$str; 
}
// define the woocommerce_order_item_name callback 
function filter_woocommerce_order_item_name( $item_name, $item ) {
    
    $product = $item->get_product();
    return '#product'.$product->get_sku();
    
    /*$array_tm = [
      '|',
      'Louis Vuitton',
      'Louis Vuitton’s',
      'LV',
      'lv',
      'Gucci',
      'GG',
      'gg',
      'gucci',
      'Dior',
      'dior',
      'Chanel’s',
      'Chanel',
      'chanel’s',
      'chanel',
      'Aigner',
      'Air Jordans',
      'Alaia',
      'Alexander McQueen',
      'Alexander Wang',
      'Alexis Bittar',
      'Alfred Dunhill',
      'Amina Muaddi',
      'Amiri',
      'AquaMarin',
      'Aquazzura',
      'Armani Collezioni',
      'Audemars Piguet',
      'Axel Arigato',
      'Balenciaga',
      'Bally',
      'Balmain',
      'Baume &amp; Mercier',
      'Bedat and Co.',
      'Berluti',
      'Bernhard H. Mayer',
      'Boss By Hugo Boss',
      'Bottega Veneta',
      'Boucheron',
      'Breitling',
      'Brioni',
      'Brunello Cucinelli',
      'Burberry',
      'Burberry Brit',
      'Burberry London',
      'Bvlgari',
      'Carolina Herrera',
      'Cartier',
      'Casadei',
      'Celine',
      'CH Carolina Herrera',
      'Chanel',
      'Charlotte Olympia',
      'Charriol',
      'Chaumet',
      'Chloe',
      'Chopard',
      'Christian',
      'Christian Dior',
      'Christian Louboutin',
      'Citizen',
      'Coach',
      'Concord',
      'Corum',
      'D&amp;G',
      'Damiani',
      'De Grisogono',
      'Diane Von Furstenberg',
      'Dior',
      'Dolce &amp; Gabbana',
      'Dsquared2',
      'Dunhill',
      'Ebel',
      'Elie Tahari',
      'Emilio Pucci',
      'Emporio Armani',
      'Ermenegildo Zegna',
      'Escada',
      'Etro',
      'Face Mask',
      'Fendi',
      'Franck Muller',
      'Frederique Constant',
      'Furla',
      'Gianfranco Ferre',
      'Gianni Versace',
      'Gianvito Rossi',
      'Gina',
      'Giorgio Armani',
      'Giuseppe Zanotti',
      'Givenchy',
      'Golden Goose',
      'Gucci',
      'Harry Winston',
      'HERMES',
      'Hublot',
      'IWC',
      'Jacob &amp; Co.',
      'Jaeger LeCoultre',
      'Jil Sander',
      'Jimmy Choo',
      'Jordan',
      'Just Cavalli',
      'Kenzo',
      'Lancaster',
      'Lanvin',
      'Le Silla',
      'Loewe',
      'Longines',
      'Loro Piana',
      'Louis Vuitton',
      'Maison Martin Margiela',
      'Malone Souliers',
      'Manolo Blahnik',
      'Marc by Marc Jacobs',
      'Marc Jacobs',
      'Marni',
      'Mary Katrantzou',
      'Mauboussin',
      'Maurice Lacroix',
      'Max Mara',
      'McQ by Alexander McQueen',
      'Meyers',
      'Michael Kors',
      'MICHAEL Michael Kors',
      'Mido',
      'Missoni',
      'Miu Miu',
      'Moncler',
      'Montblanc',
      'Montega',
      'Moschino',
      'Movado',
      'N21',
      'Neighborhood',
      'Nicholas Kirkwood',
      'Nike',
      'Nina Ricci',
      'Off-White',
      'Omega',
      'Palm Angels',
      'Panerai',
      'Patek Philippe',
      'Pharrell Williams',
      'Philip Stein',
      'Piaget',
      'Pierre Balmain',
      'Prada',
      'Prada Sport',
      'Rado',
      'Ralph Lauren',
      'Ralph Lauren Collection',
      'Raymond Weil',
      'RED Valentino',
      'René Caovilla',
      'Robergé',
      'Roberto Cavalli',
      'Roger Vivier',
      'Rolex',
      'S.T. Dupont',
      'Saint Laurent Paris',
      'Salvatore Ferragamo',
      'See by Chloe',
      'Sergio Rossi',
      'Smythson',
      'Sophia Webster',
      'Stella McCartney',
      'Stuart Weitzman',
      'Supreme',
      'Tag Heuer',
      'Technomarine',
      'Tiffany &amp; Co.',
      'Tissot',
      'Tod\'s',
      'Tom Ford',
      'Tory Burch',
      'Tudor',
      'Ulysse Nardin',
      'Vacheron Constantin',
      'Valentino',
      'Van Cleef &amp; Arpels',
      'Versace',
      'Vetements',
      'Weekend Max Mara',
      'Yeezy',
      'Yeezy x Adidas',
      'Yves Saint Laurent',
      'adidas',
      'gc',
      'Gc',
      'GC'
    ];
    
    $item_text = strip_tags($item_name);
    $ss = strtolower($item_text);
    foreach ($array_tm as $key_tm => $value_tm) {
      $value_tm = strtolower($value_tm);
      $ss = str_replace( $value_tm , '', $ss);
    }

    $item_name = str_replace( $item_text , ucwords($ss) , $item_name);
    return $item_name;*/
}

// add the filter 
add_filter( 'woocommerce_order_item_name', 'filter_woocommerce_order_item_name', 10, 2 ); 

//remove sku
add_filter( 'wc_product_sku_enabled', '__return_false' );




//add option page header
function change_url_image_register_settings() {

   add_option( 'change_url_image_code_in_header', '');
   add_option( 'change_url_image_code_in_footer', '');
   add_option( 'change_url_image_image_secure', '');

   register_setting( 'change_url_image_options_group', 'change_url_image_code_in_header', '','yes' );
   register_setting( 'change_url_image_options_group', 'change_url_image_code_in_footer', '','yes' );
   register_setting( 'change_url_image_options_group', 'change_url_image_image_secure_payment', 'handle_image_upload' );
}
add_action( 'admin_init', 'change_url_image_register_settings' );

function change_url_image_register_options_page() {
  add_options_page('Change Url Image', 'Change Url Image', 'manage_options', 'change_url_image', 'change_url_image_options_page');
}
add_action('admin_menu', 'change_url_image_register_options_page');

function change_url_image_options_page()
{
  ?>
  <div>
  <h2>Change Url Image Plugin</h2>
  <form  method="post" action="options.php" enctype="multipart/form-data">
  <?php settings_fields( 'change_url_image_options_group' ); ?>
  <h3>Option cần thiết</h3>
  <p>Các option cần thiết trong cài đặt site.</p>
  <table class="form-table">
    <tr valign="top">
      <th scope="row"><label for="change_url_image_code_in_header">Code In Header</label></th>
      <td>
        <textarea rows="10" class="large-text" id="change_url_image_code_in_header" name="change_url_image_code_in_header"><?php echo get_option('change_url_image_code_in_header'); ?></textarea>
      </td>
    </tr>


    <tr valign="top">
      <th scope="row"><label for="change_url_image_code_in_footer">Code In Footer</label></th>
      <td>
        <textarea rows="10"  class="large-text" id="change_url_image_code_in_footer" name="change_url_image_code_in_footer"><?php echo get_option('change_url_image_code_in_footer'); ?></textarea>
      </td>
    </tr>

    <tr valign="top">
      <th scope="row"><label for="change_url_image_image_secure_payment">Hình ảnh safe payment</label></th>
      <td>
        <input type="file" name="change_url_image_image_secure_payment" /> 
        <?php echo get_option('change_url_image_image_secure_payment') != '' ? '<img src="'. get_option('change_url_image_image_secure_payment') .'" />' : ''; ?>
      </td>
    </tr>

  </table>
  <?php  submit_button(); ?>
  </form>
  </div>
<?php
}

function handle_image_upload($option)
{ 
  //var_dump($_FILES["change_url_image_image_secure_payment"]);die;
  if(!empty($_FILES["change_url_image_image_secure_payment"]["tmp_name"]))
  {
    $urls = wp_handle_upload($_FILES["change_url_image_image_secure_payment"], array('test_form' => FALSE));
    $temp = $urls["url"];
    return $temp;  
  }
 
  return $option;
}


//add cide heard
function change_url_image_code_in_header() {
  $change_url_image_code_in_header = get_option('change_url_image_code_in_header');
  if ($change_url_image_code_in_header) {
    echo $change_url_image_code_in_header;
  }
  
}
add_action('wp_head', 'change_url_image_code_in_header');

function change_url_image_code_in_footer() {
  $change_url_image_code_in_footer = get_option('change_url_image_code_in_footer');
  if ($change_url_image_code_in_footer) {
    echo get_option('change_url_image_code_in_footer');
  }
}
add_action('wp_footer', 'change_url_image_code_in_footer');



//auto add alt
add_filter('wp_get_attachment_image_attributes', 'change_attachement_image_attributes', 20, 2);

function change_attachement_image_attributes( $attr, $attachment ){
    // Get post parent
    $parent = get_post_field( 'post_parent', $attachment);

    // Get post type to check if it's product
    $type = get_post_field( 'post_type', $parent);
    if( $type != 'product' ){
        return $attr;
    }

    if ( isset( $attr['class'] ) && 'custom-logo' === $attr['class'] ) {
        return $attr;
    }

    /// Get title
    $title = get_post_field( 'post_title', $parent);

    if( $attr['alt'] == ''){
      $attr['alt'] = $title;
      $attr['title'] = $title;
    }
    return $attr;
}


add_filter( 'get_post_metadata', 'add_dynamic_post_meta', 10, 4 );
function add_dynamic_post_meta( $value, $post_id, $meta_key, $single ) {
  if ($meta_key == '_wp_attachment_image_alt') {
      if ($value == '') {
        $parent = get_post_field( 'post_parent', $post_id);
        $title = get_post_field( 'post_title', $parent);
        return $title;
      }
  }
  

  return $value;
}



/*
  Plugin Name: Disable Responsive Images Complete
  Plugin URI: https://perishablepress.com/disable-wordpress-responsive-images/
  Description: Completely disables WP responsive images
  Tags: responsive, images, responsive images, disable, srcset
  Author: Jeff Starr
  Author URI: https://plugin-planet.com/
  Donate link: https://monzillamedia.com/donate.html
  Contributors: specialk
  Requires at least: 4.6
  Tested up to: 6.1
  Stable tag: 2.4.1
  Version: 2.4.1
  Requires PHP: 5.6.20
  License: GPL v2 or later

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 
  2 of the License, or (at your option) any later version.
  
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  
  You should have received a copy of the GNU General Public License
  with this program. If not, visit: https://www.gnu.org/licenses/
  
  Copyright 2022 Monzilla Media. All rights reserved.
  
*/
//
if (!defined('ABSPATH')) exit;

// disable srcset on frontend
function disable_wp_responsive_images() {
  
  return false;
  
}
add_filter('max_srcset_image_width', 'disable_wp_responsive_images');


// disable 768px image generation
function disable_wp_responsive_image_sizes($sizes) {
  
  unset($sizes['medium_large']);
  
  return $sizes;
  
}
add_filter('intermediate_image_sizes_advanced', 'disable_wp_responsive_image_sizes');


//for theme goia

add_filter('wp_calculate_image_srcset', 'disable_wp_responsive_images');
add_filter( 'max_srcset_image_width', 'disable_wp_responsive_images' );


// api them hinh anh
//upload nhiều
add_action( 'rest_api_init', function () {
  register_rest_route( 'inser_mutil_attactment_not_upload', '/set-image/' , array(
    'methods' => 'POST',
    'callback' => 'inser_mutil_attactment_not_upload',
  ) );
} );

//upload 1
add_action( 'rest_api_init', function () {
  register_rest_route( 'inser_attactment_not_upload', '/set-image/' , array(
    'methods' => 'POST',
    'callback' => 'inser_attactment_not_upload',
  ) );
} );

//

//api theem nhieu hinh vao db
function inser_mutil_attactment_not_upload($data){
    global $wpdb;
    $images_return = [];
    $product_images_json = $data['product_images_json'];
    //$path_image = $data['path_image'];//auto.morebundle.com/storage/products/
    $image_sizes = wp_get_registered_image_subsizes();
    $datetime = date('Y-m-d H:i:s');

    foreach ($product_images_json['link'] as $key => $img) {
      
        $value_ = $img;
        $name = basename($value_);
        $nxx = explode('.',$name);
        //check exxits
        $sql = "SELECT wp_posts.ID as 'id' FROM wp_posts where post_name = '{$nxx[0]}' and post_type='attachment'";
        $ress_image = $wpdb->get_results($sql);
       // return $ress_image;
        if ($ress_image) {
          $images_return[] = ['id' => $ress_image[0]['id'] ];
        }else{
          $link = $img;
          $info_image = $product_images_json['info_image'][$key];
              
          $data_post = [
              'post_author' => 1,
              'post_title'  => $nxx[0],
              'post_name'  => $nxx[0],
              'guid'  =>  $link, 
              'post_type' =>  'attachment',
              'post_status' => 'inherit',
              'ping_status' => 'closed',
              'post_date' => $datetime,
              'post_date_gmt' => $datetime,
              'post_modified' => $datetime,
              'post_modified_gmt' => $datetime,
              'post_mime_type' => $info_image['mime']
          ];

          $wpdb->insert('wp_posts',$data_post);
          $attach_id = $wpdb->insert_id;
          //return $attach_id;
          /*$attach_id = wp_insert_attachment( $data_post , $name);
          */
          $images_return[] = ['id' => $wpdb->insert_id];
          foreach ( $image_sizes as $f_n => $image_size) {
        
              if ($image_size['crop'] == true) {//cắt cứng
                  $sizes[$f_n] = [
                      'file' => $name,
                      'width' => $image_size['width'],
                      'height' => $image_size['height'],
                      'mime-type' => $info_image['mime'],
                  ];
              }else{
                  $percentChange =$image_size['width'] / $info_image[0];
                  $newHeight = round( ( $percentChange * $info_image[1] ) );
                  $sizes[$f_n] = [
                      'file' => $name,
                      'width' => $image_size['width'],
                      'height' => (int)$newHeight,
                      'mime-type' => $info_image['mime'],
                  ];
              }
                  
          }

         // $_wp_attached_file = 'products/'.$product_images_json['guid'][$key];

          $data_postmeta[] = [
            'post_id' => $attach_id,
            'meta_key' => '_wp_attached_file',
            'meta_value' => 'products/'.$product_images_json['guid'][$key],
          ];


          $array_meta_attachment = [
              'width' => $info_image[0],
              'height' => $info_image[1] ,
              'file' => $name ,
              'sizes' => $sizes,
              'filesize' => $info_image['fileSize'],
          ];

          $data_postmeta[] = [
            'post_id' => $attach_id,
            'meta_key' => '_wp_attachment_metadata',
            'meta_value' => serialize($array_meta_attachment),
          ];

          //$_wp_attachment_metadata[] = serialize($array_meta_attachment);
          


        }
        // Usage
      
    }
    if (isset($data_postmeta )) {
       $table = 'wp_postmeta';
       insert_multiple_rows( $table, $data_postmeta );
    }
    
    return $images_return;

}
 
function inser_attactment_not_upload($data){
    
    $data_post = $data['data_post'];
    $filename =  $data['filename'];
    $_wp_attached_file =  $data['_wp_attached_file'];
    $info_image = $data['info_image'];
    $attach_id = wp_insert_attachment( $data_post , $filename );
   // $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
    $image_sizes = wp_get_registered_image_subsizes();
    //var_dump($image_sizes);die;
    foreach ( $image_sizes as $f_n => $image_size) {
        
        if ($image_size['crop'] == true) {//cắt cứng
            $sizes[$f_n] = [
                'file' => $filename,
                'width' => $image_size['width'],
                'height' => $image_size['height'],
                'mime-type' => $info_image['post_mime_type'],
            ];
        }else{
            $percentChange =$image_size['width'] / $info_image[0];
            $newHeight = round( ( $percentChange * $info_image[1] ) );
            $sizes[$f_n] = [
                'file' => $filename,
                'width' => $image_size['width'],
                'height' => (int)$newHeight,
                'mime-type' => $info_image['post_mime_type'],
            ];
        }
            
    }
    update_post_meta( $attach_id , '_wp_attached_file', $_wp_attached_file );
    $array_meta_attachment = [
        'width' => $info_image[0],
        'height' => $info_image[1] ,
        'file' => $filename ,
        'sizes' => $sizes,
        'filesize' => $info_image['fileSize'],
    ];
    update_post_meta( $attach_id , '_wp_attachment_metadata', $array_meta_attachment );
    //$data = 
    return get_post( $attach_id ); 
    //wp_update_attachment_metadata( $attach_id, $attach_data );
    
    //return $image_sizes;
}


//dang ký api add taxonomy multi

add_action( 'rest_api_init', function () {
  register_rest_route( 'more_add_toxonomy', '/toxonomy/' , array(
    'methods' => 'POST',
    'callback' => 'more_add_toxonomy',
  ) );
} );

function more_add_toxonomy($data){
  $taxonomy = $data['taxonomy'];
  $terms = $data['terms'];
  $return_term_id = [];
  foreach ($terms as $key => $value) {

    if ($key == 0) {
      $term_id_parent =  wp_insert_term($value,$taxonomy);
      //error_log(print_r($term_id_parent));
      if (is_object($term_id_parent)) {
        $return_term_id[]['id'] = $term_id_parent->error_data['term_exists'];  
        $term_id_parent_id = $term_id_parent->error_data['term_exists'];
      }else{
        //error_log(print_r($term_id_parent, true));
        $return_term_id[]['id'] = $term_id_parent['term_id'];
        $term_id_parent_id = $term_id_parent['term_id'];
      }


    }else{
      $term_id = wp_insert_term($value,$taxonomy,['parent' => $term_id_parent_id ]);

      if (is_object($term_id)) {
        $return_term_id[]['id'] = $term_id->error_data['term_exists'];  
      }else{
        //error_log(print_r($term_id_parent, true));
        $return_term_id[]['id']  = $term_id['term_id'];
      }
      
    }

  }

  return $return_term_id;
}


//api tạo sản phẩm
add_action( 'rest_api_init', function () {
  register_rest_route( 'more_add_products', '/products/' , array(
    'methods' => 'POST',
    'callback' => 'more_add_products',
  ) );
} );

function more_add_products( $data ) {

    $rest_request = $data['product'];

    $post_id = wc_get_product_id_by_sku($rest_request['sku']);

    if ($post_id) {
        if (isset($data['product_update'])) {
          // update product
        }
        return $post_id;
        die;
    }
    $is_tm = $data['is_tm'];
    $post_name = $data['post_name'];
    $products_controler = new WC_REST_Products_Controller();
    $variations_controler = new WC_REST_Product_Variations_Controller();
    $wp_rest_request = new WP_REST_Request( 'POST' );
    $wp_rest_request->set_body_params( $rest_request );
    $res = $products_controler->create_item( $wp_rest_request );
    $res = $res->data;
    // The created product must have variations
    // If it doesn't, it's the new WC3+ API which forces us to build those manually

    if ( $rest_request['variations'] != NULL ) {
        
        foreach ( $rest_request['variations'] as $variation ) {
            $wp_rest_request = new WP_REST_Request( 'POST' );

            $variation['product_id'] = $res['id'];
             
            $wp_rest_request->set_body_params( $variation );
            $new_variation = $variations_controler->create_item( $wp_rest_request );
            $res['variations'][] = $new_variation->data;
        }
    }
    global $wpdb;
    if ($is_tm) {
      
      $sql = "UPDATE wp_posts SET is_tm = 1 WHERE ID = {$res['id']}";
      $wpdb->query($sql);
    }
    $slug = $res['slug'];
    //$post_name = $data['product']['post_name'];
    $sql = "UPDATE wp_posts SET post_name = REPLACE(post_name,'{$slug}', '{$post_name}')  WHERE  ID = {$res['id']}";
    $xxx = $wpdb->query($sql);
    
    $sql = "UPDATE wp_posts SET post_name = REPLACE(post_name,'{$slug}', '{$post_name}')  WHERE  post_parent = {$res['id']}";
    $xxx = $wpdb->query($sql);
    
    $sql = "UPDATE wp_yoast_indexable SET permalink = REPLACE(permalink,'{$slug}', '{$post_name}')  WHERE object_id = {$res['id']}";
    $xxx = $wpdb->query($sql);
    
    
   // $sql = "UPDATE wp_yoast_indexable SET permalink = '{$post_name}' WHERE object_id = {$res['id']}";
   // $xxx = $wpdb->query($sql);
    
    
    //return $res;
    return $res['id'];
}



/*thêm hình ảnh safe payment vào bên dưới product detail*/
add_action( 'woocommerce_share', 'below_single_product_summary', 20 );
function below_single_product_summary() {
  $img_secure = get_option('change_url_image_image_secure_payment') != '' ? get_option('change_url_image_image_secure_payment') : '';

  if($img_secure){
    echo '<img width="100%" src="'.$img_secure.'" style="margin-bottom:15px;order: 15;margin-top: 15px;" alt="secure payment">';
  }
  //echo '<img src="'.get_template_directory_uri().'/assets/img/psafe-checkout-new.png" style="margin-bottom:15px;order: 15;margin-top: 15px;" alt="secure payment">';
  //echo the_widget('below_content_product');
}




//
function insert_multiple_rows( $table, $request ) {
    global $wpdb;
    $column_keys   = '';
    $column_values = '';
    $sql           = '';
    $last_key      = array_key_last( $request );
    $first_key     = array_key_first( $request );
    foreach ( $request as $k => $value ) {
        $keys = array_keys( $value );

        // Prepare column keys & values.
        foreach ( $keys as $v ) {
            $column_keys   .= sanitize_key( $v ) . ',';
            $sanitize_value = sanitize_text_field( $value[ $v ] );
            $column_values .= is_numeric( $sanitize_value ) ? $sanitize_value . ',' : "'$sanitize_value'" . ',';
        }
        // Trim trailing comma.
        $column_keys   = rtrim( $column_keys, ',' );
        $column_values = rtrim( $column_values, ',' );
        if ( $first_key === $k ) {
            $sql .= "INSERT INTO {$table} ($column_keys) VALUES ($column_values),";
        } elseif ( $last_key == $k ) {
            $sql .= "($column_values)";
        } else {
            $sql .= "($column_values),";
        }

        // Reset keys & values to avoid duplication.
        $column_keys   = '';
        $column_values = '';
    }
    return $wpdb->query( $sql );
}

// thêm description vào trang product
add_action('wp_head', 'add_short_description_to_meta_description');

function add_short_description_to_meta_description() {
   // var_dump(212121);
    //var_dump(is_singular('product'));
    if (is_singular('product')) {
        global $post;
        $short_description = $post->post_excerpt;
        if (!empty($short_description)) {
            echo '<meta name="description" content="' . esc_attr($short_description) . '">';
        }else{
            $short_description = $post->post_title;
            //if (!empty($short_description)) {
            echo '<meta name="description" content="'. str_replace( '.com','' , parse_url( get_site_url(), PHP_URL_HOST )). ' - ' . esc_attr($short_description) . '">';
            //}
        }
    }
}

//only goia theme for cart mini change url image
function gioia_elated_generate_thumbnail( $attach_id = null, $attach_url = null, $width = null, $height = null, $crop = true ) {
  //  var_dump(212121);
		//is attachment id empty?
		if ( empty( $attach_id ) ) {
			//get attachment id from attachment url
			$attach_id = gioia_elated_get_attachment_id_from_url( $attach_url );
		}
//		var_dump($attach_id);
		if ( ! empty( $attach_id ) || ! empty( $attach_url ) ) {
			//$img_info = gioia_elated_resize_image( $attach_id, $attach_url, $width, $height, $crop );
			
			$img_url = wp_get_attachment_url( $attach_id );
			
		//	var_dump($img_url);
			
			$path_image = explode('wp-content/uploads', $img_url);
            
            
            if ( ! file_exists( ABSPATH .'wp-content/uploads'.end($path_image) )   ) {
                $img_url = 'https://cdn.'.parse_url( get_site_url(), PHP_URL_HOST ).'/image.php?src='.end($path_image).'&w=50&h=50&zc=1&a=c';
            }
 //           var_dump(111);
			$img_info['img_url']  = $img_url;
			$img_info['img_width']  = 50;
			$img_info['img_height']  = 50;
			
			$img_alt  = ! empty( $attach_id ) ? get_post_meta( $attach_id, '_wp_attachment_image_alt', true ) : '';
			
			if ( is_array( $img_info ) && count( $img_info ) ) {
				return '<img src="' . esc_url( $img_info['img_url'] ) . '" alt="' . esc_attr( $img_alt ) . '" width="' . esc_attr( $img_info['img_width'] ) . '" height="' . esc_attr( $img_info['img_height'] ) . '" />';
			}
		}
		
		return '';
}







//thay đổi structure của image hình ảnh
add_filter( 'woocommerce_structured_data_product', 'custom_woocommerce_structured_data_product');

function custom_woocommerce_structured_data_product ( $markup ) {
	if (isset($markup['image'])) {
        //var_dump($data['product']);
        $path_image = str_replace('https://'.parse_url( get_site_url(), PHP_URL_HOST ).'/wp-content/uploads','',$markup['image']);
                        
                        
        $path_image = str_replace('http://'.parse_url( get_site_url(), PHP_URL_HOST ).'/wp-content/uploads','',$path_image);
        
        $im_tem = explode('/',$path_image) ;

        $im_temp = array_map("add_string", $im_tem);
       // var_dump($im_temp);die;
        $image_return = 'https://cdn.'.parse_url( get_site_url(), PHP_URL_HOST ).'/image.php?src='.implode('/',$im_temp);
    
        $markup['image'] = $image_return;
        //$data['image'] = $image_return;
        // code...
    }
	return $markup;
}