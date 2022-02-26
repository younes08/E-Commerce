<?php
session_start (); 
include 'database-connection.php';

/////////////////////////////CREATING//////////////////////////////

//creating new brand
if (isset ($_POST['add_brand_alone']))
{
  if (!empty ($_POST['new_brand']))
  {
    //getting name of the new brand
    $new_brand = $_POST['new_brand'];

    //adding the brand record
    $brand_sql = "INSERT INTO brand (brand_name) values ('$new_brand')";
    $mysqli->query($brand_sql) or die ($mysqli->error);

    //setting session variables
    $_SESSION['message'] = "New brand has been succesfully added.";
    $_SESSION['msg_type'] = "info";
  }
  else
  {
    //setting session variables
    $_SESSION['message'] = "Field must be filled out.";
    $_SESSION['msg_type'] = "danger";
  }
  
  //redirecting
  header ("location: brand-center.php");
}

/////////////////////////////DELETING//////////////////////////////

//deleting the selected brand
if (isset ($_GET['delete']))
{
  if (!empty ($_GET['delete']) &&
      is_numeric($_GET['delete']))
  {
    //getting the brand id from the link
    $id_brand = $_GET['delete'];

    //check if the category is already used in a product
    $sql_checker = "SELECT count(*) as nbr_rec 
                    FROM product 
                    WHERE id_brand_FK='$id_brand'";

    $rec_count = $mysqli->query($sql_checker) or die ($mysqli->error);

    if ($rec_count)
    {
      //setting session variables
      $_SESSION['message'] = "This brand cannot be deleted, it has already been used with a product.";
      $_SESSION['msg_type'] = "danger";  
      //redirecting
      header ("location: brand-center.php");
    }


    $sql = "DELETE FROM brand WHERE id_brand='$id_brand'";

    //deleting
    $mysqli->query($sql) or die ($mysqli->error);

    //setting session variables
    $_SESSION['message'] = "Record has been successfully deleted.";
    $_SESSION['msg_type'] = "danger";
  }
  //redirecting
  header ("location: brand-center.php");
}


/////////////////////////////UPDATING//////////////////////////////
$id = '';
$update_brand_name = '';
$update_state = false;

if (isset ($_GET['edit']))
{
  if (!empty ($_GET['edit']) && 
      is_numeric($_GET['edit']))
  {
    //setting variables
    $id_brand = $_GET['edit'];

    //mark as updating state
    $update_state = true;
    
    $sql = "SELECT * FROM brand WHERE id_brand='$id_brand'";
    
    //selecting the record from db
    $result = $mysqli->query($sql) or die ($mysqli->error); 

    if (count ($result)==1)
    {
      $row = $result->fetch_array();

      //memorising the product data to pass then to the form for updates
      $id = $row['id_brand'];
      $update_brand_name = $row['brand_name'];
      
    }
  }
  else
  {
    //redirecting if no id is selected
    header ("location: brand-center.php");
  }
} 

if (isset ($_POST['update_brand']))
{
  $id_brand = $_POST['id'];
  if (!empty ($_POST['new_brand']))
  {
    //setting variables
    $brand_name = $_POST['new_brand'];
    
    $sql = "UPDATE brand
            SET brand_name = '$brand_name'
            WHERE id_brand='$id_brand'";
    
    //inserting into db if no error accures
    $mysqli->query($sql) or die ($mysqli->error);
    
    //setting session variables
    $_SESSION['message'] = "Record has been succesfully updated.";
    $_SESSION['msg_type'] = "info";
    
    //redirecting
    header ("location: brand-center.php");
  }
  else
  {
    //setting session variables
    $_SESSION['message'] = "Field must be filled out.";
    $_SESSION['msg_type'] = "danger";
 
    header ("location: brand-center.php?edit=$id_brand");
  }
  
}
