<?php
    
    if (isset ($_SESSION)):
        session_start ();
    endif;
        //check if an admin is connected 
    if (!isset ($_SESSION["uid"])):  
        include "header.php";
?>
    <link href="sign-register-style.css" rel="stylesheet"> 

    <body>
        <div class="container-fluid margined">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['msg_type'] ?> alert-dismissible fade show">
                <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>
            <div class="bgg">
                <div class="boxg">
                    <h2>login here</h2>
                    <form name="myForm"method="POST" class="form-group" name="user_form" action="account-actions.php"onsubmit="return validateForm()">        
                        <div class="row mb-3">
                            <input class="from-control mx-auto inpg" type="email" name="email" placeholder="Email..."> <br>
                        </div>
                        <div class="row mb-3">
                            <input class="from-control mx-auto inpg" type="password" name="password" placeholder="Password..."> <br>
                        </div>
                        <div class="row mb-3">
                            <button type="submit" class="btn mx-auto btng" name="login">Login</button>
                        </div>
                    </form>
                </div>
            </div>    
        </div>
        <script>
            var emailValidation = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9]+(\.[a-z]{2,4})$/;
            function validateForm() {
                let email = document.forms["myForm"]["email"].value;
                let pwd = document.forms["myForm"]["password"].value;
                if((email=='')||(password=='')){
                    alert("All filds must be full first");
                    return false;
                }else{
                    if(!email.match(emailValidation)){
                        alert("Please enter a valide email");
                        return false;
                    }
                }
            }
        </script>
    </body>
<?php
        include 'footer.html'; 
        
    else:
        header("location: index.php");
    endif;
?>