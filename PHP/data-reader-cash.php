<?php
include 'database-connection.php'; 
include 'data-reader-fcs.php';


//only orders in pay by check 
$sql = "SELECT * 
        FROM command, info_cmd_status, user
        WHERE id_user_FK = user.user_id
        AND info_cmd_status.id_cmd_FK = id_cmd
        AND id_status_FK = 4
        AND id_cmd NOT IN (SELECT id_cmd_FK  
                            FROM info_cmd_status 
                            WHERE id_status_FK = 3
                            OR id_status_FK = 2)";

$res = $mysqli->query ($sql) or die ($mysqli->error);
//looîng through query 
while ($row = $res->fetch_assoc())
{
    $html = "<tr>
                <td>" . $row['user_id'] . "</td>
                <td>" . $row['id_cmd'] . "</td>
                <td>" . $row['date'] . "</td>
                <td>" . $row['last_name'] . "</td>
                <td>" . $row['adresse'] . "</td>
                <td>" . $row['mobile'] . "</td>";

    $id_cmd = $row['id_cmd'];


    //get total price
    $total_sql = "SELECT * 
                    FROM cmd_line, command, product
                    WHERE cmd_line.id_cmd_FK = id_cmd
                    AND id_prod = id_prod_FK
                    AND id_cmd = '$id_cmd'";
                    
    $totals = $mysqli->query ($total_sql) or die ($mysqli->error);
    $price = 0;
    $html .= "<td>"; 
    while ($total = $totals->fetch_assoc ())
    {
        $price += $total["unit_price"];
    }

    $html .= $price . "</td>";

    //value given
    $html .= "<td>
                <form action='buy-prod.php' method='POST' style='width: min-content;'>
                    <input type='hidden' name='id_cmd' value='" . $row["id_cmd"] . "' />
                    <input type='hidden' name='price' value='" . $price . "' />
                    <div class='row'>
                        <input style='width: 90px;' type='number' class='form-control mb-2 mx-auto input-lg' name='cash-val'min='1'>
                    </div>
                    <div class='row'>
                        <button class=\"btn btn-info btn-lg\" name=\"cash-pay\"><i class=\"bi bi-plus-square\"></i></button>
                    </div>
                </form>
            </td>";
    $html .= '<td>';

    $html .= '</tr>';

    echo $html;
}