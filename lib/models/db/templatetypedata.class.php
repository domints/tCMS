<?php
class TemplateTypeData
{
    public $Id;
    public $DataType;
    public $Content;
    public $ArticleId;
    public $TemplateTypeId;

    public function __construct()
    {
    }

    public static function WithRow($row)
    {
        $result = new self();
        $result->Id = $row['ttdid'];
        $result->DataType = $row['ttddatatype'];
        $result->Content = $row['ttdcontent'];
        $result->ArticleId = $row['ttdartid'];
        $result->TemplateTypeId = $row['ttdttyid'];
        return $result;
    }

    public static function WithResult(IDbResult $dbResult)
    {
        $row = $dbResult->FetchRow();
        return WithRow($row);
    }
}
?>