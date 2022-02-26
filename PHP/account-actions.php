<?php
if (!isset ($_SESSION))
{
    session_start (); 
}

include "database-connection.php";
include "admin-action-fcts.php";


//////////////Login into the account////////////////////
if (isset ($_POST["login"]))
{
    if (!empty ($_POST["email"]) && 
        !empty ($_POST["password"])
        )
        {
            //getting values
            $email = $_POST["email"];
            $password = $_POST["password"];

            $sql = "SELECT status, user_id, first_name 
                    FROM user 
                    WHERE email='$email' 
                    AND password ='$password'";
            
            $res = $mysqli->query ($sql) or die ($mysqli->error);

            //checking if there is a record with those credentials
            if (!mysqli_num_rows ($res))
            {
                $_SESSION["msg_type"] = "danger";
                $_SESSION["message"] = "<strong>Attention!</strong>Email or password doesn't exist.";
                header ("location: sign-in.php");
            }
            else //check if the account is active
            {
                $data_user = mysqli_fetch_array ($res);

                if (!$data_user ["status"])
                {
                    $_SESSION["msg_type"] = "warning";
                    $_SESSION["message"] = "<strong>Attention!</strong>Admin activation still pending.";
                    header ("location: sign-in.php");
                }
                else
                {
                    $_SESSION["msg_type"] = "success";
                    $_SESSION ["uid"] = $data_user["user_id"];
                    $_SESSION ["name"] = $data_user["first_name"];
                    header ("location: index.php");
                }
            }
        }
    else
    {
        $_SESSION["msg_type"] = "danger";
        $_SESSION["message"] = "<strong>Attention!</strong>ALl fields must be filled out.";
        header ("location: sign-in.php");
    }
}



//////////////Creating an account////////////////////

if (isset ($_POST["register"]))
{
    if (!empty($_POST["f_name"]) && 
        !empty($_POST["l_name"]) && 
        !empty($_POST['email']) && 
        !empty($_POST['password']) && 
        !empty($_POST['mobile']) &&
        !empty($_POST['repassword']) &&
        !empty($_POST['address']) &&
        !empty ($_POST['city'])
    )
    {
        //setting variables
        $f_name = $_POST["f_name"];
        $l_name = $_POST["l_name"];
        $email = $_POST['email'];
        $selectedcity = $_POST['city'];
        $password = $_POST['password'];
        $repassword = $_POST['repassword'];
        $mobile = $_POST['mobile'];
        $address = $_POST['address'];
        $oldpassword = $new_password = $new_repassword = "";
        $id = "";


        $valid = account_form_validator ($mysqli, $f_name, $l_name, $email, $selectedcity, 
                                        $password, $repassword, $new_password, $new_repassword, 
                                        $oldpassword, $mobile, $address, $id);
        
        if (!$valid)//invalid data
        {
            header ("location: register.php");
        }
        else //inserting the new unactivated account
        {
            $sql = "INSERT INTO user
                    (`first_name`, `last_name`, `email`, 
                    `password`, `mobile`,`id_city_FK`, `adresse`,`is_admin`,`status`)
                    VALUES ('$f_name', '$l_name', '$email', '$password', '$mobile','$selectedcity', 
                    '$address', 0, 0)";

            $mysqli->query ($sql) or die ($mysqli->error);

            $_SESSION["msg_type_reg"] = "success";
            $_SESSION["message"] = "You can login to your account when it is activated.";
            header ("location: index.php");
        }
    }
    else
    {
        $_SESSION["msg_type"] = "danger";
        $_SESSION["message"] = "<strong>Attention!</strong>ALL fields must be filled out.";
        header ("location: register.php");
    }
}

//////////////Editing an account////////////////////

if (isset ($_POST["edit-profile"]))
{
    if (!empty ($_POST["f_name"]) && 
        !empty ($_POST["l_name"]) && 
        !empty ($_POST['email']) &&  
        !empty ($_POST['mobile']) &&
        !empty ($_POST["address"]) &&
        !empty ($_POST['city'])
    )
    {
        //setting variables
        $f_name = $_POST["f_name"];
        $l_name = $_POST["l_name"];
        $email = $_POST['email'];
        $selectedcity = $_POST['city'];
        $oldpassword = $_POST['oldpassword'];
        $new_password = $_POST['new_password'];
        $new_repassword = $_POST['new_repassword'];
        $mobile = $_POST['mobile'];
        $address = $_POST['address'];

        $password = $repassword = "";

        $valid = account_form_validator ($mysqli, $f_name, $l_name, $email, $selectedcity, 
                                        $password, $repassword, $new_password, $new_repassword, 
                                        $oldpassword ,$mobile, $address, $_SESSION["uid"]);
        
        if (!$valid)//invalid data
        {
            header ("location: edit-profile.php");
        }
        else //inserting the new unactivated account
        {
            $id = $_SESSION["uid"];

            $sql = "UPDATE user
                    SET `first_name` = '$f_name', `last_name` = '$l_name', `email` = '$email', 
                        `password` = '$new_password', `mobile` = '$mobile', `id_city_FK` = '$selectedcity', 
                        `adresse` = '$address'
                    WHERE user_id = '$id'";

            $mysqli->query ($sql) or die ($mysqli->error);

            $_SESSION["msg_type_reg"] = "success";
            $_SESSION["message"] = "Your account was successfully updated.";
            header ("location: edit-profile.php");
        }
    }
    else
    {
        $_SESSION["msg_type"] = "danger";
        $_SESSION["message"] = "<strong>Attention!</strong>ALL fields must be filled out.";
        header ("location: edit-profile.php");
    }
}
