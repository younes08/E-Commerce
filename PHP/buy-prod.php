<?php
include "database-connection.php";
include 'admin-action-fcts.php';

if (!isset ($_SESSION))
{
    session_start ();
}

////////////ADDING PRODUCT TO CART/////////////////
if (isset ($_POST['add-to-cart']))
{
    if (isset ($_SESSION["uid"]))
    {
        $id_prod = $_POST["prod-id"];
        if (!empty ($_POST['qte']))
        {
            if (filter_var($_POST['qte'], FILTER_VALIDATE_INT) &&
                $_POST['qte'] > 0)
            {   
                //check stock 
                $qte = $_POST["qte"];
                $sql = "SELECT qte_prod FROM product WHERE id_prod = '$id_prod'";
                $res = $mysqli->query ($sql) or die ($mysqli->error);
                $res = $res->fetch_assoc ();

                if ($res["qte_prod"] > 0)
                {
                    $new_qte_stock = $res["qte_prod"] - $qte;

                    if ($new_qte_stock >= 0)
                    {
                        //set variables                  
                        $date = date ("Y-m-d H:i:s");
                        $user_id = $_SESSION["uid"];
                        $status_id = 1;


                        ///////check if there is already unfinished cart purchase

                        $sql_active_cmd = "SELECT * 
                                            FROM command, info_cmd_status 
                                            WHERE id_cmd = id_cmd_FK 
                                            and id_user_FK = '$user_id' 
                                            AND id_cmd NOT IN (SELECT id_cmd_FK  
                                                                FROM info_cmd_status 
                                                                WHERE id_status_FK != 1)";

                        $active_cmd = $mysqli->query ($sql_active_cmd);

                        //if there is already a command in cart status (use it)
                        if (mysqli_num_rows ($active_cmd))
                        {
                            $active_cmd = $active_cmd->fetch_assoc ();
                            $cmd_id = $active_cmd["id_cmd"];

                            //check if there is the same product in the cart => only modify the quantity 
                            $sql_prod_check = "SELECT * 
                                                FROM command, cmd_line
                                                WHERE id_cmd = id_cmd_FK
                                                AND id_prod_FK = '$id_prod'
                                                AND id_cmd NOT IN (SELECT id_cmd_FK  
                                                                FROM info_cmd_status 
                                                                WHERE id_status_FK != 1)";
                            
                            $prod_exists = $mysqli->query ($sql_prod_check) or die ($mysqli->error);
                            
                            if (mysqli_num_rows ($prod_exists))
                            {
                                $prod_exists = $prod_exists->fetch_assoc ();
                                //adding new qte to the existing one
                                $cmd_qte = $prod_exists["quantity"] + $qte;

                                //check stock
                                $test_stock = $res["qte_prod"] - $cmd_qte;
                                if ($test_stock < 0)
                                {
                                    $_SESSION['message'] = "Only ".$res["qte_prod"]." items are left";
                                    $_SESSION['msg_type'] = "info";
                                    
                                    header ("location: product-view.php?prod-id=$id_prod");
                                    exit ();
                                }

                                $id_line = $prod_exists["id_line"];

                                $add_qte = "UPDATE cmd_line 
                                            SET quantity = '$cmd_qte'
                                            WHERE id_line = '$id_line'";
                                $mysqli->query ($add_qte) or die ($mysqli->error);

                                //setting session variables
                                $_SESSION['message'] = "Product quantity is updated.";
                                $_SESSION['msg_type'] = "success";
                                
                                header ("location: product-view.php?prod-id=$id_prod");
                                exit();
                            }
                        }
                        else //new command
                        {
                            //command table
                            $cmd_sql = "INSERT INTO command (id_user_FK) 
                                        VALUES ('$user_id')";
                            $mysqli->query ($cmd_sql) or die ($mysqli->error);

                            //current command id
                            $cmd_id = $mysqli->insert_id;
                            
                            //info command table
                            $info_sql = "INSERT INTO  info_cmd_status (date, id_cmd_FK, id_status_FK)
                                        VALUES ('$date', '$cmd_id', 1)";

                            $mysqli->query ($info_sql) or die ($mysqli->error); 
                        }

                        //cmd_line table
                        $line_sql = "INSERT INTO cmd_line (quantity, id_cmd_FK, id_prod_FK)
                                    VALUES ('$qte', '$cmd_id', '$id_prod')";

                        $mysqli->query ($line_sql) or die ($mysqli->error);

                        //setting session variables
                        $_SESSION['message'] = "Product added to cart.";
                        $_SESSION['msg_type'] = "success";
                        
                        header ("location: product-view.php?prod-id=$id_prod");
                    }
                    else//bigger quantity
                    {
                        //setting session variables
                        $_SESSION['message'] = "Only ".$res["qte_prod"]." items are left";
                        $_SESSION['msg_type'] = "info";
                        
                        header ("location: product-view.php?prod-id=$id_prod");
                    }
                }
                else //out of stock
                {
                    //setting session variables
                    $_SESSION['message'] = "Out of stock.";
                    $_SESSION['msg_type'] = "danger";
                    
                    header ("location: product-view.php?prod-id=$id_prod");
                }
            }
            else//Not valid qte
            {
                //setting session variables
                $_SESSION['message'] = "Quantity entered not valid.";
                $_SESSION['msg_type'] = "danger";
                
                header ("location: product-view.php?prod-id=$id_prod");
            }
            
        }
        else//empty field
        {
            //setting session variables
            $_SESSION['message'] = "Quantity field cannot be empty.";
            $_SESSION['msg_type'] = "danger";
            
            header ("location: product-view.php?prod-id=$id_prod");
        }

    }
    else//no login
    {
        //setting session variables
        $_SESSION['message'] = "You have to sign-in to purchase.";
        $_SESSION['msg_type'] = "danger";

        header ("location: sign-in.php");
    }
}

