<?php

class customJsonImportPlugin
{
    function __construct($path_for_plugin, $database_table)
    {
        $this->irgCustomImportPath = $path_for_plugin;
        $this->irgCustomImportDatabaseTable = $database_table;

        add_action('admin_menu', array($this, 'irgCustomImportJson_init'));
        add_action('admin_enqueue_scripts', array($this, 'irgCustomImportJson_admin_style'));
        add_action('init', array($this, 'irgCustomImportJson_create_custom_post'));
        add_action('admin_init', array($this, 'irgCustomImportJson_extra_fields_for_product'));
        add_action('save_post', array($this, 'irgCustomImportJson_add_extra_fields_for_product'), 10, 2);
        add_filter('template_include', array($this, 'irgCustomImportJson_include_template_function'), 1);
        add_action('wp_ajax_irgCustomImportJson_parse_json_action', array($this, 'irgCustomImportJson_parse_json'));
        add_action('rest_api_init', array($this, 'irgCustomImportJson_register_api_fields'));

        add_action('wp_enqueue_scripts', array($this, 'irgCustomImportJson_scripts'));

        add_shortcode("display_custom_products", array($this, 'irgCustomImportJson_add_shortcode'));
    }

    function __destruct()
    {

    }

    /* Wp-Admin Initialisation */
    public function irgCustomImportJson_init()
    {
        add_menu_page('Json Import', 'Json Import', 'manage_options', 'jsonimport', array($this, 'irgCustomImportJson_include_admin_page'));
    }

    public function irgCustomImportJson_include_admin_page()
    {
        include($this->irgCustomImportPath . "templates/irgcustomimportjson-admin.php");
    }

    public function irgCustomImportJson_create_custom_post()
    {
        register_post_type('product',
            array(
                'labels' => array(
                    'name' => 'Products',
                    'singular_name' => 'Product',
                    'add_new' => 'Add New',
                    'add_new_item' => 'Add New',
                    'edit' => 'Edit',
                    'edit_item' => 'Edit',
                    'new_item' => 'New Product',
                    'view' => 'View',
                    'view_item' => 'View Product',
                    'search_items' => 'Search Products',
                    'not_found' => 'No products found',
                    'not_found_in_trash' => 'No products found in Trash',
                    'parent' => 'Parent Products'
                ),

                'public' => true,
                'menu_position' => 6,
                'show_in_rest' => true,
                'rest_base' => 'product-api',
                'rest_controller_class' => 'WP_REST_Posts_Controller',
                'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
                'taxonomies' => array('category'),
                'menu_icon' => plugins_url('irgcustomimportjson/images/image.png'),
                'has_archive' => true
            )
        );
    }


    public function irgCustomImportJson_extra_fields_for_product()
    {
        add_meta_box('extra_fields_for_product_meta_box',
            'Extra info',
            array($this, 'irgCustomImportJson_display_extra_fields_for_product'),
            'product', 'normal', 'high'
        );
    }

    public function irgCustomImportJson_display_extra_fields_for_product($page)
    {
        ?>
        <table>
            <tr>
                <td style="width: 150px">Price</td>
                <td><input type="text" size="100" name="price"
                           value="<?php echo esc_html(get_post_meta($page->ID, 'price', true)); ?>"/></td>
            </tr>
            <tr>
                <td style="width: 150px">Currency</td>
                <td><input type="text" size="100" name="currency"
                           value="<?php echo esc_html(get_post_meta($page->ID, 'currency', true)); ?>"/></td>
            </tr>
            <tr>
                <td style="width: 150px">Brand</td>
                <td><input type="text" size="100" name="brand"
                           value="<?php echo esc_html(get_post_meta($page->ID, 'brand', true)); ?>"/></td>
            </tr>
            <tr>
                <td style="width: 150px">Delivery costs</td>
                <td><input type="text" size="100" name="deliveryCosts"
                           value="<?php echo esc_html(get_post_meta($page->ID, 'deliveryCosts', true)); ?>"/></td>
            </tr>

            <tr>
                <td style="width: 150px">Delivery time</td>
                <td><input type="text" size="100" name="deliveryTime"
                           value="<?php echo esc_html(get_post_meta($page->ID, 'deliveryTime', true)); ?>"/></td>
            </tr>
            <tr>
                <td style="width: 150px">Gender</td>
                <td><input type="text" size="100" name="gender"
                           value="<?php echo esc_html(get_post_meta($page->ID, 'gender', true)); ?>"/></td>
            </tr>

            <tr>
                <td style="width: 150px">Size</td>
                <td><input type="text" size="100" name="size"
                           value="<?php echo esc_html(get_post_meta($page->ID, 'size', true)); ?>"/></td>
            </tr>

            <tr>
                <td style="width: 150px">Source ID</td>
                <td><input type="text" size="100" name="source_id"
                           value="<?php echo esc_html(get_post_meta($page->ID, 'source_id', true)); ?>"/></td>
            </tr>

            <tr>
                <td style="width: 150px">Source url</td>
                <td><input type="text" size="100" name="URL"
                           value="<?php echo esc_html(get_post_meta($page->ID, 'URL', true)); ?>"/></td>
            </tr>
        </table>

        <?php
    }

