<?php 
    session_start ();
    include "database-connection.php";
    include "dashboard-head.php"; 

    //check if an admin is connected 
    if (isset ($_SESSION["uid"])): 
        include "admin-action-fcts.php";

        $id = $_SESSION["uid"];
        //get admin status 
        $res = user_id_checker ($mysqli, $id);
        if ($res["is_admin"] == 1):
?>

<body>
    <?php include "sidebar.php"; ?>
    <div class="container">
        <div class="row section-header">
            <div class="h1">Product dashboard</div>
        </div>
        <div class="row">

            <div class="col-md-4 dash-col">
                <a class="box-hlink" href="product-center.php">
                    <div class="option-box">
                        <span class="dash-icon"><i class="bi bi-bag"></i></span>
                        <p>Products</p>
                    </div>
                </a>
            </div>
        
            <div class="col-md-4 dash-col">
                <a class="box-hlink" href="brand-center.php">
                    <div class="option-box">
                        <span class="dash-icon"><i class="bi bi-tag"></i></span>
                        <p>brands</p>
                    </div>
                </a>
            </div>
        
            <div class="col-md-4 dash-col">
                <a class="box-hlink" href="category-center.php">
                    <div class="option-box">
                        <span class="dash-icon"><i class="bi bi-bookmark"></i></span>
                        <p>categories</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="row section-header">
            <div class="h1">accounts dashboard</div>
        </div>

        <div class="row">

            <div class="col-md-4 dash-col">
                <a class="box-hlink" href="user-center.php">
                    <div class="option-box">
                        <span class="dash-icon"><i class="ti-user"></i></span>
                        <p>accounts</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="row section-header">
            <div class="h1">In Pay Orders dashboard</div>
        </div>

        <div class="row">

            <div class="col-md-4 dash-col">
                <a class="box-hlink" href="cash-center.php">
                    <div class="option-box">
                        <span class="dash-icon"><i class="bi bi-cash"></i></span>
                        <p>Cash</p>
                    </div>
                </a>
            </div>

            <div class="col-md-4 dash-col">
                <a class="box-hlink" href="bank-check-center.php">
                    <div class="option-box">
                        <span class="dash-icon"><i class="bi bi-wallet"></i></span>
                        <p>Bank Check</p>
                    </div>
                </a>
            </div>
        </div>

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
