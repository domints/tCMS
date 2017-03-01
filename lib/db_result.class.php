<?php
require_once 'interfaces/idbresult.php';

/**
 * Klasa obiektów będących wynikami zapytań do bazy.
 */

class DBResult implements IDbResult
{
    /**
     * Identyfikator wyniku zapytania do bazy danych
     * @var resource
     * @access private
     */
    private $resQueryResult = '';

    /**
     * Konstrukor klasy
     * @access public
     * @param resource $resResult identyfikator wyniku zapytania do bazy danych
     */
    public function __construct( $resResult )
    {
        $this->resQueryResult = $resResult;
    }

    /**
     * Wybranie wiersza z wyników zapytania
     * @access public
     * @param integer $intResultType stała PHP określająca typ tablicy jaki ma być zwrócony
     * @return array|false wiersz z zapytania w postaci tablicy lub false w przypadku błędu
     */
    public function FetchRow( $intResultType = MYSQLI_ASSOC )
    {
        $arrTemp = mysqli_fetch_array( $this->resQueryResult, $intResultType );
        if( !$arrTemp )
        {
            return false;
        }
        else
        {
            return $arrTemp;
        }
    }

    /**
     * Zwraca ilość wierszy w wyniku zapytania
     * @access public
     * @return int ilość wierszy, -1 w przypadku błędu
     */
    public function NumRows() : int
    {
        $intNumRows = mysqli_num_rows( $this->resQueryResult );
        if( !$intNumRows )
        {
            return -1;
        }
        else
        {
            return $intNumRows;
        }
    }
	
	public function insertId(){
		return mysqli_insert_id();
	}

    /**
     * Usuwanie wyników zapytania z pamięci podręcznej
     * @access public
     */
    public function Free()
    {
        mysqli_free_result( $this->resQueryResult );
    }

}
?>