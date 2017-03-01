<?php
interface IDbResult
{
    /**
     * Wybranie wiersza z wyników zapytania
     * @access public
     * @param integer $intResultType stała PHP określająca typ tablicy jaki ma być zwrócony
     * @return array|false wiersz z zapytania w postaci tablicy lub false w przypadku błędu
     */
    public function FetchRow( $intResultType );

    /**
     * Zwraca ilość wierszy w wyniku zapytania
     * @access public
     * @return integer ilośc wierszy
     */
    public function NumRows();
}
?>