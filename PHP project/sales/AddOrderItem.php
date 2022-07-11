<?php
session_start(); 
if($_SESSION["Position"]!= "Staff")
  header('location:../include/page/NotEnoughPermissionPage.php');
  if(!empty($_POST)){
    require("../conn/conn.php");
    extract($_POST);
    $sql2 = "SELECT * FROM Item WHERE itemID = {$_POST['itemID']}";
    $rs2 = mysqli_query($conn, $sql2);
    $rc = mysqli_fetch_assoc($rs2);
  
    if((int)$rc['stockQuantity'] < (int)$itemQty){  // when stock quantity is not enough, display error
      echo '<script type="text/javascript">alert("Stock did not have enough item quantity!!");</script>';
    }
    else{
      $sql = "INSERT INTO `ItemOrders`(`orderID`, `itemID`, `orderQuantity`, `price`) VALUES ({$_GET['id']}, $itemID, $itemQty, $itemPrice)";
      $rs = mysqli_query($conn, $sql);
      $sql4 = "UPDATE `Orders` SET `totalPrice`= '{$NewAmount}' WHERE orderID = '{$_GET['id']}';";  //upload new price
      mysqli_query($conn, $sql4) or die(mysqli_error($conn));
      $sql3 = "UPDATE `Item` SET `stockQuantity`= stockQuantity-{$itemQty} WHERE itemID = '{$itemID}';";  //upload stock qty
      mysqli_query($conn, $sql3) or die(mysqli_error($conn));
      if(mysqli_affected_rows($conn)>=0){
        header("Location: ./OrderDetail.php?id={$_GET['id']}");
        exit();
      }
      $sql4 = "SELECT SUM(price) AS newPrice FROM ItemOrders WHERE orderID = '{$_GET['id']}';";
      $rs = mysqli_query($conn, $sql4) or die(mysqli_error($conn));
      $rc3 = mysqli_fetch_assoc($rs);
      extract($r3);
    }
  }
?>
<!doctype html>
<html lang="en">
  <head>
    <title>Add Order Item List</title>
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/bootstrap-main.css" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
  </head>
