<?php 
    include 'admin-action-user.php';
    //include 'database-connection.php';
    include "dashboard-head.php";
    include "sidebar.php"; 

    //check if an admin is connected 
    if (isset ($_SESSION["uid"])): 
        include "admin-action-fcts.php";

        $id = $_SESSION["uid"];
        //get admin status 
        $res = user_id_checker ($mysqli, $id);
        if ($res["is_admin"] == 1):
?>
    
    
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
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Active</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <?php include 'data-reader-user.php' ?>
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
