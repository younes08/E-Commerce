
<?php
//looking for brand id
function brand_finder($name, $mysqli)
{
    $brand_name = $name;
    $brand_id_sql = "SELECT id_brand from brand WHERE brand_name='$brand_name'";
    $brand_id = $mysqli->query($brand_id_sql) or die($mysqli->error);
    $row = $brand_id->fetch_assoc();
    $brand_id = $row['id_brand'];
    
    
    return $brand_id;
}

//looking for category id
function cat_finder ($name, $mysqli)
{
    $cat_name = $name;

    $cat_id_sql = "SELECT id_cat from category WHERE cat_name='$cat_name'";
    $cat_results = $mysqli->query($cat_id_sql) or die($mysqli->error);
    $row = $cat_results->fetch_assoc();
    $cat_id = $row['id_cat'];  

    return $cat_id;
}


//Uploading files

function file_uploader ($last_id, $mysqli, $update_file)
{
    $targetDir = "product-media/";
    $allowTypes = array ('JPG', 'PNG', 'JPEG', 'GIF', 'jpg','png','jpeg','gif');

    $statusMsg = "";
    $filenames = array_filter ($_FILES['files']['name']);
    if (!empty ($filenames))
    {
        if ($update_file) 
        {    
            // delete image file from database    
            $media_sql = "DELETE FROM product_media WHERE id_prod_FK = '$last_id'";
            $mysqli->query($media_sql) or die ($mysqli->error);
        }
        foreach ($_FILES['files']['name'] as $key=>$val)
        {
            //Getting filename and extension of each file
            $fileName = basename ($_FILES['files']['name'][$key]);
            $targetFilePath = $targetDir . $fileName;

            //Check whether file type is valid
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
            if (in_array ($fileType, $allowTypes))
            {
                // Upload file to server 
                if(move_uploaded_file($_FILES['files']['tmp_name'][$key], $targetFilePath))
                {
                    $insertValuesSQL  = "('$targetFilePath', '$last_id')"; 
                }
                else
                {
                    $errorUpload = $_FILES['files']['name'][$key];
                }
            }
            else
            {
                $errorUploadType = $_FILES['files']['name'][$key];
            }

            // Error message 
            $errorUpload = !empty($errorUpload)?'Upload Error: Can\'t upload file to the server.': ''; 
            $errorUploadType = !empty($errorUploadType)?'File Type Error: Choose the right format of your file, it should be one the these: JPG, PNG, JPEG, GIF.': ''; 
            $errorMsg = '<br/>'.$errorUpload.'<br/>'.$errorUploadType.'<br/>'; 


            if (!empty($insertValuesSQL))
            {
            // Insert image file name into database    
            $media_sql = "INSERT INTO product_media (media_path, id_prod_FK) 
            VALUES $insertValuesSQL";           
            
            $insert = $mysqli->query ($media_sql) or die ($mysqli->error);
            if (!$insert){$statusMsg = "Sorry, there was an error uploading your file.";} 
            }
            else{$statusMsg = "Upload failed! ".$errorMsg;}
        }
    }

    return $statusMsg;
}

function user_id_checker ($mysqli, $id)
{
    $sql = "SELECT is_admin FROM user WHERE user_id='$id'";
    $res = $mysqli->query ($sql) or die ($mysqli->error);
    $res = $res->fetch_assoc();

    return $res;
}

