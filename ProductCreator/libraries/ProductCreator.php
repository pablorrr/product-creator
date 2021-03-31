<?php
/*
 *
 * General class of  the plugin: admin notices , create  visual admin panel etc.
 */


namespace ProductCreator\libraries;

use ProductCreator\controllers\ProductCreator_Controller_Entry;
use ProductCreator\controllers\ProductCreator_Controller_FlashMsg;
use ProductCreator\controllers\ProductCreator_Controller_ErrorValidate;
use ProductCreator\ajax\ProductCreator_Ajax;
use ProductCreator\controllers\ProductCreator_Controller;


interface Main
{
    public function on_activate();

    public function admin_notice();

    public function createAdminMenu();

    public function getAdminPageUrl(array $params = array());

    public function printAdminPage();

    public function displayCalc();


}

final class ProductCreator implements Main
{

    public static $plugin_id = 'product-creator';// get and save  plugin option problem
    private $plugin_version = '1.0.0';// get and save  plugin option problem
    private $user_capability = 'manage_options';
    public $action_token = 'product-creator-hs-action';//posluzy do wpsolpracy z wpnonce fields WP
    private $pagination_limit = 3;//ograniczenie liczby wczytanych do 3
    private $model;
    private $plugin_path;
    public static $plugin_uri;
    private $flashMsg;
    private $request;
    private $errorValidate;
    private $ajax;
    private $helpTab;


    function __construct()
    {
        if (!function_exists('is_plugin_active'))
            require_once(ABSPATH . '/wp-admin/includes/plugin.php');

        if (!class_exists('woocommerce') && !is_plugin_active('woocommerce/woocommerce.php')) {
            add_action('admin_notices', array($this, 'admin_notice'));
        } else {
            $this->model = new ProductCreator_Model();
            $this->plugin_path = rtrim(plugin_dir_path(dirname(__DIR__)), '/') . DIRECTORY_SEPARATOR . 'product-creator.php';
            self::$plugin_uri = plugin_dir_url(dirname(__DIR__));
            register_activation_hook($this->plugin_path, array($this, 'on_activate'));
            add_action('admin_menu', array($this, 'createAdminMenu'));
            $this->flashMsg = new ProductCreator_Controller_FlashMsg();
            $this->errorValidate = new ProductCreator_Controller_ErrorValidate();
            $this->request = ProductCreator_Controller::getInstance();
            add_action('woocommerce_before_shop_loop', array($this, 'displayCalc'), 10);
            add_action('wp_loaded', array($this, 'add_custom_filter_callbacks'), 10);
            $this->ajax = new ProductCreator_Ajax();
            $this->helpTab = new ProductCreator_HelpTab();

        }
    }


    function custom_dynamic_regular_price($regular_price, $product)
    {
        if (empty($regular_price) || $regular_price == 0)
            return $product->get_price();
        else
            return $regular_price;
    }

    function add_custom_filter_callbacks()
    {
        add_filter('woocommerce_product_get_price', array($this, 'custom_dynamic_regular_price'), 10, 2);
        add_filter('woocommerce_product_variation_get_regular_price', array($this, 'custom_dynamic_regular_price'), 10, 2);
        add_filter('woocommerce_product_get_regular_price', array($this, 'custom_dynamic_regular_price'), 10, 2);
        add_filter('woocommerce_product_get_sale_price', array($this, 'custom_dynamic_regular_price'), 10, 2);
    }


//on activation  plugin in admin panel create plugin custom table
    public function on_activate()
    {
        $opt_ver = static::$plugin_id . '-version';
        $installed_version = get_option($opt_ver, NULL);
        if ($installed_version == NULL) {// get and save  plugin option problem option name - product-creator-version

            $this->model->createTable();
            update_option($opt_ver, $this->plugin_version);// get and save  plugin option problem option name product-creator-version
            //https://www.php.net/manual/en/function.version-compare.php
        } else {
            /* version_compare php - porownuje dwa stringi dotyczace wersji, param; dwa stringi do porownanania */
            switch (version_compare($installed_version, $this->plugin_version)) {
                case 0:
                    //TODO:dodac to kjakoo messege info wtyczki
                    //zainstalowana wersja jest identyczna z tą
                    break;

                case 1:
                    //TODO:dodac to kjakoo messege info wtyczki
                    //zainstalowana wersja jest nowsza niż poprzednia
                    break;

                case -1:
                    //TODO:dodac to kjakoo messege info wtyczki
                    //zainstalowana wersja jest starsza niż poprzednia
                    break;
            }
        }

    }


