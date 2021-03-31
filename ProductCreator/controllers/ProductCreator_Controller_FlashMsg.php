<?php


namespace ProductCreator\controllers;


class ProductCreator_Controller_FlashMsg extends ProductCreator_Controller
{
    function __construct()
    {

    }

//ustawienie wiadomosci w naglowku pluginu
    public function setFlashMsg($message, $status = 'updated')
    {// drugi param oznacza update wiadmosci
        //tworzenie danych sesyjnych , beda istnialy tylko na czas sesji
        $_SESSION[__CLASS__]['message'] = $message;//$_SESSION[__CLASS__] tablica danych sesyjnych klasy biezacej
        $_SESSION[__CLASS__]['status'] = $status;
    }

    //pobranie parametrow sesji
    public function getFlashMsg()
    {
        if (isset($_SESSION[__CLASS__]['message'])) {//jesli istnieje zmienna sesyjna message
            $msg = $_SESSION[__CLASS__]['message'];//przechowanie wiadomosci w msg
            unset($_SESSION[__CLASS__]);//niszczenie tej zmiennej i jej zawartosci
            return $msg;//zwrocenie pustej wiadomosci-mechanizm ten zapobiega pijawianiu ssie wiadomosci po refresh page
        }

        return NULL;// jesli zmienna sesyjna nie istnieje nastaw tablice session na null
    }

    //pobranie ststusu wiadomosci, dzieki temu jest okrelsana kalsa css okr wyglad wiadomosci w layout
    public function getFlashMsgStatus()
    {
        if (isset($_SESSION[__CLASS__]['status'])) {
            return $_SESSION[__CLASS__]['status'];//zwrocenie stsauu jesli istnieje
        }

        return NULL;
    }

    //sprawdzenie czy wogole jest ustwiona wiadmosc
    public function hasFlashMsg()
    {
        return isset($_SESSION[__CLASS__]['message']);
    }
}