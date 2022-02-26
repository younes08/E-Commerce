<?php
include 'database-connection.php'; 

$sql = "SELECT * FROM category";
$result = $mysqli->query ($sql);

//looping through the query
while ($row = $result->fetch_assoc())
{
    $html = "<tr><td>" . $row['id_cat'] . "</td><td>" . $row['cat_name'] . "</td>";

    $html .= "<td><a href=\"category-center.php?edit=" . $row['id_cat'] . 
    "\"><button class=\"btn btn-info\" name=\"edit\"><i class='bi bi-pencil-square'></i></button></a></td><td><a href=\"admin-action-category.php?delete=" . $row['id_cat'] . 
    "\"><button class=\"btn btn-danger\" name=\"delete\"><i class='bi bi-trash'></i></button></a></td></tr>";
    echo $html;
}
