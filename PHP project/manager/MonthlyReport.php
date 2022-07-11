<?php 
session_start(); 
if($_SESSION["Position"]!= "Manager")
  header('location:../include/page/NotEnoughPermissionPage.php');
?>
<!doctype html>
<html lang="en">
  <head>
    <title>Monthly Report</title>
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/bootstrap-main.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
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
            <a href="ProductItem.php" class="nav-link py-3 border-bottom rounded-0" aria-current="page" title="Product Item" data-bs-toggle="tooltip" data-bs-placement="right">
              <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Item"><use xlink:href="#Item"/></svg>
            </a>
          </li>
          <li>
            <a href="monthlyReport.php" class="nav-link active py-3 border-bottom rounded-0" title="Monthly Report" data-bs-toggle="tooltip" data-bs-placement="right">
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
          <span class="fs-4">Staff List</span>
        </a>
      </div>

      <form id="myForm" name="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div style="margin-bottom:10px" onchange="document.getElementById('myForm').submit();">
          Date of Analysis:<input name="datepicker" type="month" value="<?php 
          if(!isset($_POST['datepicker']))
            echo date('Y-m'); 
          else 
            echo $_POST['datepicker'];
          ?>" />
        </div>
      </form>

      <table class="table" id="table">
        <thead>
          <tr>
            <th>Staff ID</th>
            <th>Staff Name</th>
            <th>Number of Sales Order</th>
            <th>Total Amount of Sales Order</th>
          </tr>
        </thead>
        <tbody>
          <?php     
            if(isset($_POST['datepicker']))
              $date = $_POST['datepicker'];
            else 
              $date = date('Y-m');
            require_once("../conn/conn.php");
            $sql = "SELECT * from Staff WHERE position = 'Staff' ";
            $rs = mysqli_query($conn, $sql) 
              or die (mysqli_error($conn));
            while($rc = mysqli_fetch_assoc($rs)){
              $sql2 = "SELECT COUNT(`orderID`) AS OrderCount,SUM(`totalPrice`) AS TotelPrice FROM `Orders` 
                      WHERE `staffID` = '" . $rc["staffID"] . "' AND `dateTime` LIKE '" . $date . "%' ";
              $rs2 = mysqli_query($conn, $sql2);
              $rc2 = mysqli_fetch_assoc($rs2);

              echo "<tr>";
              echo "<td>" . $rc["staffID"] . "</td>";
              echo "<td>". $rc["staffName"] . "</td>";
              echo "<td>" . $rc2["OrderCount"] . "</td>";
              if($rc2["TotelPrice"] != null)
                echo "<td>$". $rc2["TotelPrice"] . "</td>";
              else 
                echo "<td>$0</td>";
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
              <h5 class="modal-title" id="exampleModalLabel">Delete Item #1</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form>
                Are you sure to delete this customer record?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-primary">Delete</button>
            </div>
          </div>
        </div>
      </div>
  </main>
  <!-- content end -->
  
  <!-- footer setting -->
  <?php include_once '../include/Footer.php';?>
</div>

  <script src="../javascript/bootstrap.bundle.min.js"></script>
  <script src="../javascript/script.js"></script>
</body>
</html>