<?php

	class Request
	{
		/**
		* obiekt request
		* @var object
		*/
		private static $oRequest = false;
		
		/**
		* tablice ż±dań
		* @var array
		*/
		private $_GET = array();
		private $_POST = array();
		private $_COOKIE = array();
		private $_SESSION = array();
		private $_FILES = array();
		private $_SERVER = array();
		
		/**
		* czy w ż±daniu wystepuj± magiczne znaki
		* @var bool
		*/
		private $bMagicQuotes = false;
		
		
		/**
		* czy czy¶cić automatycznie
		* syf po działaniu magic_quotes
		* domy¶lnie false
		* @var bool
		*/		
		private $bAutoClear = false;
		
		
		/**
		* konstruktor klasy
		*/
		private function __construct()
		{
			if(isset($_GET))
			{
				$this -> _GET = $_GET;
			}

			if(isset($_POST))
			{
				$this -> _POST = $_POST;
			}

			if(isset($_COOKIE))
			{
				$this -> _COOKIE = $_COOKIE;
			}

			if(isset($_SESSION))
			{
				$this -> _SESSION = $_SESSION;
			}

			if(isset($_FILES))
			{
				$this -> _FILES = $_FILES;
			}

			if(isset($_SERVER))
			{
				$this -> _SERVER = $_SERVER;
			}
			
			$this -> bAutoClear ? $this -> bMagicQuotes = get_magic_quotes_gpc() : null;	
			
		}
		
		/**
		* czy szukany klucz istnieje w tablicy
		* @parram array $aArray
		* @param string $sParam
		* @return array
		*/
		
		private function GetVars( $aArray, $sParam ) {
		
			return array_key_exists( $sParam, $aArray ) ? $aArray[$sParam] : null;
			
		}
		
		/**
		* wyjmujemy konkretne ż±danie
		* @param string $sKey
		* @param string $sMethod
		* @param bool $bHtml2Entities
		* @param bool $bDb
		* @return string
		*/
		
		public function GetRequest( $sKey, $sMethod, $bHtml2Entities = false, $bDb = false ){
			
			switch( strtolower( $sMethod ) )
			{
				case 'get' :
				{
					return $this -> Prepare( $this -> GetVars( $this -> _GET, $sKey ), $bHtml2Entities, $bDb );
				break;
				}
				case 'post' :
				{
					return $this -> Prepare( $this -> GetVars( $this -> _POST, $sKey ), $bHtml2Entities, $bDb );
				break;
				}
				case 'cookie' :
				{
					return $this -> Prepare( $this -> GetVars( $this -> _COOKIE, $sKey ), $bHtml2Entities, $bDb );
				break;
				}
				
				case 'session' :
				{
					return $this -> Prepare( $this -> GetVars( $this -> _SESSION, $sKey ), $bHtml2Entities, $bDb );
				break;
				}
				
				case 'files' :
				{
					return $this -> Prepare( $this -> GetVars( $this -> _FILES, $sKey ), $bHtml2Entities, $bDb );
				break;
				}
				case 'server' :
				{
					return $this -> Prepare( $this -> GetVars( $this -> _SERVER, $sKey ), $bHtml2Entities, $bDb );
				break;
				}
			}
			
		}
		
		/**
		* filtrowanie danych
		* @param string $sVar
		* @param bool $bHtml2Entities
		* @param bool $bDb
		* @return string
		*/
		
		private function Prepare( $sVars, $bHtml2Entities, $bDb ){
			
			if( $bHtml2Entities == true ){
				$sVars = htmlentities( $sVars );
			}
			
			if( $this -> bAutoClear == true ){
				$this -> bMagicQuotes ?
					$sVars = addslashes( $sVars )
				: 
					$sVars = stripslashes( $sVars );
			}	
			
			
			if( $bDb == true ){
				$sVars = mysql_escape_string( $sVars );
			}
			
			return $sVars;
		}

		/**
		* Gets IP address
		* @return request IP Addr
		*/
		public function GetIP() 
		{			
			if (GetVars($this->_SERVER, 'HTTP_X_FORWARDED_FOR'))
			{
				return GetVars($this->_SERVER, 'HTTP_X_FORWARDED_FOR');
			}
			else
			{
				return GetVars($this->_SERVER, 'REMOTE_ADDR');
			}
		}
		
		/**
		 * Pobieranie instancji klasy
		 * @return object
		 */
		public static function GetInstance(){
		
		        if( self::$oRequest == false ){
		            self::$oRequest = new Request();
		        }
				
			return self::$oRequest;
			
		}
		
	}
	

?>