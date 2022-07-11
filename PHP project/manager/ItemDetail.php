<?php 
session_start(); 
if($_SESSION["Position"]!= "Manager")
  header('location:../include/page/NotEnoughPermissionPage.php');
?>
<!doctype html>
<html lang="en">
  <head>
    <title>Customer Order Detail</title>
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/bootstrap-main.css" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
    <script>
      function loadUploadDataMessage(MessageID, MessageTextID, Message){
        document.getElementById(MessageID).style.display = "block";
        document.getElementById(MessageTextID).innerHTML = Message;
      }
      function setModalValue(id){
        document.getElementById("Delete").href="ProductItem.php?id="+id; 
      }
    </script>
  </head>

<body>      
<?php include_once '../include/bootstrapImage.php'; 
          include_once 'include/ManagerHeader.php';
    ?>     

  <main class="d-flex flex-nowrap">
    <div class="b-example-divider b-example-vr"></div>
      <div class="d-flex flex-column flex-shrink-0 bg-light" style="width: 4.5rem;">
        <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
          <li class="nav-item">
            <a href="ProductItem.php" class="nav-link active py-3 border-bottom rounded-0" aria-current="page" title="Product Item" data-bs-toggle="tooltip" data-bs-placement="right">
              <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Item"><use xlink:href="#Item"/></svg>
            </a>
          </li>
          <li>
            <a href="MonthlyReport.php" class="nav-link py-3 border-bottom rounded-0" title="Monthly Report" data-bs-toggle="tooltip" data-bs-placement="right">
              <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Chart"><use xlink:href="#Chart"/></svg>
            </a>
          </li>
          <li>
            <a href="Customer.php" class="nav-link py-3 border-bottom rounded-0" title="Customer" data-bs-toggle="tooltip" data-bs-placement="right">
              <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Customer"><use xlink:href="#Customer"/></svg>
            </a>
          </li>
        </ul>
      </div>

    <!-- Content start -->
    <div class="MainContent col-lg-8 py-md-5 mx-auto"  >
    <!-- alert message -->
    <?php include_once '../include/AlertMessage.php'; ?>
    <?php 
      require_once('../conn/conn.php');
      if(!empty($_POST)){ // update database data
        extract($_POST);
        $sql = "UPDATE Item SET itemName='$itemName', itemDescription='$Description', stockQuantity='$qty', price='$itemPrice'  WHERE itemID='{$_GET['id']}'";
        mysqli_query($conn, $sql) or die(mysqli_error($conn));
        $rows = mysqli_affected_rows($conn);
        if($rows > 0){
          echo '<script type="text/javascript">loadUploadDataMessage("alertSuccess", "alertSuccessMessage",
            "The Product item has been uploaded successfully");</script>';
        }else
            echo '<script type="text/javascript">loadUploadDataMessage("alertUnsuccess", "alertUnsuccessMessage", 
            "Something get error");</script>';
      }

      $HaveError = false; // error checking
      if(!empty($_GET['id'])){
        $sql = "SELECT * from Item WHERE itemID = '{$_GET['id']}'";
        $rs = mysqli_query($conn, $sql) or die (mysqli_error($conn));
        if(mysqli_affected_rows($conn)==0)
          $HaveError = true;
      }else
        $HaveError = true;

      if($HaveError){
        include_once '../include/404NotFound.php';
      }else{
      ?>
      <div class=" align-items-center pb-3 mb-5 border-bottom">
        <a class="d-flex align-items-center text-dark text-decoration-none">
          <span class="fs-4">Item Detail #<?php echo $_GET['id'] ?> </span>
        </a>
      </div>
      <form id="myForm" name="form" action="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $_GET["id"] . "" ?>" method="POST">
        <?php
          while($rc = mysqli_fetch_assoc($rs)){
          extract($rc);
            echo <<<EOD
              <div>
                <div style="margin-top:20px">Item Name:<input type="text" class="form-control" name="itemName" id="itemName" value="$itemName" required></div>
                <div style="margin-top:20px">Item Price($):<input type="number" class="form-control" min="1" name="itemPrice" value="$price" required></div>
                <div style="margin-top:20px">Stock Quantity:<input type="number" class="form-control" min="0" name="qty" value="$stockQuantity" required></div>
                <div style="margin-top:20px">Item Description:<textarea class="form-control" name="Description" id="Description" required>$itemDescription</textarea></div>
              </div>
EOD;
          }
            mysqli_free_result($rs);
            mysqli_close($conn);
        ?>   

          <div class="putRightDown">
            <a href="ProductItem.php">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button></a>
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Upload</button>
          </div>
        
          <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Upload Order</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure to upload this order?
                </div>
                <div class="modal-footer"">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <input type="submit" class="btn btn-primary" data-bs-dismiss="modal" value="Save">
                </div>
              </div>
            </div>
          </div>
          </div>
        </main>
      </form>
  <!-- content end -->
  
  <!-- footer setting -->
  <?php include_once '../include/Footer.php';?>
  <?php } ?>
</div>
</body>
<script src="../javascript/bootstrap.bundle.min.js"></script>
<script src="../javascript/script.js"></script>
</html>