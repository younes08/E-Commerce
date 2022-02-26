<?php
    include_once 'admin-action-product.php'; 
    include "dashboard-head.php"; 
    //including sidebar
    include "sidebar.php"; 
        
    //check if an admin is connected 
    if (isset ($_SESSION["uid"])): 

        $id_user = $_SESSION["uid"];
        //get admin status 
        $res = user_id_checker ($mysqli, $id_user);
        if ($res["is_admin"] == 1):
?>

<body>
    <div class="container">

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['msg_type'] ?>">
                <?php 
                    echo $_SESSION['message']; 
                    unset ($_SESSION['message']);
                    unset ($_SESSION['mssg_type']);
                ?>
            </div>
        <?php endif ?>

        <div class="row wrapper">
            
            <form class="form-group" name="form-prod" action = "admin-action-product.php" method="POST" enctype="multipart/form-data">
                
                <?php if (isset ($_SESSION['id'])): ?>
                    <input type="hidden" class="form-control" name="id" value="<?= $_SESSION['id']; ?>">
                    <?php unset ($_SESSION['id']); ?>
                <?php else: ?>
                    <input type="hidden" class="form-control" name="id" value="<?= $id; ?>">
                <?php endif; ?>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Name</label>

                        <?php 
                            // filling form with previously entered data if exists or from an existing product on update
                            //////////// case 1: when the brand or category is created with a new product in creation
                            //////////// case 2: when the the product is updating we fill with data from record
                            //////////// case 3: empty form  
                            if ((
                                isset ($_SESSION['new_brand_state']) &&
                                isset ($_SESSION['prod_name'])
                                ) ||
                                (isset ($_SESSION['new_cat_state'])  &&
                                isset ($_SESSION['prod_name']))
                                )
                            { 
                                echo "<input type='text' value='" . $_SESSION['prod_name'] . 
                                    "' class='form-control' name='prod_name' placeholder='Enter the product name...'>";
                            }
                            elseif (isset ($_SESSION['update_state'])) 
                            {
                                if (($_SESSION['update_state']))
                                {
                                    echo "<input type='text' value='" . $update_prod_name . 
                                        "' class='form-control' name='prod_name' placeholder='Enter the product name...'>";
                                }
                            }
                            else
                            {
                                echo "<input type='text' class='form-control' name='prod_name' placeholder='Enter the product name...'>";
                            }
                            //freeing the data entered in creation
                            unset ($_SESSION['prod_name']);
                        ?>
                                
                    </div>    
                    <div class="col">
                        <label class="form-label">Price</label>
                        <?php if ((
                                isset ($_SESSION['new_brand_state']) &&
                                isset ($_SESSION['unit_price'])
                                ) ||
                                (isset ($_SESSION['new_cat_state']) &&
                                isset ($_SESSION['unit_price']))
                                )
                            { 
                                echo " <input type='number' value='" . $_SESSION['unit_price'] . 
                                    "' ' step='.01' class='form-control' name='unit_price' placeholder='Enter the price...'";
                            }
                            elseif (isset ($_SESSION['update_state'])) 
                            {
                                if ($_SESSION['update_state'])
                                {
                                    echo " <input type='number' value='" . $update_unit_price . 
                                    "' step='.01' class='form-control' name='unit_price' placeholder='Enter the price...'";
                                }
                            }
                            else
                            {
                                echo " <input type='number' ' step='.01' class='form-control' name='unit_price' placeholder='Enter the price...'>";
                            }
                            unset ($_SESSION['unit_price']);

                        ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Description</label> 
                        
                        <?php if ((
                                isset ($_SESSION['new_brand_state']) &&
                                isset ($_SESSION['description'])
                                ) ||
                                (isset ($_SESSION['new_cat_state']) &&
                                isset ($_SESSION['description']))
                                )
                            { 
                                echo "<input type='text' value='" . $_SESSION['description'] . 
                                    "' class='form-control' name='description' placeholder='Enter a description...'>";
                            }
                            elseif (isset ($_SESSION['update_state'])) 
                            {
                                if ($_SESSION['update_state'])
                                {
                                    echo "<input type='text' value='" . $update_description . 
                                    "' class='form-control' name='description' placeholder='Enter a description...'>";
                                }
                            }
                            else
                            {
                                echo "<input type='text' class='form-control' name='description' placeholder='Enter a description...'>";
                            }
                            unset ($_SESSION['description']);
                        ?>
                    </div>                
                    <div class="col">    
                        <label class="form-label">Promotion</label> 
                        <?php if ((
                                isset ($_SESSION['new_brand_state']) &&
                                isset ($_SESSION['promotion'])
                                ) ||
                                (isset ($_SESSION['new_cat_state']) &&
                                isset ($_SESSION['promotion']))
                                )
                            { 
                                echo "<input type='number' value='" . $_SESSION['promotion'] . 
                                    "' class='form-control' name='promotion' placeholder='Enter a promotion...'>";
                            }
                            elseif (isset ($_SESSION['update_state'])) 
                            {
                                if ($_SESSION['update_state'])
                                {
                                    echo "<input type='number' value='" . $update_promotion . 
                                    "' class='form-control' name='promotion' placeholder='Enter a promotion...'>";
                                }
                            }
                            else
                            {
                                echo "<input type='number' class='form-control' name='promotion' placeholder='Enter a promotion...'>";
                            }
                            unset ($_SESSION['promotion']);
                        ?>
                    </div>
                    <div class="col">    
                        <label class="form-label">Quantity</label> 
                        <?php if ((
                                isset ($_SESSION['new_brand_state']) &&
                                isset ($_SESSION['qte'])
                                ) ||
                                (isset ($_SESSION['new_cat_state']) &&
                                isset ($_SESSION['qte']))
                                )
                            { 
                                echo "<input type='number' value='" . $_SESSION['qte'] . 
                                    "' class='form-control' name='qte' placeholder='Enter a quantity...'>";
                            }
                            elseif (isset ($_SESSION['update_state'])) 
                            {
                                if ($_SESSION['update_state'])
                                {
                                    echo "<input type='number' value='" . $update_qte . 
                                    "' class='form-control' name='qte' placeholder='Enter a quantity...'>";
                                }
                            }
                            else
                            {
                                echo "<input type='number' class='form-control' name='qte' placeholder='Enter a quantity...'>";
                            }
                            unset ($_SESSION['qte']);
                        ?>
                    </div>
                </div>

                      <!---------------------------------------------------------drop-downs------------------------------------------>

                          <!-- -----------------------------------------brand--------------------------------------------------------- -->
                    <?php
                        require_once 'database-connection.php'; 
                        //displaying the previously chosen brand
                        if (isset ($_SESSION['update_state']) &&
                            !isset ($_SESSION['new_brand_state']) &&
                            !isset ($_SESSION['new_cat_state'])
                            )
                        {
                            $brand_selected = "SELECT * from brand WHERE id_brand='$id_brand'";
                            $brand_row = $mysqli->query ($brand_selected);
                            $brand_row = $brand_row->fetch_array ();
                            $brand_result = $brand_row['brand_name'];
                            $brand_sql = "SELECT * from brand WHERE id_brand!='$id_brand'";                            
                        }   
                        elseif (isset ($_SESSION['new_brand_state']) &&
                                isset ($_SESSION['new_id_brand']))   
                        {
                            //selecting the new band 
                            $id_new_brand = $_SESSION['new_id_brand'];
                            $brand_sql = "SELECT * FROM brand WHERE id_brand!='$id_new_brand'";
                            unset ($_SESSION['new_id_brand']);
                        }
                        else //normal display
                        {
                            $brand_sql = "SELECT * FROM brand";
                        }
                        $brand_results = $mysqli->query($brand_sql); 

                        //display if there is more than a brand or 
                        //when we are updating(brand surely exsiting with product)
                        //or when new brand is added since we know on at least is added
                        if (
                            (mysqli_num_rows ($brand_results)!=0 &&
                            !isset ($_SESSION['new_brand_state']) && 
                            !isset ($_SESSION['update_state'])) ||

                            isset ($_SESSION['update_state']) ||

                            isset ($_SESSION['new_brand_state'])
                            ):
                    ?>
                    <div class="row mb-3">
                        <div class="col-md-5">  
                            <label class="form-label" for="brand">Choose a brand:</label>
                            <select name="brand">
                            
                            <?php 
                                //placing the new created brand when creating new product or even updating old one
                                if (isset ($_SESSION['new_brand_state'])): 
                                    if (isset ($_SESSION['brand_name'])): 
                                       echo "<option value='".$_SESSION['brand_name']."'>".$_SESSION['brand_name']."</option>";
                                    endif;
                                endif; 
                            ?>


                            <?php 
                                //placing product's brand first when we re updating product
                                if(isset ($_SESSION['update_state']) &&
                                    !isset ($_SESSION['new_brand_state']) &&
                                    !isset ($_SESSION['new_cat_state'])
                                ): 
                               
                                echo "<option value='".$brand_result."'>".$brand_result."</option>";
                            endif; 
                            ?>

                            <?php 
                                while ($row = $brand_results->fetch_array()): 
                                //looping through all brands                                
                                echo "<option value='".$row['brand_name']."'>".$row['brand_name']."</option>";
                                
                            endwhile;
                            ?>
                                
                            </select>
                        </div>

                        <div class="col-md-2">
                            <p>Or Create one:</p>
                        </div>
                        
                        <div class="col-md-3">
                            <input type="text" class="form-control m-3" name="new_brand" placeholder="Enter a brand...">
                        </div>
                       
                        <div class="col-md-2">
                            <?php 
                                if (!isset ($_SESSION['update_state'])):
                                    //to add a brand when creating new product
                            ?>
                                <button type="submit" class="btn btn-primary" name="add_brand">
                                    <i class="bi bi-plus-square"></i>
                                </button>
                            <?php 
                                else:
                                // to add new brand while updating a product
                            ?>
                                <button type="submit" class="btn btn-primary" name="add_brand_on_update">
                                    <i class="bi bi-plus-square"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php 
                        //display only creation for new brand when no record exists
                        // and we're not updating or adding new one
                        else: 
                    ?>
                        <!-- creating new brand when it doesnt exist -->
                        <div class="row mb-3">
                            <div class="col">
                                <p>New brand:</p>
                            </div>

                            <div class="col">
                                <input type="text" class="form-control m-3" name="new_brand" placeholder="Enter a brand...">
                            </div>

                            <div class="col">
                                <button type="submit" class="btn btn-primary" name="add_brand">
                                    <i class="bi bi-plus-square"></i></i>
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
    



                            <!------------------------------------------------------------category----------------------------------- -->
                    <?php
                        require_once 'database-connection.php'; 
                        //displaying the previously chosen brand
                        if (isset ($_SESSION['update_state']) &&
                            !isset ($_SESSION['new_cat_state']) &&
                            !isset ($_SESSION['new_brand_state'])
                            )
                        {
                            $cat_selected = "SELECT * from category WHERE id_cat='$id_cat'";
                            $cat_row = $mysqli->query ($cat_selected);
                            $cat_row = $cat_row->fetch_array ();
                            $cat_result = $cat_row['cat_name'];
                            $cat_sql = "SELECT * from category WHERE id_cat!='$id_cat'";
                        }
                        elseif (isset($_SESSION['new_cat_state']) &&
                                isset ($_SESSION['new_id_cat']))
                        {
                            //selecting the new category 
                            $id_new_cat = $_SESSION['new_id_cat'];
                            $cat_sql = "SELECT * FROM category WHERE id_cat!='$id_new_cat'";
                            unset ($_SESSION['new_id_cat']);  
                        }
                        else //normal display
                        {
                            $cat_sql = "SELECT * from category";
                        }    
                        

                        $cat_results = $mysqli->query($cat_sql);
                        //display if there is more than a cat or 
                        //when we are updating(cat surely exsiting with product)
                        //or when new cat is added since we know on at least is added
                        if (
                            (mysqli_num_rows ($cat_results)!=0 && 
                            !isset ($_SESSION['new_cat_state']) && 
                            !isset ($_SESSION['update_state'])) ||

                            isset ($_SESSION['update_state']) ||
                            
                            isset ($_SESSION['new_cat_state'])
                            ):
                    ?>
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <label for="category">Choose a category:</label>
                            <select name="category">

                            <?php 
                                //placing the new created category when creating new product or even updating old one
                                if (isset ($_SESSION['new_cat_state'])): 
                                    if (isset ($_SESSION['cat_name'])): 
                                        echo "<option value='".$_SESSION['cat_name']."'>".$_SESSION['cat_name']."</option>";
                                    endif;
                                endif; 
                            ?>

                            <?php 
                                //placing product's cat first when we re updating product
                                if(isset ($_SESSION['update_state']) &&
                                    !isset ($_SESSION['new_cat_state']) &&
                                    !isset ($_SESSION['new_brand_state'])
                                ):
                                    echo "<option value='".$cat_result."'>".$cat_result."</option>";
                                endif;
                            ?>

                            <?php 
                                while ($row = $cat_results->fetch_array()):
                                //looping through all brands

                                echo "<option value='".$row['cat_name']."'>".$row['cat_name']."</option>";
                            
                                endwhile;
                            ?>

                            </select>
                        </div>
                        <div class="col-md-2">
                            <p>Or Create one:</p>
                        </div>

                        <div class="col-md-3">
                            <input type="text" class="form-control m-3" name="new_cat" placeholder="Enter a category...">
                        </div>
   
                        <div class="col-md-2">
                            <?php 
                                if (!isset ($_SESSION['update_state'])):
                                    //to add a brand when creating new product
                            ?>
                                <button type="submit" class="btn btn-primary" name="add_cat">
                                    <i class="bi bi-plus-square"></i>
                                </button>  
                            <?php 
                                else:// to add new brand while updating a product
                            ?>
                                <button type="submit" class="btn btn-primary" name="add_cat_on_update">
                                    <i class="bi bi-plus-square"></i>
                                </button>  
                            <?php endif; ?>
                        </div>
                    </div>

                        <?php else: ?>
                            <div class="row mb-3">
                                <div class="col">
                                    <p>New category:</p>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control m-3" name="new_cat" placeholder="Enter a category...">
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-primary" name="add_cat">
                                        <i class="bi bi-plus-square"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                        
            

                    <!-- -------------------file upload----------------------------------- -->
                <div class="row mb-3">
                    <div class="col md-8">
                        <div class="input-group mb-3 mt-2 mb-2">
                            <input type="file" class="form-control" id="inputGroupFile02" name="files[]" multiple>
                        </div>
                    </div>
                    <?php if (isset ($_SESSION['update_state'])): ?>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-danger mb-2 mt-2" name="delete-media">Delete pictures</button>
                        </div>
                    <?php endif; ?> 
                </div>

                    
                <!-- submit buttons -->
                <?php if (!isset ($_SESSION['update_state'])): 
                        
                ?>
                    <div class="row mb-3">
                        <button type="submit" class="btn btn-primary" name="save"><i class="bi bi-save"></i></button>
                    </div>
                <?php else: ?>
                    <div class="row mb-3">
                        <button type="submit" class="btn btn-info" name="update"><i class="bi bi-pencil-square"></i></button>
                    </div>
                <?php 
                    endif; 
                    
                    //unsetting the state variable
                    if (isset ($_SESSION['new_brand_state'])) {unset ($_SESSION['new_brand_state']);}
                    if (isset ($_SESSION['new_cat_state'])) {unset ($_SESSION['new_cat_state']);}
                    if (isset ($_SESSION['update_state'])) {unset ($_SESSION['update_state']);}
                ?>

            </form>
            <!-- validation form -->
            <!-- <script src="JS/validations/form_validation.js"></script> -->
        </div>
    </div>
    </div>
    <div class="container">
        <!-- data display -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Promotion</th>
                    <th>brand </th>
                    <th>Category </th>
                    <th>Photos </th>
                    <th colspan=2>Actions </th>
                </tr>
            </thead>
            <?php include 'data-reader-product.php' ?>
        </table>    
    </div>
</body>

<?php 
        else:
            //redirecting if is non admin
            header ("location: index.php");
        endif;
    else: 
        //redirecting if is not connected
        header ("location: sign-in.php");
    endif;
?>
