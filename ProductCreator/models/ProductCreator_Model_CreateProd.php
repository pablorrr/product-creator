<?php
/**
 * Creates products and save to database of WP/WC
 *
 */

namespace ProductCreator\models;


class ProductCreator_Model_CreateProd extends ProductCreator_Model
{
    //uzyskiwanie dostepu do elementow ktre juz zostale upubicznione w bazie danych
    function getProducts()
    {
        $table_name = $this->getTableName();

        $sql = "SELECT * FROM {$table_name}";
        return $this->wpdb->get_results($sql);
    }

    function getColName()
    {
        $myProducts = $this->getProducts();

        if (!empty($myProducts)) {

            foreach ($myProducts as $snigleObj) {
                $snigleObj = (get_object_vars($snigleObj));
                $colArr = [];
                foreach ($snigleObj as $key => $val) {
                    if ($key !== 'id' && $key !== 'prod_name')
                        array_push($colArr, $key);
                }
            }

        }
        return $colArr;
    }

//TODO: MODIF ABY BYLO BEZPIECZNIEJ!!!!- RZUTOWANIE FORMATU DANYCH
    function getSingleproduct($prod_name, $col_name)
    {
        $table_name = $this->getTableName();
        $sql = "SELECT {$col_name} FROM {$table_name} WHERE prod_name = '{$prod_name}'";

        return $this->wpdb->get_results($sql);

    }

    function getAttrRow($prodName, $colName, $attr)
    {
        $table_name = $this->getTableName();

        $sql = "SELECT * FROM {$table_name} WHERE prod_name = '{$prodName}' AND {$colName} LIKE '%{$attr}%'";

        return $this->wpdb->get_results($sql);

    }

    function saveColNameOpt($option_name, $new_value)
    {
        if (get_option($option_name) !== false && !empty($_POST['col_name'])) {

            // The option already exists, so update it.
            update_option($option_name, $new_value);

        } else {

            // The option hasn't been created yet, so add it with $autoload set to 'no'.
            $deprecated = null;
            $autoload = 'yes';
            add_option($option_name, $new_value, $deprecated, $autoload);
        }

    }

    function saveProdNameOpt($option_name, $new_value)
    {
        if (get_option($option_name) !== false && !empty($_POST['prod_name'])) {

            // The option already exists, so update it.
            update_option($option_name, $new_value);

        } else {

            // The option hasn't been created yet, so add it with $autoload set to 'no'.
            $deprecated = null;
            $autoload = 'yes';
            add_option($option_name, $new_value, $deprecated, $autoload);
        }


    }


    function saveAttrName($option_name, $new_value)
    {
        if (get_option($option_name) !== false && !empty($_POST['attr_val'])) {

            // The option already exists, so update it.
            update_option($option_name, $new_value);

        } else {

            // The option hasn't been created yet, so add it with $autoload set to 'no'.
            $deprecated = null;
            $autoload = 'yes';
            add_option($option_name, $new_value, $deprecated, $autoload);
        }

    }

    function saveAttrVal($nameA, $valA)
    {
        if (get_option($nameA) !== false && !empty($valA)) {

            // The option already exists, so update it.
            update_option($nameA, $valA);

        } else {

            // The option hasn't been created yet, so add it with $autoload set to 'no'.
            $deprecated = null;
            $autoload = 'yes';
            add_option($nameA, $valA, $deprecated, $autoload);
        }

    }

    function getSingleAttr($singlProd)
    {
        foreach ($singlProd as $single) {

            $objVar = get_object_vars($single);
            foreach ($objVar as $key => $val) {
                $newArr = $val;
            }
        }

        return $newArr = explode(',', $newArr);

    }

    function getRw($arrObj)
    {
        //transform object array into simple array without object
        foreach ($arrObj as $obj) {

            $newArrobj = (array)$obj;

        }
        //convert array values into array by delimeters
        foreach ($newArrobj as $key => $val) {
            if ($key !== 'id' && $key !== 'prod_name')
                $newArrobj[$key] = explode(',', $val);

        }
        return $newArrobj;

    }

