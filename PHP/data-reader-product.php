<?php
include 'database-connection.php'; 
include 'data-reader-fcs.php';

$sql = "SELECT * FROM product";
$result = $mysqli->query ($sql);

//looping through the query
while ($row = $result->fetch_assoc())
{
    $html = "<tr><td>" . $row['id_prod'] . "</td><td>" . $row['prod_name'] .
    "</td><td>" . $row['descrip'] . "</td><td>" . $row['unit_price'] . 
    "</td><td>" . $row['qte_prod'] . "</td><td>" . $row['promotion'] . "</td>";
    
    //brand FK display
    $brand_name = brand_finder_from_FK ($row['id_brand_FK'], $mysqli);
    
    $html .= "<td>" . $brand_name . "</td>";    

    //category FK display    
    $cat_name = cat_finder_from_FK ($row['id_cat_FK'], $mysqli);
    
    $html .= "<td>" . $cat_name . "</td>" ;

    //selecting product photos
    $media_display = $row['id_prod'];
    $media_sql = "SELECT media_path from product_media WHERE id_prod_FK='$media_display'";
    $media = $mysqli->query ($media_sql);
    $media = $media->fetch_array();
    $media = $media['media_path'];

    $html .= '<td>';

    //check if there is a picture 
    if(!empty ($media)) 
    {
        $html .= '<img src="' . $media . '" alt=' . $row['prod_name'] . ' style="width: 50px; height: 65px;">'; 
    }    

    $html .= '</td>';

    $html .= "<td>
                <a href=\"product-center.php?edit=" . $row['id_prod'] . "\">
                    <button class=\"btn btn-info\" name=\"edit\"><i class='bi bi-pencil-square'></i></button>
                </a>
                </td>
                <td>
                <a href=\"admin-action-product.php?delete=" . $row['id_prod'] . "\">
                    <button class=\"btn btn-danger\" name='delete'><i class='bi bi-trash'></i></button>
                </a>
                </td>
            </tr>";
    
    echo $html;

}
    