    public
    function displayCalc()
    {
        $this->request->CalculatorFront();
    }

//admin notices when WC is not activated
    public
    function admin_notice()
    {
        ?>
        <div class="notice notice-error is-dismissible">
            <p>Woocommerce not activated! <strong> please activate Woocommerce before run plugin</strong>.</p>
        </div>
        <!--TODO DODAC SPRAWDZENIE CZY TABELA SIE POPRAWNIE ZALADOWALA-->
        <div class="notice notice-info is-dismissible">
            <p><strong>Please deactive and active Product Creator one again to run properly</strong>.</p>
        </div>
        <?php
    }


//plugin admin panel section (just visual front end in WP rules)
//uwaga!!! aby pokazalo sie menu w wwersji WP MU ,
// wtyczke nalezy aktywowac bezposrednio na kokpicie danej witryny
    public
    function createAdminMenu()
    {
        $WooProdCreatorHelpTab = add_menu_page(
            'Products Table',
            'Product Creator',
            $this->user_capability,
            static::$plugin_id,
            array($this, 'printAdminPage')
        );
        add_action('load-' . $WooProdCreatorHelpTab, array($this->helpTab, 'woo_prod_creator_help_tab'));
    }

    public function getAdminPageUrl(array $params = array())
    {//parametrem jest typ tablicowy jako wartosc pusta tablica
        $admin_url = admin_url('admin.php?page=' . static::$plugin_id);
        $admin_url = add_query_arg($params, $admin_url);//add_query_arg WP Retrieves a modified URL query string.
        //dzieki add query arg mozna dodawac parametry do urla takie ja viev form i uzaleznic od tego co ma sie w wdanej //chwili wysweitlac na stronie
        return $admin_url;
    }