////////////////////EDIT PRODUCT QTE IN CART/////////////////////////

if (isset ($_POST['edit-qte']))
{
    if (isset ($_SESSION["uid"]))
    {
        if (!empty ($_POST["id_line"]) &&
            is_numeric($_POST['id_line'])
            )
        {
            //setting vars
            $id_line = $_POST["id_line"];

            if (!empty ($_POST['qte']))
            {
                if (filter_var($_POST['qte'], FILTER_VALIDATE_INT) &&
                    $_POST['qte'] > 0)
                {
                    //get cmd_line infos
                    $sql_line = "SELECT * FROM cmd_line WHERE id_line = '$id_line'";
                    $line = $mysqli->query ($sql_line);
                    $line = $line->fetch_assoc ();

                    //check stock 
                    $qte = $_POST["qte"];
                    $id_prod = $line['id_prod_FK'];
                    $sql = "SELECT qte_prod FROM product WHERE id_prod = '$id_prod'";
                    $res = $mysqli->query ($sql) or die ($mysqli->error);
                    $res = $res->fetch_assoc ();

                    if ($res["qte_prod"] > 0)
                    {
                        var_dump($qte);
                        var_dump($res["qte_prod"]);
                        $new_qte_stock = $res["qte_prod"] - $qte;
                        var_dump ($new_qte_stock);
                        if ($new_qte_stock >= 0)
                        {
                            //Update quantity 
                            $sql_update = "UPDATE cmd_line 
                                            SET quantity = '$qte'
                                            WHERE id_line = $id_line";
                            $mysqli->query ($sql_update) or die ($mysqli->error);
                            header ("location: command.php");
                        }
                        else//bigger quantity
                        {
                            //setting session variables
                            $_SESSION['message'] = "Only ".$res["qte_prod"]." items are left";
                            $_SESSION['msg_type'] = "info";
                            
                            header ("location: command.php");
                        }
                    }
                    else //out of stock
                    {
                        //setting session variables
                        $_SESSION['message'] = "Out of stock.";
                        $_SESSION['msg_type'] = "danger";
                        
                        header ("location: command.php");
                    }
                }
                else//Not valid qte
                {
                    //setting session variables
                    $_SESSION['message'] = "Quantity entered not valid.";
                    $_SESSION['msg_type'] = "danger";
                    
                    header ("location: command.php");
                }
                    
            }
            else//empty field
            {
                //setting session variables
                $_SESSION['message'] = "Quantity field cannot be empty.";
                $_SESSION['msg_type'] = "danger";
                
                header ("location: command.php");
            }
            
            }
        else //invalid cmd line id
        {
            header ("location: command.php");
        }
    }
    else//no login
    {
        //setting session variables
        $_SESSION['message'] = "You have to sign-in to purchase.";
        $_SESSION['msg_type'] = "danger";

        header ("location: sign-in.php");
    }
}

