<?php
    include 'admin-action-brand.php'; 
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

    <div class="container">   
        <div class="row wrapper">
            <form class="form-group" name="brand_form" action="admin-action-brand.php" method="POST">
                    <input type="hidden" class="form-control" name="id" value="<?= $id; ?>">

                <div class="row mb-3">
                    <label class="form-label">New brand:</label>

                    <input type="text" class="form-control" value="<?= $update_brand_name ?>" name="new_brand" placeholder="Enter a brand...">
                </div>
                <div class="row mb-3">
                    <?php if (!$update_state): ?>
                        <button type="submit" class="btn btn-primary" name="add_brand_alone">
                            <i class="bi bi-plus-square"></i>
                        </button> 
                    <?php else: ?>
                        <button type="submit" class="btn btn-info" name="update_brand"><i class="bi bi-pencil-square"></i></button>
                    <?php endif; ?>
                </div>
        </form>
        </div>
        <!-- validation form -->
        <!-- <script src="JS/validations/form_validation.js"></script> -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Brand</th>
                    <th colspan=2>Actions</th>
                </tr>
            </thead>
            <?php include 'data-reader-brand.php' ?>
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