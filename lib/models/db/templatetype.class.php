<?php
require_once '../../interfaces/idbresult.php';

class TemplateType
{
    public $Id;
    public $Name;
    public $Path;

    public function __construct()
    {}

    public static function WithRow($row)
    {
        $result = new self();
        $result->Id = $row['ttyid'];
        $result->Name = $row['ttyname'];
        $result->Path = $row['ttypath'];
        return $result;
    }

    public static function WithResult(IDbResult $dbResult)
    {
        $row = $dbResult->FetchRow();
        return WithRow($row);
    }
}
?>