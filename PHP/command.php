<?php 
    include "header.php";

    //check if an admin is connected 
    if (isset ($_SESSION["uid"])): 
        include "admin-action-fcts.php";

        $id = $_SESSION["uid"];
?>
    
    <body>
        <div class="container mx-auto" style="margin-top: 100px;">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['msg_type'] ?>">
                <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);
                ?>
            </div>
        <?php endif ?>
            <table class="table" style="color: white;">
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>Product</th>
                        <th>Brand</th>
                        <th>Photos</th>
                        <th>Quantity</th>
                        <th>Delete Product</th>
                    </tr>
                </thead>
                <?php include 'data-reader-command.php' ?>
            </table>
        </div>
    </body>
<?php 
       
    else: 
        //redirecting if is not connected
        header ("location: sign-in.php");
    endif;
?>
