<?php
//starting the session
session_start();

include("database-connection.php"); 
include("admin-action-fcts.php");
/////////////////////////////CREATING//////////////////////////////

if (isset ($_POST['save']))
{
  if (
      !empty ($_POST['unit_price']) &&
      !empty ($_POST['prod_name']) &&
      !empty ($_POST['category']) &&
      !empty ($_POST['brand']) 
      )
    {
      if ((filter_var($_POST['qte'], FILTER_VALIDATE_INT) ||
          filter_var($_POST['qte'], FILTER_VALIDATE_INT) === 0) &&
          $_POST['qte'] >= 0)
      {
        if (filter_var($_POST['unit_price'], FILTER_VALIDATE_INT) ||
            filter_var($_POST['unit_price'], FILTER_VALIDATE_FLOAT))
        {
          //setting variables
          $prod_name = $_POST['prod_name'];
          $unit_price = $_POST['unit_price'];
         
          $description = $_POST['description'];
          $promotion = $_POST['promotion'];
          $qte = $_POST['qte'];
          
          //looking for brand id
          $brand_id = brand_finder ($_POST['brand'], $mysqli);

          //looking for category id
          $cat_id = cat_finder ($_POST['category'], $mysqli);

          $sql = "INSERT INTO product (prod_name, unit_price, descrip, qte_prod, promotion, id_brand_FK, id_cat_FK) 
                  VALUES ('$prod_name', '$unit_price', '$description', '$qte', '$promotion', '$brand_id', '$cat_id')";

          //inserting into db if no error accures
          $mysqli->query($sql) or die ($mysqli->error);

          //get last inserted id
          $last_id = $mysqli->insert_id;
          
          ///////////File upload////////////
          $update_file = false;
          $statusMsg = file_uploader ($last_id, $mysqli, $update_file);
          //check if media is valid
          if ($statusMsg)
          {
            $_SESSION['message'] = $statusMsg ."<br>" . "But the record has been saved you can modify the picture later.";
            $_SESSION['msg_type'] = "danger";
          }
          else
          {
            //setting session variables
            $_SESSION['message'] = "Record has been succesfully saved.";
            $_SESSION['msg_type'] = "success";
          }   
        }   
        else
        { 
          $_SESSION['message'] = "Price isn't valid.";
          $_SESSION['msg_type'] = "danger";
        }
      }
      else
      {
        $_SESSION['message'] = "Quantity isn't valid.";
        $_SESSION['msg_type'] = "danger";  
      }  
  }
  else
  {
    $_SESSION['message'] = "Missing some of the fields: price, name, brand or category. Please fill all of them.";
    $_SESSION['msg_type'] = "danger";
  }
  //redirecting
  header ("location: product-center.php");
}

/////////////////////////////DELETING//////////////////////////////
if (isset ($_GET['delete']))
{
  if (!empty ($_GET['delete']) &&
      is_numeric($_GET['delete']))
  {
    //setting variables
    $id_prod = $_GET['delete'];
    $sql = "DELETE FROM product WHERE id_prod='$id_prod'";
    
    //deleting from db
    $mysqli->query($sql) or die ($mysqli->error);
    
    //setting session variables
    $_SESSION['message'] = "Record has been succesfully deleted.";
    $_SESSION['msg_type'] = "danger"; 
  }
    //redirecting
    header ("location: product-center.php"); 
}

/////////////////////////////UPDATING//////////////////////////////

//init variables for updates and new brands, categories
$id = '';
$update_prod_name = '';
$update_unit_price = '';
$update_description = '';
$update_promotion = '';
$update_qte = '';

if (isset ($_GET['edit']))
{
  if (!empty ($_GET['edit']) &&
      is_numeric($_GET['edit']))
  {
    //setting variables
    $id_prod = $_GET['edit'];

    $_SESSION['update_state'] = true;

    $sql = "SELECT * FROM product WHERE id_prod='$id_prod'";

    $sql_file = "SELECT media_path FROM product_media WHERE id_prod_FK='$id_prod'";
    
    //selecting the record from db
    $result = $mysqli->query($sql) or die ($mysqli->error); 
    $result_file = $mysqli->query($sql_file) or die ($mysqli->error); 

    if (count ($result)==1)
    {
      $row = $result->fetch_array();

      //memorising the product data to pass then to the form for updates
      $id = $row['id_prod'];
      $update_prod_name = $row['prod_name'];
      $update_unit_price = $row['unit_price'];
      $update_description = $row['descrip'];
      $update_qte = $row['qte_prod'];
      $update_promotion = $row['promotion'];
      $id_brand = $row['id_brand_FK'];
      $id_cat = $row['id_cat_FK'];
    }
  }
  else
  {
    //redirecting if no id is selected
    header ("location: product-center.php");
  }
}

