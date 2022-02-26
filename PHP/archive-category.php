<?php
include "admin-action-fcts.php";

function sql_by_cat ($mysqli, $cat)
{   
    if (empty ($cat))
    {
        $sql = "SELECT * FROM product";
    }
    else
    {
        $id_cat = cat_finder ($cat, $mysqli);
        $sql =  "SELECT * 
                 FROM product 
                 WHERE id_cat_FK = '$id_cat'";
    }
    return $sql;
}