<?php
require_once '../db.class.php';
require_once '../models/db/urltable.class.php';

class URLRepo
{
    private $dbContext;
    public function __construct(DB $context)
    {
        $dbContext = $context;
    }

    public function GetByURL($url, $withData = true) : URLTable
    {
        if($withData)
        {
            $result = $dbContext -> QueryCached('urlrepo_byURLWData_'.$name,'SELECT * FROM urltable join article on urtartid = artid left join templatetypedata on artid = ttdartid where urturl = \''.$url.'\'');
            $row = $result->FetchRow();
            $table = URLTable.WithRow($row);
            
        }
        else 
        {
            $result = $dbContext -> QueryCached('urlrepo_byURL_'.$name,'SELECT * FROM urltable where urturl = \''.$url.'\'');
            return URLTable.WithResult($result);
        }
    }
}
?>