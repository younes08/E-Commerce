<!doctype html>
<html>
  
  <?php 
    include "header.php"; 
    include 'database-connection.php';
    include "data-reader-fcs.php";
    include "archive-category.php";
  ?>
  <div class="container-fluid products">
    
  <?php if (isset($_SESSION['name'])):?>
        <!-- alert on login -->
        <div class="alert alert-<?= $_SESSION['msg_type'] ?> alert-dismissible fade show">
            <?php 
                echo "Welcome ".$_SESSION['name']; 
                unset($_SESSION['name']);
            ?>
        </div>
    <?php endif; ?>

  <?php 
        if (
            isset ($_SESSION["msg_type_reg"]) && 
            isset ($_SESSION["message"])
          ):
  ?>
    <!-- alert on register -->
    <div class="alert alert-<?= $_SESSION['msg_type_reg'] ?> alert-dismissible fade show">
            <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']);
            ?>
        </div>
  <?php endif; ?>

    <div class="row">
      <?php 
        $chosen_cat = "";
        if (isset ($_GET['category']))
        {
          echo "<div class='row cat'><h1 class='category-title'>" . $_GET['category'] ."</h1></div>";
          $chosen_cat = $_GET['category'];
        }
        $sql = sql_by_cat ($mysqli, $chosen_cat);
        $result = $mysqli->query ($sql);

        //looping through the query
        while ($row = $result->fetch_assoc()):
      ?>
      <div class="col-md-4 col-sm-12 card-col">
        <div class="card">
          <?php 
            $id = $row['id_prod'];
            $sql_img = "SELECT media_path FROM product_media WHERE id_prod_FK='$id'";
            $result_media = $mysqli->query ($sql_img) or die ($mysqli->error);
            $media = mysqli_fetch_all($result_media, MYSQLI_BOTH);
            $brand_name = brand_finder_from_FK ($row['id_brand_FK'], $mysqli);
            if (!empty ($media)):
          ?>
            <!-- pormotion if no media -->
            <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
              <div class="carousel-inner">
                <?php if (!empty ($row['promotion'])):
                        if ($row['promotion']): 
                ?>
                          <span class="badge bg-danger">-<?=$row['promotion']?>%</span>
                <?php   
                        endif; 
                      endif;
                ?>
              <?php 
                $active_slide = reset ($media);
                echo "<div class='carousel-item active'>";
                echo '<img src="'. $active_slide['media_path'] .'" class="d-block w-100 img-thumbnail" alt="' . $row['prod_name'] . '">';
                echo "</div>";
                foreach (array_slice ($media, 1) as $picture): 
                  echo "<div class='carousel-item'>";
                    echo '<img src="' . $picture['media_path'] . '" class="d-block w-100 img-thumbnail" alt="' . $row['prod_name'] . '">';
                  echo "</div>";
                endforeach; 
              ?>
              </div>
            </div>
          <?php endif; ?>
          
          <div class="card-body">
            <?php 
              if (empty ($media)):
                if (!empty ($row['promotion'])):
                  if ($row['promotion']): 
            ?>
                    <span class="badge bg-danger" style="position: relative; margin-top: 0;">-<?=$row['promotion']?>%</span>
            <?php   
                  endif; 
                endif;
              endif;
            ?>
            <h5 class="card-title prod-name"><?= $row['prod_name'] ?></h5>
            <h6 class="card-title brand"><?= $brand_name ?></h6>

            <?php 
              if (!empty ($row['promotion'])):
                  if ($row['promotion']):
                    //calculating new price if there is a promotion
                    $sale_price = $row['unit_price'] * (1 - $row['promotion'] / 100); 
            ?>
              <p class="card-text price">
                <span style="text-decoration: line-through; color: #404040;"> <?= $row['unit_price'] ?> MAD </span> 
                <i class="bi bi-arrow-right"></i> 
                <?= $sale_price ?> MAD
              </p>
                  <?php endif; ?> 
            
            <?php else: ?>

              <p class="card-text price"><?= $row['unit_price'] ?> MAD</p>
            <?php endif; ?>
            <!-- <button class="btn buy" name="buy">Buy Now <i class="bi bi-cart-plus"></i> </button> -->
            <a href="product-view.php?prod-id=<?php echo $id; ?>"><button class="btn buy"><i class="bi bi-plus-square"></i></button></a>
        </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>

  <?php include "footer.html" ?>

  </body>
</html>
