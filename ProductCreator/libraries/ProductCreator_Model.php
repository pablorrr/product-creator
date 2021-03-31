<?php


namespace ProductCreator\libraries;

use ProductCreator\controllers\ProductCreator_Controller_Pagination;
use ProductCreator\controllers\ProductCreator_Controller_Entry;


class ProductCreator_Model
{

    private $table_name = 'product_creator';
    protected $wpdb;


    function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    function getTableName()
    {
        return $this->wpdb->prefix . $this->table_name;
    }


    function createTable()
    {

        $table_name = $this->getTableName();
        //TODO: wymyslic ssytem gdzie tylko super admin ma  dostep do tej tabeli
        //Todo : w przyszlosci tweorzsenie usera db oraz bazy danych i tabeli wp


        $sql = "CREATE TABLE IF NOT EXISTS {$table_name}(
                id INT NOT NULL AUTO_INCREMENT,
                prod_name VARCHAR(255) DEFAULT NULL,
                height VARCHAR(255) DEFAULT NULL,
                weight VARCHAR(255) DEFAULT NULL,
                color VARCHAR(255) DEFAULT NULL,
                volume VARCHAR(255) DEFAULT NULL,
                scent VARCHAR(255) DEFAULT NULL,
                length VARCHAR(255) DEFAULT NULL,
                width VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY(id)
             )ENGINE=InnoDB DEFAULT CHARSET=utf8";


        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

    }


    function saveproductentry(ProductCreator_Controller_Entry $productentry)
    {

        $toSave = array(

            'prod_name' => $productentry->getField('prod_name'),
            'weight' => $productentry->getField('weight'),
            'height' => $productentry->getField('height'),
            'color' => $productentry->getField('color'),
            'volume' => $productentry->getField('volume'),
            'scent' => $productentry->getField('scent'),
            'length' => $productentry->getField('length'),
            'width' => $productentry->getField('width'),

        );


        $table_name = $this->getTableName();

        if ($productentry->hasId()) {//czy wpis ma id ( kolumne z wartoscia id)

            if ($this->wpdb->update($table_name, $toSave, array('id' => $productentry->getField('id')), null, '%d')) {
                return $productentry->getField('id');//uzyskaj dostep do pola gdzie kolumna nazywa sie id
            } else {
                return false;
            }

        } else {

            if ($this->wpdb->insert($table_name, $toSave)) {
                return $this->wpdb->insert_id;//odczytuje wartosc id nowego rekordu(wiersza)
            } else {
                return false;
            }

        }

    }


    function fetchRow($id)
    {
        $table_name = $this->getTableName();
        $sql = "SELECT * FROM {$table_name} WHERE id = %d";//gdzie id jest intgerem
        $prep = $this->wpdb->prepare($sql, $id);


        return $this->wpdb->get_row($prep);
    }

    //paginacja
    function getPagination($curr_page, $limit = 10, $order_by = 'id', $order_dir = 'asc')
    {//asc sortowanie rosnaco

        $curr_page = (int)$curr_page;//rzutowanie na int
        if ($curr_page < 1) {//dla pewnosci ze strona nie ejst zero
            $curr_page = 1;//to i tak ustaw ja na jeden
        }

        $limit = (int)$limit;// rzutowanie na int
        $order_by_opts = static::getOrderByOpts();
        $order_by = (!in_array($order_by, $order_by_opts)) ? 'id' : $order_by;
        $order_dir = in_array($order_dir, array('asc', 'desc')) ? $order_dir : 'asc';// asc rosnaco desc malejaco
        $offset = ($curr_page - 1) * $limit;

        $table_name = $this->getTableName();


        $count_sql = "SELECT COUNT(*) FROM {$table_name}";
        $total_count = $this->wpdb->get_var($count_sql);
        $last_page = ceil($total_count / $limit);
        $sql = "SELECT * FROM {$table_name} ORDER BY {$order_by} {$order_dir} LIMIT {$offset}, {$limit}";

        $Products_list = $this->wpdb->get_results($sql);

        $Pagination = new ProductCreator_Controller_Pagination($Products_list, $order_by, $order_dir, $limit, $total_count, $curr_page, $last_page);

        return $Pagination;
    }

    //usuwanie wiersza
    function deleteRow($id)
    {
        $id = (int)$id;

        $table_name = $this->getTableName();
        $sql = "DELETE FROM {$table_name} WHERE id = %d";
        $prep = $this->wpdb->prepare($sql, $id);

        return $this->wpdb->query($prep);
    }


    function bulkDelete(array $ids_list)
    {
        $ids_list = array_map('intval', $ids_list);
        $table_name = $this->getTableName();
        $ids_str = implode(',', $ids_list);
        $sql = "DELETE FROM {$table_name} WHERE id IN ({$ids_str})";

        return $this->wpdb->query($sql);
    }


    static function getOrderByOpts()
    {
        return array(
            'ID' => 'id',
            'product name' => 'prod_name',
            'weight' => 'weight',
            'height' => 'height',
            'amount' => 'amount',
            'color' => 'color',
            'volume' => 'volume',
            'scent' => 'scent',
            'length' => 'length',
            'width' => 'width'
        );
    }


}