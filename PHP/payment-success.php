<?php 
    include "header.php";

    //check if an admin is connected 
    if (isset ($_SESSION["uid"])): 
        include "admin-action-fcts.php";

        $id = $_SESSION["uid"];

?>

<div class="container mx-auto" style="margin-top: 100px;">
    <div class="row" style="text-align: center; margin-bottom: 20px;">
        <h1 class="prod-title">We'll call you back for the delivery
            <br>Thank You for choosing us! <i class="bi bi-hand-thumbs-up"></i>
        <h1>
    </div>

    <div class="row mx-auto">
        <div class="col"><i class="bi bi-cart-check success-pay"></i></div>
        
        <div class="col"><i class="bi bi-check2-circle success-pay"></i></div>
        <div class="col"><i class="bi bi-caret-right success-pay-arrow"></i></div>
        <div class="col"><i class="bi bi-truck success-pay"></i></div>
    </div>
    <hr class="colorgraph">
    <div class='row'>
        <a href="index.php">
            <button class="btn btn-block btn-lg"><i class='bi bi-house'></i></button>
        </a>
    </div>
</div>

<?php 
       
    else: 
        //redirecting if is not connected
        header ("location: sign-in.php");
    endif;
?>
