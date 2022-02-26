<?php session_start(); ?>

<head>
<title>E-Commerce</title>
<meta charset="UTF-8">
<meta name="description" content="e-commerce Website">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" 
integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
<!-- Bootstrap icon set -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

<link href="ressources/fontawesome-free-5.15.3-web/css/all.css" rel="stylesheet">
<link href="ressources/themify-icons/themify-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link href="style.css" rel="stylesheet">  
<link href="sign-style.css" rel="stylesheet"> 
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container-fluid">
        <?php 
            include "database-connection.php";
            if(isset($_SESSION['uid'])){
                $sql = "SELECT first_name, is_admin from user where user_id='$_SESSION[uid]'";
                $query = mysqli_query($mysqli,$sql);
                $row=mysqli_fetch_array($query);
                if($row['is_admin']==1){
                    echo "<a class=\"navbar-brand\" href=\"dashboard.php\"><span class='badge badge-light'>Dashboard</span></a>";
                }
            }
        ?>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <div class="btn-group">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Categories
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <div class="container dropdown-container">
                <div class="row align-items-start">
                        <?php 
                            $sql_cat = "SELECT cat_name FROM category";
                            $result = $mysqli->query ($sql_cat);
                            $result = mysqli_fetch_all ($result, MYSQLI_BOTH);
                            $counter = 0;
                            $cat_count = count ($result);
                            $end_div_state = 0;
                            if (!empty ($result)):
                                foreach ($result as $cat):                                   
                                    //close the container and open a new one  
                                    if (!($counter % 5)):

                                        if ($end_div_state): 
                                            echo "</div>";
                                            $end_div_state = 0;
                                        endif; 
                                        
                                        //create new container if there is still elements
                                        if ($cat_count - $counter):
                                            //if number of elements left lower than 5 create last container
                                            if ((($cat_count - $counter) <= 5)):
                                                echo "<div class='col cat-end'>";
                                            else:                       
                                                echo "<div class='col cat'>";
                                            endif;
                                            $end_div_state = 1;
                                        endif;
                                        
                                    endif;                            
                                    echo "<li><a class='dropdown-item' href=\"index.php?category=".$cat['cat_name']."\">" . $cat['cat_name'] . "</a></li>";
                                    $counter++;
                                endforeach;
                                echo "</div>";
                            endif;
                            ?>          
                </div>
            </ul>
            </div> 
        </ul>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item me-4">
            <a href="index.php" class="nav-link active">
                <i class="bi bi-house"></i>
            </a>
            </li>
             <!-- Default button -->
             <?php 
                if (!isset($_SESSION['uid']))
                {           
                    echo "<div class=\"btn-group\">
                        <button type=\"button\" class=\"btn btn-secondary dropdown-toggle\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                            <i class=\"bi bi-person\"></i>
                        </button>
                        <ul class=\"dropdown-menu dropdown-menu-end dropdown-menu-lg-start\" style=\"left: -100px;\"><!-- Change the left to right when toggled or better center the whole menu-->
                            <li><a class=\"dropdown-item\" href=\"sign-in.php\"> Sign-in</a></li>
                            <li><hr class=\"dropdown-divider\"></li>
                            <li><a class=\"dropdown-item\" href=\"register.php\"> Sign-up</a></li>
                            
                        </ul>
                        </div>";
                }
                else //user buttons
                {
                        echo "<li class='nav-item me-4'>
                        <a href=\"command.php\" class='nav-link active'>
                            <i class='bi bi-cart'></i>
                        </a>
                        </li>
                        <div class=\"btn-group\">
                        <button type=\"button\" class=\"btn btn-secondary dropdown-toggle\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                            <i class=\"bi bi-person\"></i>
                        </button>
                        <ul class=\"dropdown-menu dropdown-menu-end dropdown-menu-lg-start\" style=\"left: -100px;\"><!-- Change the left to right when toggled or better center the whole menu-->
                            <li><a class=\"dropdown-item\" href=\"edit-profile.php\"> Edit Profile</a></li>
                            <li><hr class=\"dropdown-divider\"></li>
                            <li><a class=\"dropdown-item\" href=\"command.php\"> Check order</a></li>
                            <li><hr class=\"dropdown-divider\"></li>
                            <li><a class=\"dropdown-item\" href=\"logout.php\"> Sign-out</a></li>
                            
                        </ul>
                        </div>";
                        
                }   
            ?>
        </ul>           
        </div>
    </div>
    </nav>
</header>