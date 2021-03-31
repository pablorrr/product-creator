<?php


namespace ProductCreator\controllers;


class ProductCreator_Controller_Pagination extends ProductCreator_Controller
{
//deklaracja wlasciwowci
    private $items;//wszytskie elementy paginowane

    private $order_by;//sortuj wg
    private $order_dir;//kierunek sortowania

    private $limit;//limit mozliwej liczby stron w paginacji
    private $total_count;//aktualna liczba stron w pagionacji

    private $curr_page;//biezaca stona
    private $last_page;//ostatnmia strona

    //konstruktor klasy pagination
    function __construct($items, $order_by, $order_dir, $limit, $total_count, $curr_page, $last_page)
    {
        $this->items = $items;
        $this->order_by = $order_by;
        $this->order_dir = $order_dir;
        $this->limit = $limit;
        $this->total_count = $total_count;
        $this->curr_page = $curr_page;
        $this->last_page = $last_page;
    }

    public function hasItems()
    {//czy sa obrazki w paginacji
        return (!empty($this->items));//zwric NIE pusta zawartosc ( wpisy), return zwroci true ga sa wpisy stad //negacja
    }


    public function getItems()
    {//dostep don obrazkow
        return $this->items;
    }

    public function getOrderBy()
    {//sortowanie przez
        return $this->order_by;
    }

    public function getOrderDir()
    {//otwieranie lokalizacji
        return $this->order_dir;
    }

    public function getLimit()
    {//ustalenie limitu
        return $this->limit;
    }

    public function getTotalCount()
    {//ustalenie oststecznej liczby obrazkow
        return $this->total_count;
    }

    public function getCurrPage()
    {//dostep do biezacej strony
        return $this->curr_page;
    }

    public function getLastPage()
    {//dostep do biezacej strony
        return $this->last_page;
    }
}