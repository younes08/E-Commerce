<?php
include 'database-connection.php'; 

$sql = "SELECT * FROM brand";
$result = $mysqli->query ($sql);

//looping through the query
while ($row = $result->fetch_assoc())
{
    $html = "<tr><td>" . $row['id_brand'] . "</td><td>" . $row['brand_name'] . "</td>";

    $html .= "<td><a href=\"brand-center.php?edit=" . $row['id_brand'] . 
            "\"><button class=\"btn btn-info\" name=\"edit\"><i class='bi bi-pencil-square'></i></button></a></td><td><a href=\"admin-action-brand.php?delete=" . $row['id_brand'] . 
            "\"><button class=\"btn btn-danger\" name=\"delete\" style=\"margin: 0;\"><i class='bi bi-trash'></i></button></a></td></tr>";
    echo $html;
}