    public function irgCustomImportJson_add_extra_fields_for_product($page_id, $page)
    {

        if ($page->post_type == 'product') {
            // Store data in post meta table if present in post data

            if (isset($_POST['price']) && $_POST['price'] != '') {
                update_post_meta($page_id, 'price', $_POST['price']);
            }

            if (isset($_POST['currency']) && $_POST['currency'] != '') {
                update_post_meta($page_id, 'currency', $_POST['currency']);
            }

            if (isset($_POST['brand']) && $_POST['brand'] != '') {
                update_post_meta($page_id, 'brand', $_POST['brand']);
            }

            if (isset($_POST['deliveryCosts']) && $_POST['deliveryCosts'] != '') {
                update_post_meta($page_id, 'deliveryCosts', $_POST['deliveryCosts']);
            }

            if (isset($_POST['deliveryTime']) && $_POST['deliveryTime'] != '') {
                update_post_meta($page_id, 'deliveryTime', $_POST['deliveryTime']);
            }

            if (isset($_POST['gender']) && $_POST['gender'] != '') {
                update_post_meta($page_id, 'gender', $_POST['gender']);
            }

            if (isset($_POST['size']) && $_POST['size'] != '') {
                update_post_meta($page_id, 'size', $_POST['size']);
            }

            if (isset($_POST['source_id']) && $_POST['source_id'] != '') {
                update_post_meta($page_id, 'source_id', $_POST['source_id']);
            }

            if (isset($_POST['URL']) && $_POST['URL'] != '') {
                update_post_meta($page_id, 'URL', $_POST['URL']);
            }
        }
    }

    public function irgCustomImportJson_admin_style()
    {
        wp_enqueue_style('irgCustonImportJson-admin-styles', plugins_url('irgcustomimportjson/css/irgcustomimportjson-admin.css'));
        wp_enqueue_script('irgCustonImportJson-admin-scripts', plugins_url('irgcustomimportjson/js/irgcustomimportjson-admin.js'));
    }

    public function irgCustomImportJson_include_template_function($template_path)
    {
        if (get_post_type() == 'product') {
            if (is_single()) {
                // checks if the file exists in the theme first,
                // otherwise serve the file from the plugin
                if ($theme_file = locate_template(array('single-product.php'))) {
                    $template_path = $theme_file;
                } else {
                    $template_path = $this->irgCustomImportPath . 'templates/single-product.php';
                }
            }
        }
        return $template_path;
    }


    /* Wp-admin Functions */

    public function irgCustomImportJson_show_past_parsed()
    {
        global $wpdb;
        $mytable = $wpdb->prefix . $this->irgCustomImportDatabaseTable;
        $results = $wpdb->get_results("SELECT * FROM `$mytable` ORDER BY `id` DESC", ARRAY_A);
        return $results;
    }