///////////////////////////DELETE PRODUCT FROM CART//////////////////////////

if (isset ($_GET["delete-prod-cart"]))
{
    if (isset ($_SESSION["uid"]))
    {
        if (!empty ($_GET["delete-prod-cart"]) &&
            is_numeric($_GET['delete-prod-cart']))
        {
            //get cmd_line infos
            $id_line = $_GET["delete-prod-cart"];

            //check if the order contained only the product to be removed
            //to delete the order itself
            $sql_check = "SELECT * FROM cmd_line WHERE id_line = '$id_line'";
            $res = $mysqli->query ($sql_check) or die ($mysqli->error);
            
            $res = $res->fetch_assoc ();
            $id_cmd = $res["id_cmd_FK"];

            $sql_check = "SELECT * 
                            FROM cmd_line, command 
                            WHERE  id_cmd = id_cmd_FK
                            AND id_cmd = '$id_cmd
                            '";
            $res = $mysqli->query ($sql_check) or die ($mysqli->error);

            if (mysqli_num_rows ($res) == 1) //deleting the order 
            {
                $sql_del = "DELETE FROM command WHERE id_cmd = '$id_cmd'";
                $mysqli->query ($sql_del) or die ($mysqli->error);    
            }
            else
            {
                //deleting the line -- product from command
                $sql_del = "DELETE FROM cmd_line WHERE id_line = '$id_line'";
                $mysqli->query ($sql_del) or die ($mysqli->error);
            }           
            
            //setting session variables
            $_SESSION['message'] = "Product has been removed.";
            $_SESSION['msg_type'] = "success";

            header ("location: command.php");
        }
    }
    else
    {
        //setting session variables
        $_SESSION['message'] = "You have to sign-in to purchase.";
        $_SESSION['msg_type'] = "danger";

        header ("location: sign-in.php");
    }
}

////////////////////////DELETE THE TOTALITY OF THE ORDER//////////////////////
if (isset ($_GET["delete-order"]))
{
    if (!empty ($_GET["delete-order"]) &&
        is_numeric ($_GET["delete-order"])
        )
        {
            //set vars
            $id_cmd = $_GET["delete-order"];
            
            $sql = "DELETE FROM command WHERE id_cmd = '$id_cmd'";
            $mysqli->query ($sql) or die ($mysqli->error);

            //setting session variables
            $_SESSION['message'] = "Cart is now empty.";
            $_SESSION['msg_type'] = "success";

        }
    header ("location: command.php");
}

///////////////////////PAYMENT/////////////////////////////////////
if (isset ($_POST["pay-confirm"]))
{
    if (!empty ($_POST["pay-method"]))
    {
        $id_cmd = $_POST["id_cmd"];

        if ((!empty ($id_cmd)) && 
            is_numeric ($id_cmd)
            )
        {
            //cash payment
            if ($_POST["pay-method"] == "cash")
            {
                $new_stat = 4;

                //stock order updates
                order_payment_changes ($id_cmd, $new_stat, $mysqli);
                header ("location: payment-success.php");
            }
            else
            {
                //check payment
                if ($_POST["pay-method"] == "cheque")
                {   
                    $new_stat = 2;

                    //stock order updates
                    order_payment_changes ($id_cmd, $new_stat, $mysqli);
                    header ("location: payment-success.php");
                }
                else
                {
                    //credit card payment
                    if ($_POST["pay-method"] == "credit")
                    {
                        header ("location: card-payment.php?id_cmd=$id_cmd");
                    }
                }
            }
        }
    }
}

