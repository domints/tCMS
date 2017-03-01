<?php

//Boot results
require_once 'db_result.class.php';
require_once 'db_result_cached.class.php';


/**
* Sterownik bazy danych.
 *
 */

define('CACHE_DIR', './sql_cache/');

class DBException extends Exception {
}

class DB
{
	
	/**
	* Identyfikator poł±czenia z serwerem bazy danych
	     * @access private
	     * @var resource
	     */
	    private $mysqli = '';
	
	
	/**
	* Nazwa bazy danych
	     * @access private
	     * @var string
	     */
	    private $strDBName = '';
	
	
	/**
	* Identyfikator wyniku zapytania do bazy danych
	     * @access private
	     * @var resource
	     */
	    private $resQueryResult = '';
	
	
	/**
	* Liczba wykonanych zapytń
	     * @access private
	     * @var integer
	     */
	    private $intExecutedQueries = 0;
	
	
	/**
	* Czas wykonywania wszystkich zapytań
	     * @access private
	     * @var float
	     */
	    private $fltTimer = 0;
	
	
	/**
	* Tablica przechowuj±ca wykonane przez skrypt zapytania (może się przydać przy debugowaniu)
	     * @access private
	     * @var array
	     */
	    private $arrExecutedQueries = array();
	
	
	/**
	* Konstruktor klasy
	     * @access private dostęp do klasy poprzez metodę GetInstance
	     * @param string $strDBHost  nazwa serwera
	     * @param string $strDBUser  nazwa użytkownika
	     * @param string $strDBPass  hasło dla użytkownika
	     * @param string $strDBName  nazwa bazy danych
	     * @return bool true w przypadku udanego nawi±zania z baz±
	     */
	    private function __construct( $strDBHost, $strDBUser, $strDBPass, $strDBName ){
		
		$this->mysqli = new mysqli($strDBHost, $strDBUser, $strDBPass, $strDBName);
		if ($this->mysqli->connect_errno) {
			throw new DBException($this->mysqli->connect_error);
		}
		
		if (!$this->mysqli->set_charset("utf8")) {
			throw new DBException($this->mysqli->error);
		}
	}
	
	
	/**
	* Query - wykonuje zapytanie do bazy
	     * @access public
	     * @param string $strQuery zapytanie SQL
	     * @return object zwraca obiekt będ±cy wynikiem zapytania
	     */
	    public function Query( $strQuery ) : DBResult
	    {
		$this->resQueryResult = '';
		$fltStart = $this->GetTime();
		
		$this->resQueryResult = $this->mysqli->query($strQuery);
		if( !$this->resQueryResult )
		        {
			//$			this->Error( $strQuery );
			throw new DBException($mysqli->error);
		}
		else
		        {
			$this->intExecutedQueries++;
			$this->arrExecutedQueries[] = $strQuery;
			$this->fltTimer += $this->GetTime() - $fltStart;
			if( $this->resQueryResult === true )
			            {
				return true;
			}
			else
			            {
				return( new DBResult( $this->resQueryResult ) );
			}
		}
	}
	
	
	/**
	* QueryCached - wykonuje zapytanie do bazy z cache'owaniem wyniku zapytania do pliku
     * @access public
     * @param string $strQuery zapytanie SQL
     * @param string $strHandle fragment nazwy pliku
     * @return object zwraca obiekt będ±cy wynikiem zapytania
     */
    public function QueryCached( $strHandle, $strQuery ) : DBResultCached
    {
        if( !file_exists( CACHE_DIR . 'query_' . $strHandle . '.cache' ) )
        {
            $this->resQueryResult = '';
            $fltStart = $this->GetTime();
            $this->resQueryResult = $mysqli->query( $strQuery );
            if( !$this->resQueryResult )
            {
                $this->Error( $strQuery );
            }
            else
            {
                $this->intExecutedQueries++;
                $this->arrExecutedQueries[] = $strQuery;
                $this->fltTimer += $this->GetTime() - $fltStart;
                if( $this->resQueryResult === true )
                {
                    return true;
                }
                else
                {
                    return( new DBResultCached( $strHandle, $this->resQueryResult ) );
                }
            }
        }
        else
        {
            return( new DBResultCached( $strHandle ) );
        }
    }
    /**
     * AffectedRows - ilo¶ć zmienionych wierszy w wyniku zapytania SQL
     * @access public
     * @return integer ilo¶ć zmienionych wierszy w wyniku zapytania SQL
     */
    public function AffectedRows()
    {
        return mysqli_affected_rows($this->mysqli);
    }
    /**
     * InsertID - zwraca numer ID wygenerowny podczas ostatniej operacji INSTERT
     * @access public
     * @return  integer zwraca numer ID wygenerowny podczas ostatniej operacji INSTERT
     */
    public function InsertID()
    {
        return mysqli_insert_id($this->mysqli);
    }
    /**
     * Close - zakończenie pracy z baz±
     * @access public
     * @return bool true-powodzenie
     */
    public function Close()
    {
        $resDBClose = mysqli_close( $this->mysqli );
        if( !$resDBClose )
        {
            $this->Error();
        }
        else
        {
            return true;
        }
    }
    /**
     * RemoveCache - Kasowanie pliku cache z wynikiem zapytania do bazy
     * @access public
     * @param string $strHandle nazwa pliku
     * @return bool
     */
    public function RemoveCache( $strHandle )
    {
        if( file_exists( CACHE_DIR . 'query_' . $strHandle . '.cache' ) )
        {
            unlink( CACHE_DIR . 'query_' . $strHandle . '.cache' );
            return true;
        }
        else
        {
            return false;
        }
    }
    /**
     * GetQueryCnt - Zwraca ilo¶ć wykonanych zapytań (zapytania cache'owane NIE s± liczone jeżeli s± czytane z pliku)
	     * @access public
	     * @return float ilo¶ć wykonanych zapytań
	     */
	    public function GetQueryCnt()
	    {
		return $this->intExecutedQueries;
	}
	
	
	/**
	* Error - zwraca tekst komunikatu o błędzie oraz konczy działanie skryptu
	     * @access private
	     * @return string tekst komunikatu
	     */
	    private function Error( $strQuery = '' )
	    {
		if( $strQuery == '' )
		        {
			echo '<br />MySQL zwrócił komunikat o błędzie numer <b>' . mysqli_errno() . '</b> i treści: <b><font color="red">' . mysqli_error() . '</font></b><br />';
		}
		else
		        {
			echo '<br />MySQL zwrócił komunikat o błędzie numer <b>' . mysqli_errno() . '</b> i treści: <b>' . mysqli_error() . '</b>.<br />Bł±d w zapytaniu : <b><font color="red">' . $strQuery . '</b></font><br />';
		}
		exit;
	}
	
	
	/**
	* GetExecutionTime - Zwraca czas wszystkich zapytań (czas zapytań cache'owanych NIE jest liczony jeżeli s± czytane z pliku)
     * @access public
     * @return float czas wszystkich zapytań
     */
    public function GetExecutionTime()
    {
        return round( $this->fltTimer, 5 );
    }
    /**
     * GetTime - Pobiera aktualny czas
     * @access private
     * return float aktualny czas
     */
    private function GetTime()
    {
        $arrTime = explode( ' ', microtime() );
        return $arrTime[0] + $arrTime[1];
    }
    /**
     * Metoda Singleton zwracaj±ca instancję klasy
     * @access public
     * @param string $strDBHost  nazwa serwera
     * @param string $strDBUser  nazwa użytkownika
     * @param string $strDBPass  hasło dla użytkownika
     * @param string $strDBName  nazwa bazy danych
     * @return object zwracaj±ca instancję klasy
     */
    static public function GetInstance( $strDBHost, $strDBUser, $strDBPass, $strDBName )
    {
        static $objInstance;
        if( !isset( $objInstance ) )
        {
            $objInstance =  new DB( $strDBHost, $strDBUser, $strDBPass, $strDBName );
        }
        return( $objInstance );
    }
}
?>
