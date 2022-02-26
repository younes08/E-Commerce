<?php
include 'database-connection.php'; 
include 'data-reader-fcs.php';

//get the cart (current cmd)
$sql = "SELECT * 
        FROM command, cmd_line, product, info_cmd_status 
        WHERE id_cmd = cmd_line.id_cmd_FK 
        AND info_cmd_status.id_cmd_FK = id_cmd
        AND id_prod = id_prod_FK
        AND id_cmd NOT IN (SELECT id_cmd_FK  
                            FROM info_cmd_status 
                            WHERE id_status_FK != 1)";

$res = $mysqli->query ($sql) or die ($mysqli->error);

//displaying the query
while ($row = $res->fetch_assoc())
{
    $id_cmd = $row['id_cmd'];
    $html = "<tr><td>" . $row['id_cmd'] . "</td><td>" . $row['prod_name'] . "</td>";
    //brand FK display
    $brand_name = brand_finder_from_FK ($row['id_brand_FK'], $mysqli);
    
    $html .= "<td>" . $brand_name . "</td>";    

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

    $html .= "<td>
                <form action='buy-prod.php' method='POST' style='width: min-content;'>
                    <input type='hidden' name='id_line' value='" . $row['id_line'] . "' />
                    <div class='row'>
                        <input style='width: 90px;' type='number' class='form-control mx-auto input-lg' name='qte' value='" . $row['quantity'] . "'min='1'>
                    </div>
                    <div class='row'>
                        <button class=\"btn btn-info btn-lg\" name=\"edit-qte\"><i class='bi bi-pencil-square'></i></button>
                    </div>
                </form>
            </td>
            </td>";

    $html .= "<td>
            <div class='row'>
                <a href=\"buy-prod.php?delete-prod-cart=" . $row['id_line'] . "\">
                    <button class=\"btn btn-danger\"><i class='bi bi-trash'></i></button>
                </a>
            </div>
            </td>
        </tr>";


    echo $html;       
}

if (!empty ($id_cmd))
{
    $price = 0;

    //get total price
    $total_sql = "SELECT * 
                    FROM cmd_line, command, product
                    WHERE cmd_line.id_cmd_FK = id_cmd
                    AND id_prod = id_prod_FK
                    AND id_cmd = '$id_cmd'";

    $totals = $mysqli->query ($total_sql) or die ($mysqli->error);

    while ($total = $totals->fetch_assoc ())
    {
        $price += $total["unit_price"];
    }

    $html = "<span class='badge rounded-pill bg-warning text-dark' style='font-size: 25px;'>Total: ". $price . " MAD</span><hr class='colorgraph'>";
    echo $html;
}




//deleting or confirming the cart       
if (mysqli_num_rows ($res))
{
    $res = $mysqli->query ($sql) or die ($mysqli->error);
    $res = $res->fetch_assoc ();
    echo "
        <div class='row mb-3'>
            <div class='col-xs-12 col-md-6'>
                <a href=\"buy-prod.php?delete-order=" . $res["id_cmd"] . "\">
                    <button class='btn btn-block btn-lg' style='margin-bottom: 20px;'><i class='bi bi-trash'></i> Delete Current Order</button>
                </a>
            </div>
            <div class='col-xs-12 col-md-6'>
                <a href=\"payment.php?id_cmd=" . $res["id_cmd"] . "\">
                    <button class='btn btn-block btn-lg' style='margin-bottom: 20px;'><i class='bi bi-check-circle'></i> Confirm Your Order</button>
                </a>
            </div>
        </div>";
}


