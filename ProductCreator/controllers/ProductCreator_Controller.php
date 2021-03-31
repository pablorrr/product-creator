<?php

namespace ProductCreator\controllers;


class ProductCreator_Controller

{

    private $query_params;
    private static $instance = null;

    // private static $instance;

    private function __construct()
    {

    }

    public static function getInstance()
    {

        if (self::$instance == null) {

            //self::$instance == new Singleton();
            //to samo co  powyzej mozna stososwac zamiennie
            self::$instance = new self();

        }

        return self::$instance;

    }

    // przetwarzaie grupy parameetrow
    function getQueryParams()
    {
        if (!isset($this->query_params)) {
            //rozdzielnaie lancuhca wg ampresanda, wynik koncowy to tablica asocjacyjna
            $parts = explode('&', $_SERVER['QUERY_STRING']);//$_SERVER['QUERY_STRING']- PHP tablica superglobalna , query //string daje dostep do tych znkow w pasku url  ktore sa po znaku '?'

            $this->query_params = array();

            foreach ($parts as $part) {//przetwarzanie tablicy parts jako part( zmienna tymczasowa)
                $tmp = explode('=', $part);//rozdzielanaie po znku =
                $this->query_params[$tmp[0]] = trim(urldecode($tmp[1]));//trim ucina puste spacje , urldecode , dekoduje url //na format php
            }
        }

        return $this->query_params;
    }
    // dostanie sie do pojedynczego parametru
    /* funkcja moze przyjmowac rowniez tylko jeden parametr w wywwolaniu jako name moze byc  view, index, jest to pojedynczy parametr url tutaj do wyswietlania danej strony np form= forlmularz */
    function getQuerySingleParam($name, $default = NULL)
    {
        $qparams = $this->getQueryParams();

        if (isset($qparams[$name])) {//jesli istniej nazwa parametru np w wywolaniu funkcji
            return $qparams[$name];// to ja zwroc
        }

        return $default;//jesli warunek sie nie spelni to zwroci null jesli sie spelni to zwoci wartosc a ana koniec doda //null w ten spsosb uniknie dodania przypadkowej wartosci do kweredny url
    }


    function isMethod($method)
    {// przyjmuje jako param metode wyslania danych do bazy danych przez formularz get post
        return ($_SERVER['REQUEST_METHOD'] == $method);//nastawia dynamicznie na biezaca metode wysylania danych przez formularz tutaj: post lub get, post wyslanie danych do bazy danych , get wysylanie do kweredny url

        //Which request method was used to access the page; i.e. 'GET', //'HEAD', 'POST', 'PUT'.
    }


    public function redirect($location)
    {
        //przekirwoanie na inna strone f built-in WP
        wp_safe_redirect($location);
        //alternatywa dla php  die dzieki temu zadnea instr nie wykona sie pon przekierowaniu
        exit;
    }


    public function render($view, array $args = array())
    {


        extract($args);//wyciagnie (phhp) zmiennych


        $tmpl_dir = plugin_dir_path(__DIR__) . 'views/';

        $view = $tmpl_dir . $view . '.php';

        require_once $tmpl_dir . 'layout.php';

    }

    public function CalculatorFront()
    {
        ob_start();
        require plugin_dir_path(__DIR__) . 'views/front-end-calculator.php';
        $content = ob_get_clean();
        echo $content;
    }


}