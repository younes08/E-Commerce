<?php
include 'database-connection.php'; 

$sql = "SELECT * FROM user";
$result = $mysqli->query ($sql) or die ($mysqli->error);

//looping through the query
while ($row = $result->fetch_assoc())
{
    if (!$row["is_admin"]):
        $html = "<tr><td>" . $row['user_id'] . "</td><td>" . $row['first_name'] .  " " . 
                $row['last_name'] . "</td><td>" . $row['email'] . "</td>";

        if ($row["status"]):
            $html .= "<td><div class='form-check'><a href=\"admin-action-user.php?activate=" . $row['user_id'] . 
                    "&active=" . $row['status'] .
                    "\"><input class='form-check-input' type='checkbox' checked name='active'></a></div></td>";
        else:
            $html .= "<td><div class='form-check'><a href=\"admin-action-user.php?activate=" . $row['user_id'] .
                     "&active=" . $row['status'] .
                     "\"><input class='form-check-input' type='checkbox' name='active'></a></div></td>";
        endif;

        $html .= "<td><a href=\"admin-action-user.php?delete=" . $row['user_id'] . 
                "\"><button class=\"btn btn-danger mt-0 m\" name=\"delete\">
                <i class='bi bi-trash'></i></button></a></td></tr>";
        echo $html;

    endif;
}
