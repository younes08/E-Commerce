<?php include 'header.php'; ?>
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
    <?php endif ?>
    <div class="bgg">
      <div class="boxg">
        <h2>Register here</h2>
        <form name="myForm" method="post" class="form-group" action="account-actions.php" onsubmit="return validateForm()">
                <div class="row mb-3">
                  <input class="form-control mx-auto inpg" type="text" name="f_name" placeholder="First name ..." >
                </div>
                <div class="row mb-3">
                  <input class="form-control mx-auto inpg" type="text" name="l_name"placeholder="Last name ...">
                </div>
                <div class="row mb-3">
                  <input class="form-control mx-auto inpg" type="email" name="email" placeholder="Email ...">
                </div>
                <div class="row mb-3">
                  <input class="form-control mx-auto inpg" type="password" name="password" placeholder="Password ...">
                </div>
                <div class="row mb-3">
                  <input class="form-control mx-auto inpg" type="password" name="repassword" placeholder="Repassword ...">
                </div>
                <div class="row mb-3">
                  <input class="form-control mx-auto inpg" type="text" name="mobile" placeholder="Mobile Phone ...">
                </div>
                <div class="row mb-3">
                  <select class="form-control mx-auto inpg" name="city" id="">
                      <?php 
                          $sql = "SELECT * FROM city";
                          $cities = $mysqli->query($sql) or die ($mysqli->error);
                          
                          //looping through cities available
                          while($row = mysqli_fetch_array($cities))
                          {
                              echo "<option value=\"".$row[0]."\">".$row[1]."</option>";
                          }
                      ?>
                  </select>
                </div>
                <div class="row mb-3">
                  <input class="form-control mx-auto inpg" type="text" name="address"placeholder="Address ...">
                </div>
                <div class="row mb-3">
                  <button type="submit" class="btn mx-auto btn-info btng" name="register">Register</button>
                </div>
                <div class="row mb-3">
                  <a href="sign-in.php">You already have an account ?!</a>
                </div>

        </form>
      </div>
    </div>
  </div>
  <script>
    
    //var nameinput = document.getElementById("f_name");

    var vname = /^[a-zA-Z ]+$/;
    var emailValidation = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9]+(\.[a-z]{2,4})$/;
    var number = /^[0-9]{10}$/;

    function validateForm() {
        let x = document.forms["myForm"]["f_name"].value;
        let l_name = document.forms["myForm"]["l_name"].value;
        let email = document.forms["myForm"]["email"].value;
        let pwd = document.forms["myForm"]["password"].value;
        let repwd = document.forms["myForm"]["repassword"].value;
        let mobile = document.forms["myForm"]["mobile"].value;
        if ((x == "")||(l_name == "")||(email == "")||(pwd == "")||(repwd == "")||(mobile == "")) {
          alert("Al Inputs must be filled out");
          return false;
        }else{
          if(!x.match(vname)){
            alert("Please enter a valide first name");
            return false;
          }
          if(!l_name.match(vname)){
            alert("Please enter a valide last name");
            return false;
          }
          if(!email.match(emailValidation)){
            alert("Please enter a valide email");
            return false;
          }
          if(pwd.length<9){
            alert("The password is too wake");
            return false;
          }
          if(pwd != repwd){
            alert("The passwords doesn't match");
            return false;
          }
          if(!mobile.match(number)){
            alert("Your number is incorrect");
            return false;
          }
        }
        //if()
}
    
  </script>
</body>

<?php include "footer.html"; ?>