    public function printAdminPage()
    {
        $view = $this->request->getQuerySingleParam('view', 'index');
        $action = $this->request->getQuerySingleParam('action');
        $productid = (int)$this->request->getQuerySingleParam('productid');//konwersja parametru id w url-u na int printuje w url //po wybraniu pojedynczego linka(slidu)


        switch ($view) {//view ma rozne wartosci index, form, ( layout - tutja nie wykorzystywany) wyswietlanie tresci //strony w zaleznosci od wybranego kontekstu

            case 'index':
                //var_dump($productid);

                if ($action == 'delete') {

                    $token_name = $this->action_token . $productid;
                    $wpnonce = $this->request->getQuerySingleParam('_wpnonce', NULL);

                    if (wp_verify_nonce($wpnonce, $token_name)) {
                        /* wp_verify_nonce WP
                           param; nazwa pola nonce do weryfikacji, akcja jaka jest kojarzona z tym nonce
                           tutaj: token w parametrze url , nazwa tokena*/

                        if ($this->model->deleteRow($productid) !== FALSE) {

                            $this->flashMsg->setFlashMsg('Poprawnie usunięto produkt!');

                        } else {
                            $this->flashMsg->setFlashMsg('Nie udało się usunąć produkt', 'error');
                        }

                    } else {
                        $this->flashMsg->setFlashMsg('Nie poprawny token akcji', 'error');
                    }


                } else
                    if ($action == 'bulk') {//akcja bulk czyli masowe dzialanie


                        if ($this->request->isMethod('POST') && check_admin_referer($this->action_token . 'bulk')) {

                            $bulk_action = (isset($_POST['bulkaction'])) ? $_POST['bulkaction'] : NULL;
                            $bulk_check = (isset($_POST['bulkcheck'])) ? $_POST['bulkcheck'] : array();
                            //bulkcheck - ilosc wpisow
                            //bulkaction- akcja masowych dzilana np delete

                            if (count($bulk_check) < 1) {
                                $this->flashMsg->setFlashMsg('Brak produktów do zmiany', 'error');
                            } else {
                                //delete-akcja ususniecia wartosc
                                if ($bulk_action == 'delete') {

                                    if ($this->model->bulkDelete($bulk_check) !== FALSE) {
                                        $this->flashMsg->setFlashMsg('Poprawnie usunięto zaznaczone produkty!');
                                    } else {
                                        $this->flashMsg->setFlashMsg('Nie udało się usunąć zaznaczonych produktów', 'error');
                                    }

                                }

                            }

                        }
                        //przekierwoanie na flowna stone admina puginu po wykonaniu masowych dzialan

                        $this->request->redirect($this->getAdminPageUrl());
                    }


                $curr_page = (int)$this->request->getQuerySingleParam('paged', 1);//biezaca strona
                $order_by = $this->request->getQuerySingleParam('orderby', 'id');//sortuj wg
                $order_dir = $this->request->getQuerySingleParam('orderdir', 'asc');//kierunek sortowania


                $pagination = $this->model->getPagination($curr_page, $this->pagination_limit, $order_by, $order_dir);
                // renderowanie pagincji
                $this->request->render('index', array(
                    'Pagination' => $pagination
                ));
                break;

            case 'form':

                if ($productid > 0) {

                    $ProductEntry = new ProductCreator_Controller_Entry($productid);//instancja, obiekt ma zawartosc productid

                    if (!$ProductEntry->exists()) {//jesli wpis obrazka nie istnieje
                        $this->flashMsg->setFlashMsg('Brak takiego produktu w bazie danych', 'error');
                        $this->request->redirect($this->getAdminPageUrl());//przekierowanie do  glownejstrony admina w momencie //braku wpisu

                    }


                } else {// jesli dodano poprawnie wpis stworz obiekt slide entry klasy moj plugin slide entry

                    $ProductEntry = new ProductCreator_Controller_Entry();

                }


                //jesli akcja jest save (przycisk save w formularzu)i zadanie jest przesylane postem i istieje post entry
                if ($action == 'save' && $this->request->isMethod('POST') && isset($_POST['product'])) {

                    if (check_admin_referer($this->action_token)) {//sprawdzenie poprawengo tokena

                        $ProductEntry->setFields($_POST['product']);// wstawienie wszytskich danych wysyslanaych przez pst

                        //  if ($this->errorValidate->validate()) {//wywolanie walidacji danych przesylanych postem i spr czy sa poprawne

                        //jesli tak to zapisz obrazek
                        $product_id = $this->model->saveproductentry($ProductEntry);
                        //jesli wpis nie ma wartosci logocznej false czyli zapisanie wpisu odbylo sie poprawnie
                        if ($product_id !== FALSE) {

                            if ($ProductEntry->hasId()) {// w przypadku modyfikacji wpisu( obrazka)
                                $this->flashMsg->setFlashMsg('Poprawnie zmodyfikowano produkt.');
                            } else {// w przypadku dodanie nowego wpisu
                                $this->flashMsg->setFlashMsg('Poprawnie dodano nowy produkt.');
                            }
                            //przekierwoanie na widok juz zapisanego aktulanego biezacego  slidu , po to jest klucz //productid
                            $this->request->redirect($this->getAdminPageUrl(array('view' => 'form', 'productid' => $product_id)));


                        } else {//jesli entry id jest false, wszytskie f., obslugujace wiadomosci obsluguja sesje
                            $this->flashMsg->setFlashMsg('Wystąpiły błędy z zapisem do bazy danych', 'error');
                        }
                        //} else {//jesli walidacja nie przebiegla prawidlowo
                        //$this->flashMsg->setFlashMsg('Popraw błędy formularza', 'error');
                        // }

                    } else {//odwolanie do check admin referer sprawdzanie tokena jesli bledne
                        $this->flashMsg->setFlashMsg('Błędny token formularza!', 'error');

                    }

                }
                ///////////osdpowiedzilany  zza nazwe $Link przy form.php//////////////
                $this->request->render('form', array(
                    'Product' => $ProductEntry
                ));
                break;


            default:
                $this->request->render('404');
                break;

        }
    }
}