function account_form_validator ($mysqli, $f_name, $l_name, $email, $selectedcity, 
                                $password, $repassword, $new_password, $new_repassword, 
                                $oldpassword, $mobile, $address, $id)
{
    //regex variables for validation
    $name = "/^[a-zA-Z ]+$/";
    $emailValidation = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9]+(\.[a-z]{2,4})$/";
    $number = "/^[0-9]+$/";

    if (!preg_match ($name, $f_name))
    {
        $_SESSION["msg_type"] = "danger";
        $_SESSION["message"] = " <strong>Attention!</strong> The first name you entred isn't valid!!!";
        return 0;
    }
        
    if (!preg_match ($name, $l_name))
    {
        $_SESSION["msg_type"] = "danger";
        $_SESSION["message"] = " <strong>Attention!</strong> The last name you entred isn't valid!!!";
        return 0;
    }
           
    if (!preg_match ($emailValidation, $email))
    {
        $_SESSION["msg_type"] = "danger";
        $_SESSION["message"] = " <strong>Attention!</strong> The email you entred isn't valid!!!";
        return 0;
    }                    
                
    if (!empty ($password) &&
        !empty ($repassword))
    {
        if(strlen($password)<9)
        {
            $_SESSION["msg_type"] = "danger";
            $_SESSION["message"] = " <strong>Attention!</strong> The password you entred is too weak!";
            return 0;
        }
                    
        if($repassword != $password)
        {
            $_SESSION["msg_type"] = "danger";
            $_SESSION["message"] = " <strong>Attention!</strong> The passwords you entred do not match!";
            return 0;
        }
    }

    if (!empty ($oldpassword) &&
        !empty ($new_repassword) &&
        !empty ($new_password)
        )
    {
        if(strlen($new_password)<9)
        {
            $_SESSION["msg_type"] = "danger";
            $_SESSION["message"] = " <strong>Attention!</strong> The new password you entred is too weak!";
            return 0;
        }

        //get the old password to match 
        $sql = "SELECT password FROM user WHERE user_id='$id'";
                
        $res = $mysqli->query ($sql) or die ($mysqli->error);
        $res = $res->fetch_assoc ();

        if ($oldpassword != $res["password"])
        {
            $_SESSION["msg_type"] = "danger";
            $_SESSION["message"] = " <strong>Attention!</strong> The old passsword is not correct!";
            return 0;
        }

        if($new_repassword != $new_password)
        {
            $_SESSION["msg_type"] = "danger";
            $_SESSION["message"] = " <strong>Attention!</strong> The passwords you entred do not match!";
            return 0;
        }
    }
    
     
    if(!preg_match ($number, $mobile))
    {
        $_SESSION["msg_type"] = "danger";
        $_SESSION["message"] = " <strong>Attention!</strong> The mobile phone you entred isn't valid!!!";
        return 0;
    }
                        
    if(strlen ($mobile)<10)
    {
        $_SESSION["msg_type"] = "danger";
        $_SESSION["message"] = " <strong>Attention!</strong> The mobile phone should contain only 10 digits.";
        return 0;
    }
        
    //check if email already exists in database
    if (!empty ($id))
    {
        $sql = "SELECT * FROM user WHERE email='$email' and user_id != $id";
    }
    else
    {
        $sql = "SELECT * FROM user WHERE email='$email'";
    }
    
    $res = $mysqli->query ($sql) or die ($mysqli->error);

    if (mysqli_num_rows ($res))//email is in the database
    {
        $_SESSION["msg_type"] = "danger";
        $_SESSION["message"] = " <strong>Attention!</strong> The email is already existing!";
        return 0;
    }


    //no error was found
    return 1;
}


function order_payment_changes ($id_cmd, $new_stat, $mysqli)
{
    
    //get the command to change its status and update stock value 
    $sql = "SELECT * 
            FROM command, cmd_line
            WHERE id_cmd = id_cmd_FK
            AND id_cmd = '$id_cmd'";

    $res = $mysqli->query ($sql) or die ($mysqli->error);
    
    //stock update
    while ($row = $res->fetch_assoc ())
    {
        $id_prod = $row["id_prod_FK"];
        $sql_prod = "SELECT qte_prod FROM product WHERE id_prod = '$id_prod'";
        $qte_prod = $mysqli->query ($sql_prod) or die ($mysqli->error);
        $qte_prod = $qte_prod->fetch_assoc ();
        $qte_prod = $qte_prod["qte_prod"];
        $qte_prod -= $row["quantity"];
        //insert new quantity
        $sql_new_qte = "UPDATE product 
                        SET qte_prod = '$qte_prod'
                        WHERE id_prod = '$id_prod'";
                        

        $mysqli->query ($sql_new_qte) or die ($mysqli->error);
    }

    //Add cmd status: done
    $date = date ("Y-m-d H:i:s");
    $sql_status_update = "INSERT INTO info_cmd_status(date, id_cmd_FK, id_status_FK)
                            VALUES ('$date', '$id_cmd', '$new_stat')";

    $mysqli->query ($sql_status_update) or die ($mysqli->error);
    
}



        