<?php


namespace ProductCreator\controllers;


use ProductCreator\models\ProductCreator_Model;


class ProductCreator_Controller_Entry extends ProductCreator_Controller
{
    private $id = NULL;//id produktu

    private $exists = FALSE;//ustawienie oznacza ze domyslnie jeest zalozenie ze dana (wpis -nowy wiersz)instancja nie //istnieje w bazie danych


    function __construct($id = NULL)
    {//ustawienei domyslengo id na null id bedzie wykorzystywany jako parametr w url, id //jesy rowniez kolumna w bazie danych
        $this->id = $id;
        $this->load();
    }

    //ladowanie danych z bazy anych z uzyciem ferch row zdef w klasie modelowej( obsluga bazy danych)
    private function load()
    {
        if (isset($this->id)) {
            $Model = new ProductCreator_Model();//stworzenie obiektu lte home slider model
            $row = $Model->fetchRow($this->id);//zwrocenie zawartosci wiersza

            if (isset($row)) {//jesli istnieje wiersz w bazie danych
                $this->setFields($row);//ustaw pole na dany wiersz
                $this->exists = true;//ustaw instancje  ( wpis -nowy wiersz) w bazzie danych na true
            }
        }
    }

    //zwraca istniejaca instancje wpis nowy wiersz( rekord)
    public function exists()
    {
        return $this->exists;
    }

    //uzyskajj pole z bazy danych
    function getField($field)
    {
        if (isset($this->{$field})) {//zapis zmiennej zmiennej dynamicznej
            return $this->{$field};
        }

        return NULL;
    }

    function hasId()
    {
        return isset($this->id);//zwroc wartosc id
    }

    //ustawia wartosc  wszytskicj pol
    function setFields($fields)
    {
        foreach ($fields as $key => $val) {//przypisanie klucza wartosci
            $this->{$key} = $val;
        }
    }
}


