<?php 
session_start(); 
if($_SESSION["Position"]!= "Manager")
  header('location:../include/page/NotEnoughPermissionPage.php');
?>
<!doctype html>
<html lang="en">
  <head>
    <title>Customer Order Detail</title>
  </head>
  <link href="../css/style.css" rel="stylesheet">
  <link href="../css/bootstrap-main.css" rel="stylesheet">
  <script>
    function setModalValue(id){
      document.getElementById("Delete").href="../conn/Customer.php?custEmail="+id; 
    }
    function loadUploadDataMessage(MessageID, MessageTextID, Message){
      document.getElementById(MessageID).style.display = "block";
      document.getElementById(MessageTextID).innerHTML = Message;
    }
  </script>
<body>     
  <!-- image loading -->
  <?php include_once '../include/bootstrapImage.php'; 
        include_once 'include/ManagerHeader.php';
  ?>

  <main class="d-flex flex-nowrap">
    <div class="b-example-divider b-example-vr"></div>
      <div class="d-flex flex-column flex-shrink-0 bg-light" style="width: 4.5rem;">
        <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
          <li class="nav-item">
            <a href="ProductItem.php" class="nav-link py-3 border-bottom rounded-0" aria-current="page" title="Product Item" data-bs-toggle="tooltip" data-bs-placement="right">
              <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Item"><use xlink:href="#Item"/></svg>
            </a>
          </li>
          <li>
            <a href="MonthlyReport.php" class="nav-link py-3 border-bottom rounded-0" title="Monthly Report" data-bs-toggle="tooltip" data-bs-placement="right">
              <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Chart"><use xlink:href="#Chart"/></svg>
            </a>
          </li>
          <li>
            <a href="Customer.php" class="nav-link active py-3 border-bottom rounded-0" title="Customer" data-bs-toggle="tooltip" data-bs-placement="right">
              <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Customer"><use xlink:href="#Customer"/></svg>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Content start -->
    <div class="MainContent col-lg-8 py-md-5 mx-auto" style="width:80%" >
      <div class="d-flex align-items-center pb-3 mb-5 border-bottom">
        <a h\class="d-flex align-items-center text-dark text-decoration-none">
          <span class="fs-4">Customer List</span>
        </a>
      </div>

      <?php include_once '../include/AlertMessage.php'; ?>
      
      <table class="table">
        <thead>
          <tr>
            <th>Customer Email</th>
            <th>Name</th>
            <th>Phone Number</th>
            <th> </th>
          </tr> 
        </thead>
        <tbody>
          <?php
            require_once("../conn/conn.php");
            $sql = "SELECT * from Customer";
            $rs = mysqli_query($conn, $sql) 
              or die (mysqli_error($conn));
            while($rc = mysqli_fetch_assoc($rs)){
              echo "<tr>";
              echo "<td>" . $rc["customerEmail"] . "</td>";
              echo "<td>". $rc["customerName"] . "</td>";
              echo "<td>". $rc["phoneNumber"] . "</td>";
              echo "<td><button type='button' class='btn btn-primary' id='{$rc["customerEmail"]}'  onclick='setModalValue(id)' data-bs-toggle='modal' data-bs-target='#exampleModal' data-bs-whatever='@mdo'>Delete</button></td>";
              echo "</tr>";
            }
            mysqli_free_result($rs);
            mysqli_close($conn);
          ?>
        </tbody>
      </table>
      
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Delete customer record</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form>
                Are you sure to delete this customer record?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <a id="Delete" href=""><button type="button" class="btn btn-primary" >Delete</button></a>
            </div>
          </div>
        </div>
      </div>
  </main>
  <!-- content end -->
  
  <!-- footer setting -->
  <?php include_once '../include/Footer.php';?>
</div>

<?php
if(isset($_GET["Deletedata"])){
  $UploadDataStatus = $_GET["Deletedata"];
  if(strcmp($UploadDataStatus, "True") == 0)
    echo '<script type="text/javascript">loadUploadDataMessage("alertSuccess", "alertSuccessMessage",
    "The Customer record (including the related data) has been deleted successfully!");</script>';
  else 
    echo '<script type="text/javascript">loadUploadDataMessage("alertUnsuccess", "alertUnsuccessMessage", 
    "Something get error");</script>';
}
?> 
</body>
<script src="../javascript/bootstrap.bundle.min.js"></script>
  <script src="../javascript/script.js"></script>
</html>