<body>     
<?php include_once '../include/bootstrapImage.php'; 
    include_once 'include/salesHeader.php'; 
    ?>      

  <main class="d-flex flex-nowrap">
    <div class="b-example-divider b-example-vr"></div>
      <div class="d-flex flex-column flex-shrink-0 bg-light" style="width: 4.5rem;">
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
    <div class="MainContent col-lg-8 py-md-5 mx-auto" style="width:82%">
      <div class="d-flex align-items-center pb-3 mb-5 border-bottom">
        <a h\class="d-flex align-items-center text-dark text-decoration-none">
          <span class="fs-4">Product Item List</span>
        </a>
      </div>

      <!-- alert message -->
      <?php include_once '../include/AlertMessage.php'; ?>
      
      <?php
        if(!empty($_POST))
        echo '<script type="text/javascript">loadUploadDataMessage("alertUnsuccess", "alertUnsuccessMessage", 
        "Stock did not have enough item quantity!!");</script>'
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

            function arrayNotInstring($str, array $arr) {
              foreach($arr as $arr_value) {
                  if (stripos($str,$arr_value) !== false) return false; 
              }
              return true;
            }

            require("../conn/conn.php");
            $array = array();
            $sql2 = "SELECT * from ItemOrders WHERE orderID = '{$_GET['id']}'";
            $rs2 = mysqli_query($conn, $sql2) or die (mysqli_error($conn));
            while($row = mysqli_fetch_assoc($rs2)){
              $array[] = $row["itemID"];
            }

            $sql = "SELECT * from Item WHERE stockQuantity > 0";
            $rs = mysqli_query($conn, $sql) or die (mysqli_error($conn));
            while($rc = mysqli_fetch_assoc($rs)){
              if(arrayNotInstring($rc["itemID"],$array)){
                echo "<tr>";
                echo "<td>" . $rc["itemID"] . "</td>";
                echo "<td>". $rc["itemName"] . "</td>";
                echo "<td>". $rc["stockQuantity"] . "</td>";
                echo "<td>$". $rc["price"] . "</td>";
                echo "<td><button type='button' onclick='setModalValue(`{$rc["itemID"]}`, `{$rc["itemName"]}`, `{$rc["price"]}`)' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#AddItem' data-bs-whatever='@mdo'>Add</button></td>";
                echo "</tr>";
              }
            }
            mysqli_free_result($rs);
            mysqli_close($conn);
          ?>
        </tbody>
      </table>
    
      <form id="myForm" name="form" action="<?php echo "{$_SERVER["PHP_SELF"]}?id={$_GET['id']}"; ?>" method="POST">   
      <div class="modal fade" id="AddItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Add New Item</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                  <label for="recipient-name" class="col-form-label">Item ID:</label>
                  <input type="text" class="form-control" id="itemID" name="itemID" readonly>
                </div>
                <div class="mb-3">
                  <label for="recipient-name" class="col-form-label">Item Name:</label>
                  <input type="text" class="form-control" id="itemName" name="itemName" readonly>
                </div>
                <div class="mb-3">
                  <label for="message-text" class="col-form-label">Unit Price($):</label>
                  <input type="text" class="form-control" id="itemPrice" name="itemPrice" readonly>
                </div>
                <div class="mb-3">
                  <label for="message-text" class="col-form-label">Quantity:</label>
                  <input type="number" class="form-control" id="itemQty" name="itemQty" value="1" min="1" required>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" onclick="setAmountValue()" class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#ConfirmDetail' data-bs-whatever='@mdo'>Add</button>
                <!-- <input type="submit" class="btn btn-primary" value="Add"></button> -->
              </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="ConfirmDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Confirm New Amount of Order</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">  
                <div class="mb-3">
                  <input type="hidden" value="<?php echo $_GET['amount']; ?>" name="MyAmount" id="MyAmount">
                  <label for="message-text" class="col-form-label">Current Amount:(include discount)</label>
                  <input type="text" class="form-control" id="CurrentAmount" name="CurrentAmount" readonly>
                </div>
                <div class="mb-3">
                  <label for="message-text" class="col-form-label">New Amount:(include discount)</label>
                  <input type="number" class="form-control" id="NewAmount" name="NewAmount" readonly>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('myForm').submit();">Confirm</button>
              </div>
          </div>
        </div>
      </div>
    </form>
  </main>
  <!-- content end -->
  
  <!-- footer setting -->
  <?php include_once '../include/Footer.php';?>
</div>
</body>
<script src="../javascript/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  function setAmountValue(){
    var CurrentOrderAmount = document.getElementById("MyAmount").value;
    GetApiResult(document.getElementById("CurrentAmount"), CurrentOrderAmount);
    GetApiResult(document.getElementById("NewAmount"), (parseInt(document.getElementById("itemPrice").value) * parseInt(document.getElementById("itemQty").value) + parseInt(CurrentOrderAmount)));
  }

  function GetApiResult(inputbox, input){
      $(document).ready(function(){
        $.ajax({
            type:"GET", 
            url: "http://127.0.0.1:8000/api/discountCalculator/" + input,
            dataType:"JSON",
            success: function(JSON){
              inputbox.value = JSON["TotalAmount"];
            },
            error: function(err){
                alert("something get error");
            }
        });
      });
    }
    function setModalValue(id, value, price){
      document.getElementById("itemID").value = id;
      document.getElementById("itemName").value = value;
      document.getElementById("itemPrice").value = price;
    }
    function loadUploadDataMessage(MessageID, MessageTextID, Message){
        document.getElementById(MessageID).style.display = "block";
        document.getElementById(MessageTextID).innerHTML = Message;
    }

</script>

</html>