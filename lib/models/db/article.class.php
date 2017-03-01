<?php
require_once '../../interfaces/idbresult.php';

class Article
{
    public $Id;
    public $TemplateTypeId;
    public $Text;

    public function __construct()
    {
    }

    public static function WithRow($row)
    {
        $result = new self();
        $result->Id = $row['artid'];
        $result->TemplateTypeId = $row['artttyid'];
        $result->Text = $row['arttext'];
        return $result;
    }

    public static function WithResult(IDbResult $dbResult)
    {
        $row = $dbResult->FetchRow();
        return WithRow($row);
    }
}
?>