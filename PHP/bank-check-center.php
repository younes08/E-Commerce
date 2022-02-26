<?php 
    include_once 'admin-action-category.php';
    include "dashboard-head.php"; 

    //check if an admin is connected 
    if (isset ($_SESSION["uid"])): 
        include "admin-action-fcts.php";

        $id = $_SESSION["uid"];
        //get admin status 
        $res = user_id_checker ($mysqli, $id);
        if ($res["is_admin"] == 1):
?>

<?php include "sidebar.php"; ?>

<body>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['msg_type'] ?>">
            <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']);
            ?>
        </div>
    <?php endif ?>
    <div class="container mx-auto" style="margin-top: 100px;">

        <table class="table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Mobile</th>
                        <th>Total Price</th>
                        <th>Add Checks</th>
                        <th>Bank Checks</th>
                        <th>Date Of Payment</th>
                    </tr>
                </thead>
                <?php include 'data-reader-bank-check.php' ?>
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