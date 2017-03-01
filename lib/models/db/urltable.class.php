<?php
require_once '../../interfaces/idbresult.php';

class URLTable 
{
    public $Id;
    public $ArticleId;
    public $URL;
    public $Article;

    public function __construct()
    {
    }

    public static function WithRow($row)
    {
        $result = new self();
        $result->Id = $row['urtid'];
        $result->ArticleId = $row['urtartid'];
        $result->URL = $row['urturl'];
        return $result;
    }

    public static function WithResult(IDbResult $dbResult)
    {
        $row = $dbResult->FetchRow();
        return WithRow($row);
    }
}
?>