    public function irgCustomImportJson_parse_json()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->irgCustomImportDatabaseTable;
        // The $_REQUEST contains all the data sent via ajax
        if (isset($_REQUEST)) {

            $jsonurl = $_REQUEST['jsonurl'];

            $wpdb->query("INSERT INTO `$table_name` (`id`,`feedurl`) VALUES ('NULL','$jsonurl')");

            $content = $this->irgCustomImportJson_get_data_from_remote_url($jsonurl);
            $products = json_decode($content, true);

            foreach ($products['products'] as $product) {

                $title = $product['name'];

                $description = $product['description'];
                $image = $product['images'][0];

                $this->irgCustomImportJson_add_product($title, $description, $image, $product);
            }
        }
        // Always die in functions echoing ajax content
        die();
    }

    public function irgCustomImportJson_get_data_from_remote_url($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36");

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_REFERER, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    public function irgCustomImportJson_add_product($title, $description, $image, $attributes = array())
    {
        $post_id = wp_insert_post(array(
            'post_type' => 'product',
            'post_title' => $title,
            'post_content' => $description,
            'post_status' => 'publish',
            'comment_status' => 'closed',   // if you prefer
            'ping_status' => 'closed',      // if you prefer
        ));

        $this->irgCustomImportJson_generate_featured_image($image, $post_id);

        if ($post_id) {
            // insert post meta
            foreach ($attributes as $proprety => $value) {
                if ($proprety != "name" && $proprety != "description" && $proprety != "images") {
                    switch ($proprety) {
                        case "ID":
                            add_post_meta($post_id, "source_id", $value);
                            break;

                        case "price":
                            add_post_meta($post_id, "price", $value['amount']);
                            add_post_meta($post_id, "currency", $value['currency']);
                            break;

                        case "properties":
                            foreach ($value as $p => $v) {
                                add_post_meta($post_id, $p, $v[0]);
                            }
                            break;

                        case "categories":
                            $category_ids = array();
                            foreach ($value as $p => $v) {
                                $catid = get_cat_ID($v);

                                if ($catid == 0) {
                                    $catid = wp_insert_term($v, 'category');
                                    $category_ids[] = $catid;

                                } else {
                                    $category_ids[] = $catid;
                                }
                            }

                            wp_set_post_categories($post_id, $category_ids, true);
                            break;

                        case "variations":
                            //nothing
                            break;

                        default:
                            add_post_meta($post_id, $proprety, $value);
                    }
                }

            }


        }
    }

    public function irgCustomImportJson_generate_featured_image($image_url, $post_id, $desc = "")
    {
        // Set variables for storage, fix file filename for query strings.
        preg_match('/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $image_url, $matches);
        if (!$matches) {
            return new WP_Error('image_sideload_failed', __('Invalid image URL'));
        }

        $file_array = array();
        $file_array['name'] = basename($matches[0]);

        // Download file to temp location.
        $file_array['tmp_name'] = download_url($image_url);

        // If error storing temporarily, return the error.
        if (is_wp_error($file_array['tmp_name'])) {
            return $file_array['tmp_name'];
        }

        // Do the validation and storage stuff.
        $id = media_handle_sideload($file_array, $post_id, $desc);

        // If error storing permanently, unlink.
        if (is_wp_error($id)) {
            @unlink($file_array['tmp_name']);
            return $id;
        }
        return set_post_thumbnail($post_id, $id);
    }

    /* Front end functions */
    public function slug_get_post_meta_cb($object, $field_name, $request)
    {
        return get_post_meta($object['id'], $field_name);
    }

    public function slug_update_post_meta_cb($value, $object, $field_name)
    {
        return update_post_meta($object['id'], $field_name, $value);
    }

    public function irgCustomImportJson_register_api_fields()
    {
        register_rest_field('product',
            'price',
            array(
                'get_callback' => array($this, 'slug_get_post_meta_cb'),

                'schema' => null,
            )

        );

        register_rest_field('product',
            'currency',
            array(
                'get_callback' => array($this, 'slug_get_post_meta_cb'),

                'schema' => null,
            )

        );

        register_rest_field('product',
            'brand',
            array(
                'get_callback' => array($this, 'slug_get_post_meta_cb'),

                'schema' => null,
            )

        );

        register_rest_field('product',
            'better_featured_image',
            array(
                'get_callback' => array($this, 'rest_api_featured_images_get_field'),
                'schema' => null,
            )
        );
    }

    public function rest_api_featured_images_get_field($object, $field_name, $request)
    {
        $feat_img_array = wp_get_attachment_image_src(
            $object['featured_media'], // Image attachment ID
            'large',  // Size.  Ex. "thumbnail", "large", "full", etc..
            true // Whether the image should be treated as an icon.
        );
        return $feat_img_array[0];
    }

    public function irgCustomImportJson_scripts()
    {
        wp_enqueue_style('style-irgCustomImportJson', plugins_url('irgcustomimportjson/css/irgcustomimportjson.css'), array(), time(), 'all');

        wp_enqueue_script('js-irgCustomImportJson', plugins_url('irgcustomimportjson/js/irgcustomimportjson.js'), array('jquery'), time(), 'all');
    }

    /* add shortcode for adding placeholders */

    public function irgCustomImportJson_add_shortcode()
    {
        $html = '<div id="ProductList"></div>';
        $url = get_site_url();
        $url .= '/wp-json/wp/v2/product-api/';

        $html .= '<div class="pagination-wrap"><div class="prev" id="product-prev">Prev</div> <div class="next" id="product-next">Next</div></div> <script>var urltoparse="' . $url . '"; var pagenr = 1;</script>';

        return $html;
    }
}