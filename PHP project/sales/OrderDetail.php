<?php 
session_start(); 
if($_SESSION["Position"]!= "Staff")
  header('location:../include/page/NotEnoughPermissionPage.php');
?>
<!doctype html>
<html lang="en">
  <head>
    <title>Customer Order Detail</title>
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/bootstrap-main.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
      function setHiddenBoxValue(value, price){
        document.getElementById("SaveItemID").value = value;
        document.getElementById("SaveItemPrice").value = price;
        var x = parseInt(document.getElementById("OrderAmount").value) - parseInt(document.getElementById("SaveItemPrice").value);
        displayNewAmount(x);
      }
      function submitUploadDataForm(){
        document.getElementById("UploadForm").submit();
      }
      function loadUploadDataMessage(MessageID, MessageTextID, Message){
          document.getElementById(MessageID).style.display = "block";
          document.getElementById(MessageTextID).innerHTML = Message;
      }
      function displayNewAmount(amount){
      $(document).ready(function(){
        $.ajax({
            type:"GET", 
            url: "http://127.0.0.1:8000/api/discountCalculator/" + amount,
            dataType:"JSON",
            success: function(JSON){
              document.getElementById("NewAmount").value = JSON["TotalAmount"];
            },
            error: function(err){
                alert("something get error");
            }
        });
      });
    }
    </script>
  </head>