if (isset ($_POST['update']))
{
  $id_prod = $_POST['id'];
  if (!empty ($_POST['unit_price']) &&
      !empty ($_POST['prod_name']) &&
      !empty ($_POST['category']) &&
      !empty ($_POST['brand']) 
    )
    {
      if ((filter_var($_POST['qte'], FILTER_VALIDATE_INT) ||
          filter_var($_POST['qte'], FILTER_VALIDATE_INT) === 0) &&
          $_POST['qte'] >= 0)
      {
        if ((filter_var($_POST['unit_price'], FILTER_VALIDATE_INT) ||
            filter_var($_POST['unit_price'], FILTER_VALIDATE_FLOAT)) &&
            $_POST['unit_price'] > 0)
          {
            //setting variables
            $prod_name = $_POST['prod_name'];
            $unit_price = $_POST['unit_price'];
            $description = $_POST['description'];
            $qte = $_POST['qte'];
            $promotion = $_POST['promotion'];

            //looking for cat id to update
            $cat_id = cat_finder ($_POST['category'], $mysqli);

            //looking for brand id update 
            $brand_id = brand_finder ($_POST['brand'], $mysqli);

            $sql = "UPDATE product 
                    SET prod_name = '$prod_name', unit_price = '$unit_price', 
                        descrip = '$description', qte_prod = '$qte', promotion = '$promotion', 
                        id_cat_FK = '$cat_id', id_brand_FK = '$brand_id'
                    WHERE id_prod='$id_prod'";

            //inserting into db if no error accures
            $mysqli->query($sql) or die ($mysqli->error);
            
            //saving files
            $update_file = true;
            $statusMsg = file_uploader ($id_prod, $mysqli, $update_file);

            //check if file uploaded is valid
            if ($statusMsg)
            {
              $_SESSION['message'] = $statusMsg ."<br>" . "But the record has been saved you can modify the picture later.";
              $_SESSION['msg_type'] = "danger";
            }
            else
            {
              //setting session variables
              $_SESSION['message'] = "Record has been succesfully saved.";
              $_SESSION['msg_type'] = "success";
            }
          }
          else
          {
              $_SESSION['message'] = "Price isn't valid.";
              $_SESSION['msg_type'] = "danger";
          }
      }
      else
      {
        $_SESSION['message'] = "Quantity isn't valid.";
        $_SESSION['msg_type'] = "danger"; 
      }
    }
    else
    {
      $_SESSION['message'] = "Missing some of the fields: price, name, brand or category. Please fill all of them.";
      $_SESSION['msg_type'] = "danger";
    }
    //redirecting
    header ("location: product-center.php");
}
/////////////////////////////adding the brand and category in product creation or update////////////////////////////
//adding brand and keeping the form filled
if (isset ($_POST['add_brand']) ||
    isset ($_POST['add_brand_on_update'])    
    )
{
  if (is_numeric($_POST['id']) ||
      empty ($_POST['id']))
  {
    if (!empty ($_POST['new_brand']))
    {
      if (isset ($_POST['add_brand_on_update']))
      {
        $_SESSION['update_state'] = true;
        $_SESSION['id'] = $_POST['id'];
      }
      //memorising variables when new brand is added
      $_SESSION['new_brand_state'] = true;
    
      //previously entered data
      $_SESSION['prod_name'] = $_POST['prod_name'];
      $_SESSION['unit_price'] = $_POST['unit_price'];;
      $_SESSION['description'] = $_POST['description'];
      $_SESSION['promotion'] = $_POST['promotion'];
      $_SESSION['qte'] = $_POST['qte'];
      $new_brand = $_POST['new_brand'];
      $_SESSION['brand_name'] = $new_brand;
    
      //adding the brand record
      $brand_sql = "INSERT INTO brand (brand_name) values ('$new_brand')";
      $mysqli->query($brand_sql) or die ($mysqli->error);
    
      //getting last brand inserted
      $last_brand_id = $mysqli->insert_id;
      $_SESSION['new_id_brand'] = $last_brand_id;
    
      //setting session variables
      $_SESSION['message'] = "New brand has been succesfully added.";
      $_SESSION['msg_type'] = "info";
    }
    else
    {
      //setting session variables
      $_SESSION['message'] = "Brand field must be filled out before adding it.";
      $_SESSION['msg_type'] = "danger"; 
    }
  }
  header ("location: product-center.php");  
}

//adding category and keeping the form filled
if (isset ($_POST['add_cat']) ||
    isset ($_POST['add_cat_on_update'])    
    )
{
  if (is_numeric($_POST['id']) 
      || empty ($_POST['id']))
  {
    if (!empty ($_POST['new_cat']))
    {
      if (isset ($_POST['add_cat_on_update']))
      {
        $_SESSION['update_state'] = true;
        $_SESSION['id'] = $_POST['id'];
      }
      //memorising variables when new category is added
      $_SESSION['new_cat_state'] = true;
    
      //previously entered data
      $_SESSION['prod_name'] = $_POST['prod_name'];
      $_SESSION['unit_price'] = $_POST['unit_price'];
      $_SESSION['description'] = $_POST['description'];
      $_SESSION['promotion'] = $_POST['promotion'];
      $_SESSION['qte'] = $_POST['qte'];
      $new_cat = $_POST['new_cat'];
      $_SESSION['cat_name'] = $new_cat;
    
      //adding the category record
      $cat_sql = "INSERT INTO category (cat_name) values ('$new_cat')";
      $mysqli->query($cat_sql) or die ($mysqli->error);
    
      //getting last category inserted
      $last_cat_id = $mysqli->insert_id;
      $_SESSION['new_id_cat'] = $last_cat_id;
    
      //setting session variables
      $_SESSION['message'] = "New category has been succesfully added.";
      $_SESSION['msg_type'] = "info";
     
    }
    else
    {
      //setting session variables
      $_SESSION['message'] = "Category field must be filled out before adding it.";
      $_SESSION['msg_type'] = "danger"; 
    }
  }
  header ("location: product-center.php");
}

////////////////Delete all pictures//////////////
if (isset ($_POST['delete-media']))
{
  $id_prod = $_POST['id'];

  // delete image file from database    
  $media_sql = "DELETE FROM product_media WHERE id_prod_FK = '$id_prod'";
  $mysqli->query($media_sql) or die ($mysqli->error);

  //setting session variables
  $_SESSION['message'] = "All pictures have been deleted. You can select new pictures.";
  $_SESSION['msg_type'] = "info"; 

  //redirecting
  header ("location: product-center.php?edit=$id_prod");
}