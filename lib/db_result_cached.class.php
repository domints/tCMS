<?php
require_once 'interfaces/idbresult.php';
/**
 * Klasa obiektów będących wynikami zapytań (cache'owanych) do bazy
 */

class DBResultCached implements IDbResult
{
    /**
     * Identyfikator wyniku zapytania do bazy danych
     * @var resource
     * @access private
     */
    private $resQueryResult = '';

    /**
     * Bufor wyników zapytania
     * @var array
     * @access private
     */
    private $arrCacheBuffer = array();

    /**
     * Nazwa pliku z cache'em zapytania
     * @var string
     * @access private
     */
    private $strCacheFile = '';

    /**
     * Wskaźnik na wiersz w pliku z cache'm
     * @var integer
     * @access private
     */
    private $intCachePtr = 0;

    /**
     * Konstruktor klasy
     * @param string $strHandle fragment nazwy pliku
     * @param string $resResult identyfikator wyniku zapytania do bazy danych
     * @access public
     */
    public function __construct( $strHandle , $resResult = '' )
    {
        if( empty( $resResult ) )
        {
            $this->arrCacheBuffer = unserialize( file_get_contents( CACHE_DIR . 'query_' . $strHandle . '.cache' ) );
        }
        else
        {
            $this->resQueryResult = $resResult;
        }
        $this ->strCacheFile = CACHE_DIR . 'query_' . $strHandle . '.cache';
    }

    /**
     * Zwraca tablicę utworzoną z wyniku zapytania
     * @access public
     * @param integer $intResultType stała PHP określająca typ tablicy jaki ma być zwrócony
     * @return array|false zwraca wiersz z zapytania w postaci tablicy lub false w przypadku błędu
     */
    public function FetchRow( $intResultType = MYSQL_ASSOC )
    {
        if( empty( $this->resQueryResult ) )
        {
            $intTempPtr = ++$this->intCachePtr;
            if( isset( $this->arrCacheBuffer[ ( $intTempPtr ) - 1 ] ) )
            {
                return $this->arrCacheBuffer[ ( $intTempPtr ) - 1 ];
            }
            else
            {
                return false;
            }
        }
        else
        {
            $arrTemp = mysqli_fetch_array( $this->resQueryResult, $intResultType );
            if( !$arrTemp )
            {
                file_put_contents( $this->strCacheFile, serialize( $this->arrCacheBuffer ) );
                return false;
            }
            else
            {
                $this ->arrCacheBuffer[] = $arrTemp;
                return $arrTemp;
            }
        }
    }

  /**
     * Zwraca ilość wierszy w wyniku zapytania
     * @access public
     * @return integer ilośc wierszy
     */
    public function NumRows()
    {
        if( empty( $this->resQueryResult ) )
        {
            $intNumRows = count( $this->arrCacheBuffer );
        }
        else
        {
            $intNumRows = mysqli_num_rows( $this->resQueryResult );
        }

        if( !$intNumRows )
        {
            return -1;
        }
        else
        {
            return $intNumRows;
        }
    }
}
?>