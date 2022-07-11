<?php 
session_start(); 
if($_SESSION["Position"]!= "Manager")
  header('location:../include/page/NotEnoughPermissionPage.php');
?>
<!doctype html>
<html lang="en">
  <head>
    <title>Product Item List</title>
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/bootstrap-main.css" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
  </head>
  <script>
    function loadUploadDataMessage(MessageID, MessageTextID, Message){
      document.getElementById(MessageID).style.display = "block";
      document.getElementById(MessageTextID).innerHTML = Message;
    }
    function setModalValue(id){
      document.getElementById("Delete").href="ProductItem.php?id="+id; 
    }
  </script>
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
    <div class="MainContent col-lg-8 py-md-5 mx-auto" style="width:80%" >
      <div class="d-flex align-items-center pb-3 mb-5 border-bottom">
        <a h\class="d-flex align-items-center text-dark text-decoration-none">
          <span class="fs-4">Product Item List
            <button class="btn btn-primary" style="margin-left:20px;" data-bs-toggle="modal" data-bs-target="#AddItem">
              <svg class="bi bi-volume-mute-fill" fill="White" width="24" height="24" role="img" aria-label="Add"><use xlink:href="#Add"/></svg>
              Add Item
            </button>
          </span>
        </a>
      </div>

      <!-- add item form -->
      <div class="modal fade" id="AddItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Add New Item</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="myForm" name="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="mb-3">
                  <label for="recipient-name" class="col-form-label">Item Name:</label>
                  <input type="text" class="form-control" id="itemName" name="itemName" required>
                </div>
                <div class="mb-3">
                  <label for="message-text" class="col-form-label">Item Price:</label>
                  <input type="number" class="form-control" id="itemPrice" name="itemPrice" required>
                </div>
                <div class="mb-3">
                  <label for="message-text" class="col-form-label">Stock Quantity:</label>
                  <input type="number" class="form-control" id="StockQuantity" name="StockQuantity" required>
                </div>
                <div class="mb-3">
                  <label for="message-text" class="col-form-label">Item Description:</label>
                  <textarea class="form-control" id="ItemDescription" name="ItemDescription" required></textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="Add Item"></button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <?php include_once '../include/AlertMessage.php'; ?>
      <?php
        if(!empty($_POST)){ //add product item
            extract($_POST);
            require("../conn/conn.php");
            $sql = "SELECT * FROM `Item` ORDER BY itemID DESC";
            $rs = mysqli_query($conn, $sql) or die (mysqli_error($conn));
            $rc = mysqli_fetch_assoc($rs);
            $LastItemID = $rc["itemID"]+=1;  // generate primary key from last number of orderID

            $sql2 = "INSERT INTO Item       
                  VALUES('$LastItemID',
                      '{$itemName}',
                      '{$ItemDescription}',
                      '{$StockQuantity}',
                      '{$itemPrice}'
                      )";
                  mysqli_query($conn, $sql2) or die(mysqli_error($conn));
                  $rows = mysqli_affected_rows($conn);
                  if($rows > 0){
                    echo '<script type="text/javascript">loadUploadDataMessage("alertSuccess", "alertSuccessMessage",
              "The new product item has been added");</script>';
                    
                  }else
                  echo '<script type="text/javascript">loadUploadDataMessage("alertUnsuccess", "alertUnsuccessMessage", 
              "Something get error");</script>';
            mysqli_close($conn);
          }
      ?>


      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th scope="col">Item Name</th>
            <th scope="col">Stock Quantity</th>
            <th scope="col">Price</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <?php
            require("../conn/conn.php");
            $sql = "SELECT * from Item";
            $rs = mysqli_query($conn, $sql) 
              or die (mysqli_error($conn));
            while($rc = mysqli_fetch_assoc($rs)){
              echo "<tr>";
              echo "<td>" . $rc["itemID"] . "</td>";
              echo "<td>". $rc["itemName"] . "</td>";
              echo "<td>". $rc["stockQuantity"] . "</td>";
              echo "<td>$". $rc["price"] . "</td>";
              echo "<td><a href='ItemDetail.php?id={$rc["itemID"]}'><button type='button' class='btn btn-primary'>Edit</button></a></td>";
              echo "</tr>";
            }
            mysqli_free_result($rs);
            mysqli_close($conn);
          ?>
        </tbody>
      </table>
    </div>
  </main>
  <!-- content end -->
  
  <!-- footer setting -->
  <?php include_once '../include/Footer.php';?>
</div>
</body>
<script src="../javascript/bootstrap.bundle.min.js"></script>
  <script src="../javascript/script.js"></script>
</html>