//////////////ADDING CREDIT CARD///////////////////
if (isset ($_POST["add-card"]))
{
    if (!empty ($_POST["card-number"]))
    {
        $card_num = $_POST["card-number"];
        $id_cmd = $_POST["id_cmd"];
        
        $new_stat = 3;

        //stock order updates
        order_payment_changes ($id_cmd, $new_stat, $mysqli);
        
        //save credit card number
        $sql_credit = "INSERT INTO credit_payment(date, id_cmd_FK) 
                        VALUES ('$date', '$id_cmd')";
        $mysqli->query ($sql_credit) or die ($mysqli->error);

        header ("location: payment-success.php");
    }
}


//////////////PAYING BY CHECKS///////////////////
if (isset ($_POST['check-pay']))
{
    if (!empty ($_POST["check-val"]))
    {
        $check_val = $_POST['check-val'];
        $id_cmd = $_POST["id_cmd"];
        $total_price = $_POST["price"];
        $date = date ("Y-m-d H:i:s");

        //add check
        $check_pay_sql = "INSERT INTO cheque_payment(date, value, id_cmd_FK)
                                    VALUES ('$date', '$check_val', '$id_cmd')";
        
        $mysqli->query ($check_pay_sql);

        $check_sql = "SELECT *
                    FROM command, cheque_payment
                    WHERE id_cmd = id_cmd_FK
                    AND id_cmd = '$id_cmd'";

        $checks = $mysqli->query ($check_sql) or die ($mysqli->error);
        
        $payed = 0;
        while ($check = $checks->fetch_assoc ())
        {
            $payed += $check["value"];
        }

        $rest = $total_price - $payed;
    
        if ($rest <= 0)
        {
            //Add cmd status: done
            $sql_status_update = "INSERT INTO info_cmd_status(date, id_cmd_FK, id_status_FK)
                                    VALUES ('$date', '$id_cmd', 3)";

            $mysqli->query ($sql_status_update) or die ($mysqli->error);
            //setting session variables
            $_SESSION['message'] = "Order is payed.";
            $_SESSION['msg_type'] = "success";
        }
        header ("location: bank-check-center.php");
    }
}

////////////////CASH PAYMENT///////////////////
if (isset ($_POST['cash-pay']))
{
    if (!empty ($_POST["cash-val"]))
    {
        $cash = $_POST["cash-val"];
        $id_cmd = $_POST["id_cmd"];
        $total_price = $_POST["price"];
        $date = date ("Y-m-d H:i:s");

        //add check
        $cash_pay_sql = "INSERT INTO cash_payment(date, value, id_cmd_FK)
                                    VALUES ('$date', '$cash', '$id_cmd')";
        
        $mysqli->query ($cash_pay_sql);

        $cash_sql = "SELECT *
                    FROM command, cash_payment
                    WHERE id_cmd = id_cmd_FK
                    AND id_cmd = '$id_cmd'";

        $cash_bd = $mysqli->query ($cash_sql) or die ($mysqli->error);
        $cash_bd = $cash_bd->fetch_assoc ();
        $payed = $cash_bd["value"];

        $rest = $total_price - $payed;

        if ($rest <= 0)
        {
            //Add cmd status: done
            $sql_status_update = "INSERT INTO info_cmd_status(date, id_cmd_FK, id_status_FK)
                                    VALUES ('$date', '$id_cmd', 3)";

            $mysqli->query ($sql_status_update) or die ($mysqli->error);
            
            //setting session variables
            $_SESSION['message'] = "Order is payed.";
            $_SESSION['msg_type'] = "success";
        }
        else
        {
            //setting session variables
            $_SESSION['message'] = "Amount payed is unsufficient.";
            $_SESSION['msg_type'] = "danger";
        }
    }
    header ("location: cash-center.php");
}