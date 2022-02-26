
<?php
    include "header.php";
    if (isset ($_SESSION["uid"])): 
        include "admin-action-fcts.php";

        $id = $_SESSION["uid"];
        //get admin status 
        $res = user_id_checker ($mysqli, $id);
        $sql = "SELECT * FROM user WHERE user_id = '$id'";
        $user_data = $mysqli->query ($sql) or die ($mysqli->error);
        $user_data = $user_data->fetch_assoc (); 
?>

    <div class="container mx-auto" style="margin-top: 150px;"> 
        <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?= $_SESSION['msg_type_reg'] ?> alert-dismissible fade show">
                    <?php 
                        echo $_SESSION['message']; 
                        unset($_SESSION['message']);
                    ?>
                </div>
        <?php endif ?>  
        <form method="POST" action="account-actions.php">
            <h2>Edit your profile.<br> <strong> Mr. 
                <?php 
                    echo $user_data['first_name']; 
                    echo "  "; 
                    echo $user_data['last_name'];
                ?>
            </strong><br><small>It's always easy</small></h2>

            <hr class="colorgraph">

            <div class="row mb-3">
                <div class="col-xs-12 col-sm-4 col-md-4">
                    <div class="form-group ">
                        <input type="text" name="f_name" id="first_name" class="form-control input-lg"  value="<?php echo $user_data['first_name']; ?>">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-8 col-md-4">
                    <div class="form-group">
                        <input type="text" name="l_name" id="last_name" class="form-control input-lg" value="<?php echo $user_data['last_name']; ?>">
                    </div>
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-md-8">
                    <input type="email" name="email" class="form-control input-lg" value="<?php echo $user_data['email']; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-xs-12 col-sm-6 col-md-2">
                    <div class="form-group">
                        <input type="text" name="mobile" class="form-control input-lg" value="<?php echo $user_data["mobile"] ?>" placeholder="Mobile">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-2">
                    <select class="form-control mx-auto inpg" name="city">
                        
                    <?php 
                        $id_city = $user_data["id_city_FK"];
                        $sql = "SELECT * FROM city WHERE id_city = '$id_city'"; 
                        $city = $mysqli->query($sql) or die ($mysqli->error);
                        $city = $city->fetch_assoc ();
                        echo "<option value=\"".$city["id_city"]."\">".$city["city_name"]."</option>";

                        $sql = "SELECT * FROM city WHERE id_city != '$id_city'";
                        $cities = $mysqli->query($sql) or die ($mysqli->error);
                        
                        //looping through cities available
                        while($city = mysqli_fetch_array($cities))
                        {
                            echo "<option value=\"".$city["id_city"]."\">".$city["city_name"]."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="form-group">
                        <input type="text" name="address" class="form-control input-lg" value="<?php echo $user_data['adresse'];?>" placeholder="Address">
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-xs-12 col-sm-6 col-md-8">
                    <div class="form-group">
                        <input type="password" name="oldpassword" class="form-control input-lg" placeholder="Old password">
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="form-group">
                        <input type="password" name="new_password" class="form-control input-lg" placeholder="New Password">
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="form-group">
                        <input type="password" name="new_repassword" class="form-control input-lg" placeholder="Confirm New Password">
                    </div>
                </div>
            </div>
            <hr class="colorgraph">
            <div class="row mb-3">
                <div class="col-xs-12 col-md-6">
                    <button type="submit" class="btn btn-success btn-block btn-lg" name="edit-profile">Edit profile</button>
                </div>
            </div>
        </form>   
    </div>

<?php 
    include "footer.html";
    else: 
        //redirecting if is not connected
        header ("location: sign-in.php");
    endif;
?>
