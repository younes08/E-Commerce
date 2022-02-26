<?php 
    include "header.php";

    //check if an admin is connected 
    if (isset ($_SESSION["uid"])): 
        include "admin-action-fcts.php";

        $id = $_SESSION["uid"];
        
?>

<div class="container mx-auto" style="margin-top: 100px;">
    <form action="buy-prod.php" method="POST" class="form-group">
        <div class="col-xs-12 col-sm-6 col-md-4 mx-auto">
            <div class="form-group">
                <input type="hidden" name = "id_cmd" value="<?php echo $_GET["id_cmd"]; ?>">
                <label class="form-label"><i class="bi bi-credit-card"></i> Card Number</label> 
                <input type="text" name="card-number" class="form-control input-lg" placeholder="Card number...">
                <hr class="colorgraph">
                <div class="row mb-3 mx-auto">
                    <div class="col-xs-12 col-md-12 mx-auto">
                        <button type="submit" class="btn btn-block btn-lg" name="add-card"><i class="bi bi-plus-square"></i> Add card number</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php 
       
    else: 
        //redirecting if is not connected
        header ("location: sign-in.php");
    endif;
?>