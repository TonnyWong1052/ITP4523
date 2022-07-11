<?php 
session_start(); 
if($_SESSION["Position"]!= "Staff")
  header('location:../include/page/NotEnoughPermissionPage.php');
?>
<!doctype html>
<html lang="en">
  <head>
    <title>Customer Order List</title>
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/bootstrap-main.css" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
  </head>
  <script>
    function onChangeTextSearch(){
      var value = document.getElementById("search").value;
    }
    function setModalValue(id){
      document.getElementById("Delete").href="Order.php?orderID="+id; 
    }
    function loadUploadDataMessage(MessageID, MessageTextID, Message){
        document.getElementById(MessageID).style.display = "block";
        document.getElementById(MessageTextID).innerHTML = Message;
    }
  </script>
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
          <span class="fs-4">Customer Order List</span>
        </a>
      </div>

      <form id="SearchFrom" name="SearchFrom" action="Order.php" method="POST" class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
        <input type="search" name="search" id="search" onchange="document.getElementById('SearchFrom').submit();" class="form-control SearchInput" oninput="onChangeTextSearch()" 
        style="width: 300px;" placeholder="Emeil search..." aria-label="Search" value="<?php if(!empty($_POST)) echo $_POST['search']; ?>"></p>
      </form>

      <!-- alert message -->
      <?php include_once '../include/AlertMessage.php'; ?>
      
      <table class="table">
        <thead>
          <tr>
            <th>Order ID</th>
            <th scope="col">Customer Name</th>
            <th scope="col">Customer Email</th>
            <th scope="col">Salesperson</th>
            <th scope="col">Amount</th>
            <th scope="col">Status</th>
            <th scope="col"></th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
        <?php
            require_once("../conn/conn.php");
            if(!empty($_GET['orderID'])){
                $orderID = $_GET['orderID'];
                $sql2 = "DELETE FROM ItemOrders WHERE OrderID = '$orderID'";
                mysqli_query($conn, $sql2) or die(mysqli_error($conn));
                $sql3 = "DELETE FROM Orders WHERE orderID = '$orderID' ";
                mysqli_query($conn, $sql3) or die(mysqli_error($conn));
                $rows = mysqli_affected_rows($conn);
                $rows = mysqli_affected_rows($conn);
                if($rows > 0){
                  echo '<script type="text/javascript">loadUploadDataMessage("alertSuccess", "alertSuccessMessage",
                    "The Order has been deleted successfully");</script>';
                }else
                    echo '<script type="text/javascript">loadUploadDataMessage("alertUnsuccess", "alertUnsuccessMessage", 
                    "Something get error");</script>';
            }

            if(empty($_POST)) $_POST['search'] = "";

            $sql = "SELECT orderID, customerName, Customer.customerEmail, StaffName, totalPrice, if(deliveryAddress is NULL, 'Pick-up', 'Delivery') 
            AS Status FROM `Orders`, `Staff`, `Customer` WHERE Orders.staffID = Staff.staffID AND Customer.customerEmail = Orders.customerEmail AND Customer.customerEmail LIKE '%{$_POST['search']}%'";
            $rs = mysqli_query($conn, $sql) or die (mysqli_error($conn));
            while($rc = mysqli_fetch_assoc($rs)){
              echo "<tr>";
              echo "<td>" . $rc["orderID"] . "</td>";
              echo "<td>". $rc["customerName"] . "</td>";
              echo "<td>". $rc["customerEmail"] . "</td>";
              echo "<td>". $rc["StaffName"] . "</td>";
              echo "<td>$". $rc["totalPrice"] . "</td>";
              echo "<td>". $rc["Status"] . "</td>";
              echo "<td><a href='OrderDetail.php?id={$rc["orderID"]}'><button type='button' class='btn btn-primary'>Edit</button></a></td>";
              echo "<td><button type='button' onclick='setModalValue({$rc['orderID']})' class='btn btn-primary' data-bs-target='#DeleteOrder' data-bs-toggle='modal' data-bs-whatever='@mdo'>Delete</button></td>";
              echo "</tr>";
            }
            mysqli_free_result($rs);
            mysqli_close($conn);
          ?>
        </tbody>
      </table>
      
      <div class="modal fade" id="DeleteOrder" tabindex="-1" aria-labelledby="DeleteOrder" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Delete Order</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form>
                Are you sure to delete this order?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <a id="Delete" href="www.google.com"><button type="button" class="btn btn-primary" onclick="setDisplayLine()" data-bs-dismiss="modal">Delete</button></a>
            </div>
          </div>
        </div>
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