    function PrinttableProdwithProp()
    {

        if (get_option('prod_name_opt') && get_option('col_name_opt') && get_option('attr_val_opt')) {

            $getRow = $this->getAttrRow(get_option('prod_name_opt'), get_option('col_name_opt'), get_option('attr_val_opt'));
            $newArr = [];
            foreach ($this->getRw($getRow) as $single) {

                if (is_array($single)) {

                    $key = array_search(get_option('attr_val_opt'), $single);

                    foreach ($this->getRw($getRow) as $sngl) {
                        if (is_array($sngl)) {
                            if (array_key_exists((string)$key, $sngl)) {
                                $newArr[] = array_push($newArr, $sngl[$key]);
                            }
                        }
                    }
                }
            }

            $lastArr = array();
            foreach ($newArr as $key => $val) {

                if ($key !== 0 && $key % 2 !== 0) {

                    unset($newArr[$key]);
                    $lastArr = array_values($newArr);
                }
            }
            return array_combine($this->getColName(), $lastArr);

        }

    }


    function printProdattrForm()
    {
        if (!empty($this->PrinttableProdwithProp())) {

            $expArr = array_map(function ($el) {
                return explode('|', $el);
            }, $this->PrinttableProdwithProp());
            $expArrr = array_filter(array_map(function ($ell) {
                if (count($ell) == 1) return implode('', $ell);
            }, $expArr));
            $nextArr = array_diff_key($expArr, $expArrr);

            return array_merge($nextArr, $expArrr);

        }
    }


    function prodCreatorcreateProd($postTable)
    {
        update_option('prod_name_opt', get_option('prod_name_opt') . '_' . (string)random_int(100, 999));
        foreach ($postTable as $nameAttr => $value) {

            if ($nameAttr !== 'submitAttr') {
                $this->saveAttrVal($nameAttr, $value);
            }
        }
        $attrArr = [];
        foreach ($postTable as $nameAttr => $value) {

            if ($nameAttr !== 'submitAttr') {

                $arr_to_replic = array(
                    'name' => $nameAttr, // parameter for custom attributes
                    'visible' => true, // default: false
                    'value' => get_option($nameAttr)
                );

                array_push($attrArr, $arr_to_replic);
            }
        }
        //get_page_by_title zwraca null jesli nie ma w bazie danych  prod o danej nazwie
        $product_name = get_page_by_title(get_option('prod_name_opt'), OBJECT, 'product');
        //TODO: PRZY PRODUCT ATTR TABLICE DODOAWAC PETLAFOREACH - LIKWODACJA METODY SAVE ATTR VAL POSLUGIWANIE SIE TYLKO TAB POST
        //TODO: STWORZYC ZUPELNIE NOWE POLE W TABELI HEIGHT, post content , post exerrpt,

        if ($product_name == null) {//dizla dla jednego produktu - nioe powduje duplikatu!!!
            $post_data = array(
                'post_title' => get_option('prod_name_opt'),
                'post_type' => 'product',
                'post_status' => 'publish',
                'post_content' => 'individual product',
                'post_excerpt' => 'individual product',
                'meta_input' => array(
                    '_width' => get_option('width'),
                    '_height' => get_option('height'),
                    '_weight' => get_option('weight'),
                    '_length' => get_option('length'),
                    '_visibility' => 'visible',//alt hidden visible
                    '_stock_status' => 'instock',
                    '_regular_price' => self::calculator(),                 // default null,
                    '_price' => self::calculator(),   //default null computed from calc
                    '_sku' => (string)random_int(100, 999),
                    '_product_attributes' => array(
                        $attrArr[0], $attrArr[1], $attrArr[2], $attrArr[3], $attrArr[4], $attrArr[5]
                    ),//prod attr
                    '_purchase_note' => '  ',
                    '_manage_stock  ' => 'yes'
                )
            );
            // create product
            wp_insert_post($post_data);


        }
    }


//todo: problem zwidocznoscia cen w ustwianiach  fix poprzez uzycie filtrow i meta patrz add new prod
    static function calculator()
    {
        $weight = get_option('weight') ? floatval(get_option('weight')) : null;
        $height = get_option('height') ? floatval(get_option('height')) : null;
        $volume = get_option('volume') ? floatval(get_option('volume')) : null;
        $lenght = get_option('length') ? floatval(get_option('length')) : null;//njprwd zly ENG
        $width = get_option('width') ? floatval(get_option('width')) : null;

        return ($weight * 4) + ($height * 2) + ($volume * 3) + ($lenght * 1) + ($width * 6);

    }
}