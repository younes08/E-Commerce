<?php 
    include "header.php";

    //check if an admin is connected 
    if (isset ($_SESSION["uid"])): 
        include "admin-action-fcts.php";

        $id = $_SESSION["uid"];
        
?>

<div class="container mx-auto" style="margin-top: 100px;">
    
    <div class="row" style="text-align: center; margin-bottom: 20px;"><h1 class="prod-title">Choose a payment method</h1></div>
    <hr class="colorgraph">
    <form action="buy-prod.php" method="POST">
        <input type="hidden" name = "id_cmd" value="<?php echo $_GET["id_cmd"]; ?>">
        <div class="form-check mb-3">
            <input class="form-check-input" value="cash" type="radio" name="pay-method" id="flexRadioDefault1">
            <label class="form-check-label" for="flexRadioDefault1">
                <span class = "payment-choice"><i class="bi bi-cash"></i></span>
                Cash In Delivery
            </label>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" value="cheque" type="radio" name="pay-method" id="flexRadioDefault2" checked>
            <label class="form-check-label" for="flexRadioDefault2">
                <span class = "payment-choice"><i class="bi bi-wallet"></i></span>
                Pay By Checks
            </label>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" value="credit" type="radio" name="pay-method" id="flexRadioDefault3">
            <label class="form-check-label" for="flexRadioDefault3">
                <span class = "payment-choice"><i class="bi bi-credit-card"></i></span>
                Credit Card
            </label>
        </div>
        <hr class="colorgraph">
        <div class="row mb-3 mx-auto">
            <div class="col-xs-12 col-md-6 mx-auto">
                <button type="submit" class="btn btn-block btn-lg" name="pay-confirm">Confirm</button>
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