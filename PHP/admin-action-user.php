<?php
session_start (); 

include "database-connection.php";

/////////////////////////////DELETING//////////////////////////////
if (isset ($_GET['delete']))
{
  if (!empty ($_GET['delete']) &&
      is_numeric($_GET['delete']))
  {
    //setting variables
    $id_user = $_GET['delete'];

    $sql = "DELETE FROM user WHERE user_id='$id_user'";
    
    //deleting from db
    $mysqli->query($sql) or die ($mysqli->error);
    
    //setting session variables
    $_SESSION['message'] = "Account has been succesfully deleted.";
    $_SESSION['msg_type'] = "danger"; 
  }
    //redirecting
    header ("location: user-center.php"); 
}


/////////////////////////////ACCOUNT ACTIVATION//////////////////////////////
if (isset ($_GET['activate']) &&
    isset ($_GET['active'])
    )
{
    
    //validating data to be used
    if (!empty ($_GET['activate']) &&
        is_numeric ($_GET['activate']) &&
        ($_GET['active'] == 0 ||
        $_GET['active'] == 1) &&
        is_numeric ($_GET['active'])
        )
        {
            $id_user = $_GET["activate"];
            if (!$_GET["active"])
            {
                //activating an account
                $sql_active = "UPDATE user 
                                SET status = 1 
                                WHERE user_id='$id_user'";

                $mysqli->query ($sql_active) or die ($mysqli->error);
                //setting session variables
                $_SESSION['message'] = "Account has been succesfully activated.";
                $_SESSION['msg_type'] = "success";
            }
            else//desactivate an account
            {
                $sql_active = "UPDATE user 
                                SET status = 0
                                WHERE user_id='$id_user'";

                $mysqli->query ($sql_active) or die ($mysqli->error);
                //setting session variables
                $_SESSION['message'] = "Account has been succesfully desactivated.";
                $_SESSION['msg_type'] = "success";
            }
        }

    //redirecting
    header ("location: user-center.php");
}