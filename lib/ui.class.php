<?php

	//model ui
	
	class UiException extends Exception {}
	
	class UI 
	{
	
	public static function Redirect( $sString, $seconds = false ){
		preg_match('/[0-9]{1,}/',$seconds)?header('Refresh: '.$seconds.'; url='.$sString):header( 'location: '.$sString.'' );
	}

	
	public static function RandString( $sString, $ile = 6 ){
		$aArr = array( 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'w', 'x', 'y', 'z' );	
		for( $i = 0; $i < $ile; $i++ ){		
			$liczba = rand(0,count($aArr)-1);		
			$sVal .= $aArr[$liczba];	
		}
		
		$aS = explode( ".", $sString );
		$sDok = md5( $sVal );
		
		return $aS[0].$sDok.'.'.$aS[1];
	}
	
	public static function Dump( $aArr ){
		echo'<pre>';
		print_r( $aArr );
		echo'</pre>';
	}	
	
	public static function URLExists($url){
   		if(strstr($url, "http://")) $url = str_replace("http://", "", $url);
		   $fp = @fsockopen($url, 80);
		   if($fp === false) return false;
		   return true;
			}


public static function Post2Insert( $tab ){

			foreach($tab as $klucz => $wartosc){

				$klucze .= "`".$klucz."`, ";
				$wartosci .= "'".$wartosc."', ";
				
				}
				
		$zw['klucze'] = substr($klucze, 0, -2);
		$zw['wartosci'] = substr($wartosci, 0, -2);
		
		return $zw;
	}
	
	public static function Post2Update( $tab ){

			foreach($tab as $klucz => $wartosc){

				$zapytanie .= "`".$klucz."` = '".$wartosc."', ";
				
				}
				
		return substr($zapytanie, 0, -2);
	}

	public static function Name2File ( $sciezka , $plik ){
	
		$tablica = pathinfo( $plik );
		
		$nazwa_pliku = ereg_replace( ".".$tablica['extension'], "", $plik );
		$nazwa_pliku = ui::makeLink( $nazwa_pliku );
		
		$pl = $sciezka.$nazwa_pliku.".".$tablica['extension'];
		$nazwa = $nazwa_pliku;
		
		for ( $a = 1 ; file_exists( $sciezka.$nazwa.".".$tablica['extension'] ) ; $a++ )
			{ $nazwa = $nazwa_pliku."_".$a; }
				
		return $nazwa.".".$tablica['extension'];
	} 

public static function MakeLink( $sString ){
		 $sString = strtolower($sString);
		 $sString =str_replace('ą', "a",  $sString );
		 $sString =str_replace("ę", "e",  $sString );
		 $sString =str_replace('ó', 'o',  $sString ); 
		 $sString =str_replace("ł", "l",  $sString );
		 $sString =str_replace("ż", "z",  $sString ); 
		 $sString =str_replace("ź", "z",  $sString ); 
		 $sString =str_replace("ć", "c",  $sString );
		 $sString =str_replace("ś", "s",  $sString );
		 $sString =str_replace('Ą', "a",  $sString );
		 $sString =str_replace("Ę", "e",  $sString );
		 $sString =str_replace("Ń", "n",  $sString );
		 $sString =str_replace("ń", "n",  $sString );
		 $sString =str_replace('Ó', 'o',  $sString ); 
		 $sString =str_replace("Ł", "l",  $sString );
		 $sString =str_replace("Ż", "z",  $sString ); 
		 $sString =str_replace("Ź", "z",  $sString ); 
		 $sString =str_replace("Ć", "c",  $sString );
		 $sString =str_replace("Ś", "s",  $sString );
		 $sString =str_replace(" ", "_",  $sString );
		 $sString =str_replace(".", "",  $sString );
		 $sString =str_replace(",", "",  $sString );
		 $sString =str_replace("'", "",  $sString );
		 $sString =str_replace('"', "",  $sString );
		 $sString =str_replace('|', "",  $sString );
		 $sString =str_replace('-', "_",  $sString );
		 $sString =str_replace('/', "",  $sString );
		 $sString =str_replace('\\', "",  $sString );
		 $sString =str_replace('(', "",  $sString );
		 $sString =str_replace(')', "",  $sString );
		 $sString =str_replace('%', "",  $sString );
		 $sString =str_replace('ţ', "t",  $sString );
		 $sString =str_replace('ă', "a",  $sString );
		 $sString =str_replace('ş', "s",  $sString );
		 $sString =str_replace('ă', "a",  $sString );
		 $sString =str_replace('?', "",  $sString ); 
		return  trim($sString) ;
	}

public static function isEmail($email) {
$email = strtolower($email);
$reg = '/^([a-z0-9]{1,}[a-z0-9-_.]*)@[a-z0-9-.]+([a-z]{2,4})$/';
if (preg_match($reg,$email,$aMatch)) return $email;
else return false;
}

}	
?>