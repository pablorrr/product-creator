<?php


namespace ProductCreator\ajax;


use ProductCreator\libraries\ProductCreator;
use ProductCreator\models\ProductCreator_Model_CreateProd;

class ProductCreator_Ajax
{
    private $model;

    public function __construct()
    {
        $this->model = new ProductCreator_Model_CreateProd();
        add_action('wp_enqueue_scripts', array($this, 'ajax_js'));
        add_action('wp_ajax_set_form_one', array($this, 'set_form_one'));    //execute when wp logged in
        add_action('wp_ajax_nopriv_set_form_one', array($this, 'set_form_one')); //execute when logged out
        add_action('wp_ajax_set_form_two', array($this, 'set_form_two'));    //execute when wp logged in
        add_action('wp_ajax_nopriv_set_form_two', array($this, 'set_form_two')); //execute when logged out
    }


    public function ajax_js()
    {
        if (function_exists('is_woocommerce')): if (is_woocommerce()): ?>
            <?php wp_enqueue_script('script_handle', ProductCreator::$plugin_uri . 'public/js/ajax.js', array('jquery'));
            wp_localize_script('script_handle', 'cpm_object_first', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('productcreator_first')
            ));
            wp_localize_script('script_handle', 'cpm_object_second', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('productcreator_second')
            )); ?>
        <?php endif;
        endif;
    }

    public function set_form_one()
    {
        if (!wp_verify_nonce($_REQUEST['nonce'], 'productcreator_first')) {
            wp_send_json_error();
        }
//todo wpr isset oraz walidacje preg match
        $prod_name = $_POST['prod_name'];
        $col_name = $_POST['col_name'];

        //todo zmiana nazwy model n na inna oraz uwzglednienei jej jako zminnej czlokowskiej
        $this->model->saveProdNameOpt('prod_name_opt', $prod_name);
        $this->model->saveColNameOpt('col_name_opt', $col_name);
        $singleProduct = $this->model->getSingleproduct(get_option('prod_name_opt'), get_option('col_name_opt'));
        $response = array('prod_name' => get_option('prod_name_opt'), 'col_name' => get_option('col_name_opt'),
            'form_first' => $this->firstForm($this->model->getSingleAttr($singleProduct)),
            'nonce' => wp_create_nonce('productcreator_first'));
        wp_send_json_success($response);
        die();
    }


    public function firstForm($sngleProdt)
    {
        if (!empty($sngleProdt)) {
            $form_first = '<form id="ajax-first-form" action="" method="post" enctype="multipart/form-data">';
            $form_first .= '<label for="attr-val">Please choose product property</label>';
            $form_first .= ' <select id="attr-val" name="attr_val">';
            foreach ($sngleProdt as $single) {
                $form_first .= '<option value="' . esc_attr($single) . '">' . $single . '</option>';
            }
            $form_first .= '</select >';

            $form_first .= '</form >';
        }
        return $form_first;

    }


    //second form creation


    public function set_form_two()
    {
        if (!wp_verify_nonce($_REQUEST['nonce'], 'productcreator_second')) {
            wp_send_json_error();
        }
//todo wpr isset oraz walidacje preg match
        $attr_val = $_POST['attr_val'];

        $this->model->saveAttrName('attr_val_opt', $attr_val);

        $response = array('attr_val' => get_option('attr_val_opt'),
            'form_second' => $this->secondForm($this->model->printProdattrForm()),
            'nonce' => wp_create_nonce('productcreator_second'));
        wp_send_json_success($response);
        die();
    }

    public function secondForm($arrMerg)
    {

        if (!empty($arrMerg)) {
            $form_second = '<h4>Available products with properties</h4>';
            $form_second .= '<form id="ajax-second-form" action="" method="post" enctype="multipart/form-data">';

            foreach ($arrMerg as $col => $value) {
                $form_second .= '<label for="' . esc_attr($col) . '">' . esc_attr($col) . '</label>';
                $form_second .= '<select id="' . $col . '" name="' . $col . '">';
                if (is_array($value)) {
                    foreach ($value as $single) {
                        $form_second .= '<option value="' . esc_attr((string)$single) . '">' . (string)$single . '</option>';
                    }
                }
                if (!is_array($value)) {
                    $form_second .= '<option value="' . esc_attr($value) . '">' . $value . '</option>';
                }

                $form_second .= '</select>';
            }
        }
        $form_second .= '</br>';
        $form_second .= '<p><span class="label label-info">If the product does not show up, please refresh the page</span></p>';
        $form_second .= '</br>';
        $form_second .= '<input type="submit" name="submit" class="button-secondary btn-info" value="Please click to create product">';
        $form_second .= '</form>';

        return $form_second;

    }
}