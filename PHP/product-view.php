<!doctype html>
<html>

<?php 
    include "header.php"; 
    include 'database-connection.php';
    include "data-reader-fcs.php";

    if (isset ($_GET["prod-id"])):
        $id = $_GET["prod-id"];
        
        $sql = "SELECT * FROM product WHERE id_prod = '$id'";
        $sql_img = "SELECT * FROM product_media WHERE id_prod_FK = '$id'";
        
        //get products imgs
        $res_img = $mysqli->query ($sql_img) or die ($mysqli->error);
        
        //get products informations
        $res_prod = $mysqli->query ($sql) or die ($mysqli->error);

        $media = mysqli_fetch_all($res_img, MYSQLI_BOTH);
        $active_slide = reset ($media);
        $res_prod = $res_prod->fetch_assoc ();
        //get brand name
        $brand_name = brand_finder_from_FK ($res_prod['id_brand_FK'], $mysqli);
        
?>

<div class="container-fluid prod-imgs">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['msg_type'] ?>">
                <?php 
                    echo $_SESSION['message']; 
                    unset ($_SESSION['message']);
                    unset ($_SESSION['msg_type']);
                ?>
            </div>
        <?php endif ?>
        <div class="row" style="text-align: center; margin-bottom: 20px;"><h1 class="prod-title"><?php echo $res_prod["prod_name"] ?></h1></div>

        <div class="row">
            <?php if (!empty ($media)): ?>
            <div class="col-md-4">
                <table class="table">
                    <tr>
                        <?php 
                            echo "<td class='active-img' colspan = '4'><img id='slid' src='". $active_slide["media_path"]."'/></td>";
                        ?>
                    </tr>
                    <tr>
                        <?php  
                            foreach ($media as $picture): 
                                echo "<td><img class='miniature' src='".$picture["media_path"]."' onclick='display_img (this)'/></td>";
                            endforeach;
                        ?>
                    </tr>
                </table>
                <script src="JS/product-displayer/product-displayer.js"></script>
            </div>
            <?php endif; ?> 
            <div class="col-md-8">
                <h3 class="brand" style="font-size: 25px;"><?php echo $brand_name; ?></h3>
                <hr class="colorgraph">
                <p>
                    <?php 
                        if (empty ($res_prod["descrip"])):
                            echo "No description";
                        else:
                            echo $res_prod["descrip"];
                        endif;   
                    ?>
                </p>
                <hr class="colorgraph">

                <br>
                <br>
                <form action="buy-prod.php" method="POST">
                    <label class="form-label">Quantity:</label>
                    <input style="width: 10%;" type='number' class='form-control mx-auto input-lg' name='qte' value="1"min="1">
                    <?php echo "<input name='prod-id' type='hidden' value = '".$id."'>"; ?>
                    <hr class="colorgraph">
                    <br>
                    <br>
                    <div class="col-xs-12 col-md-12">
                        <!-- check if product available -->
                        <?php if($res_prod["qte_prod"] >0 ): ?>
                            <button type="submit" class="btn btn-success btn-block btn-lg" name="add-to-cart">Add to cart <i class="bi bi-cart-plus"></i></button>
                        <?php else: ?>
                            <span class="badge badge-warning" style="font-size: 20px;">Out of stock</span>
                        <?php endif ?>   
                    </div>
                </form>
            </div>
        </div>   
</div>
<?php 
    endif; 
    include "footer.html";    
?>
