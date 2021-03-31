<?php


namespace ProductCreator\controllers;



class ProductCreator_Controller_ErrorValidate

{
    function __construct()
    {
    }
    private $prod_name = NULL;
    private $weight = NULL;
    private $amount = NULL;
    private $color = NULL;
    private $volume = NULL;
    private $scent = NULL;
    private $length = NULL;
    private $width = NULL;
    private $errors = array();//przechowywanie bledow w tablicy





    //ustawianie bledow, komunikatow bledow
    function setError($field, $error)
    {//$field-nazwa pola,$error- komunikat tresc bledu
        $this->errors[$field] = $error;//ustawienie pola tablicy jako error
    }

    //pobieranie bloedow
    function getError($field)
    {
        if (isset($this->errors[$field])) {//sprawdzenie czy pole ma blad
            return $this->errors[$field];
        }

        return NULL;//jesli nie ma
    }

    //sprawdzanie czy pole ma bledy
    function hasError($field)
    {
        return isset($this->errors[$field]);
    }

    //sprawdza czy obiekt ma bledy
    function hasErrors()
    {
        return (count($this->errors) > 0);//zwraca liczbe bledow
    }

    ////////////////////WALIDACJA/////////////////////////////////////////////////
    function validate()
    {


        /*
         * POLE prod name:
         * - nie może być puste
         * - maksymalna długość 255 znaków
         */
        if (empty($this->prod_name)) {//jesli pole title w pluginie jest puste
            $this->setError('prod_name', 'To pole nie może być puste');
        } elseif (preg_match("/^[a-zA-Z_ ]*$/i", $this->prod_name)) {
            $this->prod_name = esc_html($this->prod_name);
        } else {
            $this->setError('prod_name', 'To pole musi zawierać tylko litery');
        }

        if (strlen($this->prod_name) > 255) {//strlen zwraca dlugosc lnacucha( php)
            $this->setError('prod_name', 'To pole nie może być dłuższe niż 255 znaków.');
        }

        ///////////////////////////////////


        /*
         * POLE weight:
         * -  NIE może być puste
         *
         */

        if (empty($this->weight)) {//jesli pole link w pluginie jest puste
            $this->setError('weight', 'To pole nie może być puste');
        } elseif (preg_match("/^\d+(?:\.\d+)?$/", $this->weight)) {
            if ($this->weight > 100) {
                $this->setError('weight', 'To pole nie może mięc większej wartości niż 100');
            } else {
                $this->weight = esc_html(floatval($this->weight));
            }

        } else
            $this->setError('weight', 'To pole musi byc liczba zmienna przecinkowa lub całkowitą (dodatnią), użyj kropki zamiast przecinka');


        /////////////////////////////////////////////////////




        /*
        * POLE Color:
        *
        */

        if (empty($this->color)) {//jesli pole title w pluginie jest puste
            $this->setError('color', 'To pole nie może być puste');
        } elseif (preg_match("/^[a-zA-Z_ ]*$/i", $this->color)) {
            $this->color = esc_html($this->color);
        } else {
            $this->setError('color', 'To pole musi zawierać tylko litery');
        }

        if (strlen($this->color) > 255) {//strlen zwraca dlugosc lnacucha( php)
            $this->setError('color', 'To pole nie może być dłuższe niż 255 znaków.');
        }


        /*
         * POLE Volume:
         *
         */
        if (empty($this->volume)) {//jesli pole link w pluginie jest puste
            $this->setError('volume', 'To pole nie może być puste');
        } elseif (preg_match("/^\d+(?:\.\d+)?$/", $this->volume)) {
            if ($this->volume > 100) {
                $this->setError('volume', 'To pole nie może mięc większej wartości niż 100');
            } else {
                $this->volume = esc_html(floatval($this->volume));
            }

        } else
            $this->setError('volume', 'To pole musi byc liczba zmienna przecinkowa lub całkowitą (dodatnią), użyj kropki zamiast przecinka');

        /////////////////////////////////////////////////////
        /*
         *
         * Pole Scent(zapach)
         *
         */
        if (empty($this->scent)) {//jesli pole title w pluginie jest puste
            $this->setError('scent', 'To pole nie może być puste');
        } elseif (preg_match("/^[a-zA-Z_ ]*$/i", $this->scent)) {
            $this->scent = esc_html($this->scent);
        } else {
            $this->setError('scent', 'To pole musi zawierać tylko litery');
        }

        if (strlen($this->scent) > 255) {//strlen zwraca dlugosc lnacucha( php)
            $this->setError('scent', 'To pole nie może być dłuższe niż 255 znaków.');
        }


        /*
         * POLE Długość
         *
         */
        if (empty($this->length)) {
            $this->setError('length', 'To pole nie może być puste');
        } elseif (preg_match("/^(?:0|[1-9][0-9]*)$/", $this->length)) {
            if ($this->length > 100) {
                $this->setError('length', 'To pole nie może mięc większej wartości niż 100');
            } else {
                $this->length = esc_html($this->length);
            }

        } else
            $this->setError('length', 'To pole musi byc liczba całkowitą (dodatnią)');
        /*
         * POLE width:
         * -  NIE może być puste
         *
         */

        if (empty($this->width)) {//jesli pole link w pluginie jest puste
            $this->setError('width', 'To pole nie może być puste');
        } elseif (preg_match("/^\d+(\.\d+\ \d+\.\d+\ \d+\.\d+)$/", $this->width)) {
            if ($this->width > 100) {
                $this->setError('width', 'To pole nie może mięc większej wartości niż 100');
            } else {
                $this->width = esc_html(floatval($this->width));
            }

        } else
            $this->setError('width', 'To pole musi byc liczba zmienna przecinkowa lub całkowitą (dodatnią), użyj kropki zamiast przecinka');

        return (!$this->hasErrors());//f. validujaca zwraca brak bledow
    }//koniec f., walidujacej

}