<body>      
<?php include_once '../include/bootstrapImage.php'; 
    include_once 'include/salesHeader.php'; 
    ?>   

  <main class="d-flex flex-nowrap">
    <div class="b-example-divider b-example-vr"></div>
      <div class="d-flex flex-column flex-shrink-0 bg-light" style="width: 4.5rem">
        <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
          <li class="nav-item">
            <a href="PlaceOrder.php" class="nav-link py-3 border-bottom rounded-0" aria-current="page" title="Place Order" data-bs-toggle="tooltip" data-bs-placement="right">
              <svg class="bi pe-none" width="24" height="24" role="img" aria-label="POS"><use xlink:href="#POS"/></svg>
            </a>
          </li>
          <li>
            <a href="Order.php" class="nav-link active py-3 border-bottom rounded-0" title="Orders" data-bs-toggle="tooltip" data-bs-placement="right">
              <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Orders"><use xlink:href="#table"/></svg>
            </a>
          </li>
        </ul>
      </div>

    <!-- Content start -->
    <div class="MainContent col-lg-8 py-md-5 mx-auto"  >
      <div class=" align-items-center pb-3 mb-5 border-bottom">
      <?php
      $HaveError = false; // error checking
      require("../conn/conn.php");
      if(!empty($_GET['id'])){
          $TestConn = "SELECT * FROM `Orders`, `Staff`, `Customer` WHERE Orders.staffID = Staff.staffID AND Customer.customerEmail = Orders.customerEmail AND 
                  Orders.orderID = '{$_GET['id']}'";
          mysqli_query($conn, $TestConn) or die (mysqli_error($conn));
        if(mysqli_affected_rows($conn)==0)
          $HaveError = true;
      }else
        $HaveError = true;

      if($HaveError){
        include_once '../include/404NotFound.php';
      }else{
    ?>
        <a class="d-flex align-items-center text-dark text-decoration-none">
          <span class="fs-4">Order Detail #<?php echo $_GET['id']; ?> </span>
        </a>
      </div>
    
      <!-- alert message -->
      <?php include_once '../include/AlertMessage.php'; ?>

      <?php
          require_once("../conn/conn.php");
          if(!empty($_GET['SaveItemID'])){ // delete order item
            $sql3 = "DELETE FROM `ItemOrders` WHERE orderID = '{$_GET['id']}' AND itemID = '{$_GET['SaveItemID']}'";
            $rs3 = mysqli_query($conn, $sql3) or die (mysqli_error($conn));
            $sql6 = "UPDATE `Orders` SET `totalPrice`= '{$_GET['NewAmount']}' WHERE orderID = '{$_GET['id']}';";  //upload new price
            mysqli_query($conn, $sql6) or die(mysqli_error($conn));
          }else if(!empty($_POST['deliveryDate'])){
              $sql5 = "UPDATE `Orders` SET deliveryAddress='{$_POST['ShippingAddress']}', deliveryDate='{$_POST['deliveryDate']}' WHERE `orderID` = '{$_GET['id']}'";
              $rs5 = mysqli_query($conn, $sql5) or die (mysqli_error($conn));
              $rows = mysqli_affected_rows($conn);
              if($rows > 0){
                echo '<script type="text/javascript">loadUploadDataMessage("alertSuccess", "alertSuccessMessage",
                  "The Order detail has been uploaded successfully");</script>';
              }else
                  echo '<script type="text/javascript">loadUploadDataMessage("alertUnsuccess", "alertUnsuccessMessage", 
                  "Something get error");</script>';
           }
          // $sql = "SELECT * FROM `Orders`, `Staff`, `Customer` WHERE Orders.staffID = Staff.staffID AND Customer.customerEmail = Orders.customerEmail AND 
          //         Orders.orderID = '{$_GET['id']}'";
          // $rs = mysqli_query($conn, $sql) or die (mysqli_error($conn));
          $sql = "SELECT * FROM `Orders`, `Staff`, `Customer` WHERE Orders.staffID = Staff.staffID AND Customer.customerEmail = Orders.customerEmail AND 
                  Orders.orderID = '{$_GET['id']}'";
          $rs = mysqli_query($conn, $sql) or die (mysqli_error($conn));
          while($rc = mysqli_fetch_assoc($rs)){
          extract($rc);
          $orderStatus = $deliveryAddress==null?"Pick-up":"Delivery";
            echo <<<EOD
            <div class="DataOnOneLine">
              <label class="FirstColumn">Customer Name:</label>
              <label class="FirstData">$customerName</label>
              <label class="SecondColumn">Customer Email: </label>
              <label class="SecondData">$customerEmail</label>
          </div><br>

          <div class="DataOnOneLine">
            <label class="FirstColumn">Customer Phone:</label>
            <label class="FirstData">$phoneNumber</label>
            <label class="SecondColumn">SalesPerson ID:</label>
            <label class="SecondData">$staffID</label>
          </div><br>

          <div class="DataOnOneLine">
            <label class="FirstColumn">SalesPerson Name:</label>
            <label class="FirstData">$staffName</label>
            <label class="SecondColumn">Order Date&Time:</label>
            <label class="SecondData">$dateTime</label>
          </div><br>

          <div class="DataOnOneLine">
            <label class="FirstColumn">Order Status:</label>
            <label class="FirstData">$orderStatus</label>
          </div><br>

          <div class="border-bottom" style="margin-top:60px;">
            <h4>Order item list</h4>
          </div>
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Item Name</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
EOD;
                $sql2 = "SELECT Item.itemID, orderQuantity, Item.price, itemName FROM ItemOrders, 
                Item WHERE Item.itemID = ItemOrders.itemID AND orderID = '{$_GET['id']}' ORDER BY itemName DESC";
                $rs2 = mysqli_query($conn, $sql2) or die (mysqli_error($conn));
                $Amount = 0;
                while($rc2 = mysqli_fetch_assoc($rs2)){ 
                  extract($rc2); 
                  $total = $orderQuantity*$price;
                  $Amount += $total;
                  echo <<<EOD
                  <tr>
                    <td>$itemID</td>
                    <td>$itemName</td>
                    <td>$orderQuantity</td>
                    <td>$$price</td>
                    <td>$$total</td>
                    <td><button class="btn btn-info" onclick="setHiddenBoxValue($itemID, $total)" style="background-color: red;" data-bs-toggle="modal" data-bs-target="#DeleteOrderItemModal">X</button></td>
                  </tr>
EOD;
                }
                $Discount = $Amount - $totalPrice;
                echo <<<EOD
                <tr><td colspan="6"; style="color: blue;font-size: 15px;"><a href="AddOrderItem.php?id={$_GET['id']}&amount={$Amount}"><u>+Add new item</u></a></td></tr>
                <tr><td colspan="6">Total Price = $$totalPrice (Discount:$$Discount)</td></tr>
              </tbody>
            </table>
EOD; 
                if($orderStatus == "Delivery"){
                  $sql = "SELECT * FROM `Orders`, `Staff`, `Customer` WHERE Orders.staffID = Staff.staffID AND Customer.customerEmail = Orders.customerEmail AND 
                  Orders.orderID = '{$_GET['id']}'";
                  $rs2 = mysqli_query($conn, $sql) or die (mysqli_error($conn));
                  $rc = mysqli_fetch_assoc($rs2);
                  echo <<<EOD
                  <form id="UploadForm" name="UploadForm" action="{$_SERVER['PHP_SELF']}?id={$_GET['id']}" method="POST">
                    <div class="DataOnOneLine">
                      <label class="FirstColumn">Delivery Address:</label>
                      <textarea name="ShippingAddress" class="FirstData" style="left:30%;" id="ShippingAddress" rows="4" cols="35">{$rc["deliveryAddress"]}</textarea>
                      <label class="SecondColumn">Delivery Date:</label>
                      <label class="SecondData" style="left: 68%;"><input name="deliveryDate" type="date" id="datefield" min="{$rc["deliveryDate"]}" value="{$rc["deliveryDate"]}"></label>
                    </div><br>
                  </form>
EOD; 
                }
                    echo '<div class="putRightDown">
                      <a href="Order.php">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button></a>';
                        if($orderStatus == "Delivery")
                          echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="margin: 5px;" data-bs-whatever="@mdo">Upload</button>';
                    echo '</div>';
          }
            mysqli_free_result($rs);
            mysqli_free_result($rs2);
            mysqli_close($conn);
        ?> 
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Upload Order</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              Are you sure to upload this order?
          </div>
          <div class="modal-footer"">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" onclick="submitUploadDataForm()" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="DeleteOrderItemModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Delete Item</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="DeleteItemForm" name="DeleteItemForm" action="<?php echo "{$_SERVER['PHP_SELF']}"; ?>" method="GET">         
                Are you sure to delete this Item?<br>

                <div class="mb-3">
                  <input type="hidden" value="<?php echo $Amount; ?>" name="OrderAmount" id="OrderAmount">
                  <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']; ?>">
                  <input type="hidden" name="SaveItemID" id="SaveItemID">
                  <input type="hidden" name="SaveItemPrice" id="SaveItemPrice">
                  <label for="message-text" class="col-form-label">Current Amount:(include discount)</label>
                  <input type="text" value="<?php echo $totalPrice; ?>" class="form-control" id="CurrentAmount" name="CurrentAmount" readonly>
                </div>
                <div class="mb-3">
                  <label for="message-text" class="col-form-label">New Amount:(include discount)</label>
                  <input type="text" class="form-control" id="NewAmount" name="NewAmount" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <input type="submit" value="Delete" class="btn btn-primary">
            </form>
            </div>
          </div>
        </div>
        <?php } ?>
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