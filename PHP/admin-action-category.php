<?php
session_start (); 
include 'database-connection.php';

/////////////////////////////CREATING//////////////////////////////

//creating new category
if (isset ($_POST['add_cat_alone']))
{
  if (!empty ($_POST['new_cat']))
  {
    //getting name of the new category
    $new_cat = $_POST['new_cat'];

    //adding the category record
    $cat_sql = "INSERT INTO category (cat_name) values ('$new_cat')";
    $mysqli->query ($cat_sql) or die ($mysqli->error);

    //setting session variables
    $_SESSION['message'] = "New category has been succesfully added.";
    $_SESSION['msg_type'] = "info";
  }
  else
  {
    //setting session variables
    $_SESSION['message'] = "Field must be filled out.";
    $_SESSION['msg_type'] = "danger"; 
  }
  
  header ("location: category-center.php");
}

/////////////////////////////DELETING//////////////////////////////

//deleting the selected category
if (isset ($_GET['delete']))
{
  if (!empty ($_GET['delete']) &&
      is_numeric($_GET['delete']))
  {
    //getting the category id from the link
    $id_cat = $_GET['delete'];

    //check if the category is already used in a product
    $sql_checker = "SELECT count(*) as nbr_rec 
                    FROM product 
                    WHERE id_cat_FK='$id_cat'";

    $rec_count = $mysqli->query($sql_checker) or die ($mysqli->error);

    if ($rec_count)
    {
      //setting session variables
      $_SESSION['message'] = "This category cannot be deleted, it has already been used with a product.";
      $_SESSION['msg_type'] = "danger";  
      //redirecting
      header ("location: category-center.php");
    }

    $sql = "DELETE FROM category WHERE id_cat='$id_cat'";

    //deleting
    $mysqli->query($sql) or die ($mysqli->error);

    //setting session variables
    $_SESSION['message'] = "Record has been succesfully deleted.";
    $_SESSION['msg_type'] = "danger";
  }
  
  //redirecting
  header ("location: category-center.php");
}


/////////////////////////////UPDATING//////////////////////////////
$id = '';
$update_cat_name = '';
$update_state = false;

if (isset ($_GET['edit']))
{
  if (!empty ($_GET['edit']) &&
      is_numeric($_GET['edit']))
  {
    //setting variables
    $id_cat = $_GET['edit'];

    //mark as updating state
    $update_state = true;
    
    $sql = "SELECT * FROM category WHERE id_cat='$id_cat'";
    
    //selecting the record from db
    $result = $mysqli->query($sql) or die ($mysqli->error); 

    if (count ($result)==1)
    {
      $row = $result->fetch_array();

      //memorising the product data to pass then to the form for updates
      $id = $row['id_cat'];
      $update_cat_name = $row['cat_name'];
    }
  }  
  else
  {
    //redirecting if no id is selected
    header ("location: category-center.php");
  }
} 

if (isset ($_POST['update_cat']))
{
  $id_cat = $_POST['id'];
  if (!empty ($_POST['new_cat']))
    {
      //setting variables
      $cat_name = $_POST['new_cat'];
      
      $sql = "UPDATE category
              SET cat_name = '$cat_name'
              WHERE id_cat='$id_cat'";
      
      //inserting into db if no error accures
      $mysqli->query($sql) or die ($mysqli->error);
      
      //setting session variables
      $_SESSION['message'] = "Record has been succesfully updated.";
      $_SESSION['msg_type'] = "info";

      //redirecting
      header ("location: category-center.php");
    }
    else
  {
    //setting session variables
    $_SESSION['message'] = "Field must be filled out.";
    $_SESSION['msg_type'] = "danger";
 
    header ("location: category-center.php?edit=$id_cat");
  }
}
