<?php
//looking for brand name by id 
function brand_finder_from_FK ($brand_id, $mysqli)
{
    $brand_id_sql = "SELECT brand_name from brand WHERE id_brand='$brand_id'";
    $brand_name = $mysqli->query($brand_id_sql) or die($mysqli->error);
    $brand_name = $brand_name->fetch_array();
    $brand_name = $brand_name['brand_name'];

    return $brand_name;
}

//looking for category name by id
function cat_finder_from_FK ($cat_id, $mysqli)
{
    $cat_id_sql = "SELECT cat_name from category WHERE id_cat='$cat_id'";
    $cat_name = $mysqli->query($cat_id_sql) or die($mysqli->error);
    $cat_name = $cat_name->fetch_array();
    $cat_name = $cat_name['cat_name'];

    return $cat_name;
}