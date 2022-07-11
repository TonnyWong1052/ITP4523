<?php 
session_start(); 
if($_SESSION["Position"]!= "Staff")
  header('location:../include/page/NotEnoughPermissionPage.php');
?>
<!doctype html>
<html lang="en">
  <head>
    <title>Place Order</title>
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/bootstrap-main.css" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
  </head>
  <script>
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
            <a href="PlaceOrder.php" class="nav-link active py-3 border-bottom rounded-0" aria-current="page" title="Place Order" data-bs-toggle="tooltip" data-bs-placement="right">
              <svg class="bi pe-none" width="24" height="24" role="img" aria-label="POS"><use xlink:href="#POS"/></svg>
            </a>
          </li>
          <li>
            <a href="Order.php" class="nav-link py-3 border-bottom rounded-0" title="Orders" data-bs-toggle="tooltip" data-bs-placement="right">
              <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Orders"><use xlink:href="#table"/></svg>
            </a>
          </li>
        </ul>
      </div>

  <!-- Content start -->

    <div class="MainContent col-lg-8 py-md-5 mx-auto" style="width:80%" >
      <div class="d-flex align-items-center pb-3 mb-5 border-bottom">
        <a h\class="d-flex align-items-center text-dark text-decoration-none">
          <span class="fs-4">Place Order</span>
        </a>
        <?php
        
        ?>
      </div>

      <div id="CreateOrder" class="CreateOrder"> 
        <?php include_once '../include/AlertMessage.php'; ?>
        <div class="row mb-3">
          <div class="col-sm-6 col-lg-8 themed-grid-col productList">
                <?php
                require("../conn/conn.php");
                if(!empty($_POST['customerEmail'])){
                  extract($_POST);
      
                  $sql = "SELECT * FROM `Orders` ORDER BY orderID DESC";
                  $rs = mysqli_query($conn, $sql) or die (mysqli_error($conn));
                  $rc = mysqli_fetch_assoc($rs);
                  $LastOrderID = $rc["orderID"]+=1;  // generate primary key from last number of orderID
      
                  if($DelivertDate!='')  // The order is pickup or delivery
                    $sql = "INSERT INTO Orders VALUES('$LastOrderID', '{$customerEmail}', '{$_SESSION['staffID']}', DEFAULT, '{$address}', '$DelivertDate', '$OrderAmount')";
                  else
                    $sql = "INSERT INTO Orders VALUES('$LastOrderID', '{$customerEmail}', '{$_SESSION['staffID']}', DEFAULT, NULL, NULL, '$OrderAmount')";
                  mysqli_query($conn, $sql) or die(mysqli_error($conn));
      
                  for ($i = 0; $i < count($id); $i++) {
                    $sql2 = "INSERT INTO ItemOrders VALUES('$LastOrderID', '{$id[$i]}', '{$qty[$i]}', '{$price[$i]}')";
                    mysqli_query($conn, $sql2) or die(mysqli_error($conn));

                    $sql3 = "UPDATE `Item` SET `stockQuantity`= stockQuantity-{$qty[$i]} WHERE itemID = '{$id[$i]}';";  //upload stock qty
                    mysqli_query($conn, $sql3) or die(mysqli_error($conn));
                  }
                  $rows = mysqli_affected_rows($conn);
                  if($rows > 0){
                    echo '<script type="text/javascript">loadUploadDataMessage("alertSuccess", "alertSuccessMessage",
                  "The new order has been created successfully!");</script>';
                      }else
                      echo '<script type="text/javascript">loadUploadDataMessage("alertUnsuccess", "alertUnsuccessMessage", 
                  "Something get error");</script>';
              }

                $sql = "SELECT * FROM item WHERE stockQuantity > 0";
                $rs = mysqli_query($conn, $sql) or die (mysqli_error($conn));
                while($rc = mysqli_fetch_assoc($rs)){
                  extract($rc);
                  echo <<<EOD
                  <button onclick="addItemToCart(`{$itemID}`, `{$itemName}`, `{$price}`, `{$stockQuantity}`);" 
                  class="btn btn-info productList">$itemName<br><label style="font-size: 12px;">Unit Price:$$price<br>Stock quantity:{$stockQuantity}</label></button>
EOD;
                }
                mysqli_free_result($rs);
              ?>
          </div>
          <div class="col-6 col-lg-4 themed-grid-col">
          <form id="CreateOrderFrom" name="CreateOrderFrom" action="PlaceOrder.php" method="POST">
            <table id="tbl" class="table">
                  <thead>
                  <th style="width: 200px;">Itme Name</th>
                  <th>Qty</th>
                  <th>Price($)</th>
                  <th></th>
                
                  </thead>
                  
                  <tbody>
                      
                  </tbody>
            </table>

          </div>
        </div>
        <div class="putRightDown">          
          <div style="margin-top: 20px;">
            <button type="button" style="visibility: hidden;" onclick="GetApiResult()" id="Create" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Create Order</button>
          </div>
        </div>
      </div>

      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Pleace new order</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                  <label for="message-text" class="col-form-label" >Total Amount:(include discount)</label>
                  <input name="OrderAmount" class="form-control" type="text" id="OrderAmount" value="0" readonly>
                </div>
                <div class="mb-3">
                  <label for="recipient-name" class="col-form-label">Customer Email:</label>
                  <input list="browsers" class="form-control" id="recipient-name" name="customerEmail" required/></label>
                  <datalist id="browsers">
                    <?php
                        require("../conn/conn.php");
                        $sql = "SELECT * FROM Customer";
                        $rs = mysqli_query($conn, $sql) or die (mysqli_error($conn));
                        while($rc = mysqli_fetch_assoc($rs)){   //load customer email to datalist from database
                          extract($rc);
                          echo <<<EOD
                          <option value="$customerEmail">
EOD;
                        }
                        mysqli_free_result($rs);
                        mysqli_close($conn);
                      ?>
                  </datalist>
                </div>
                <div class="mb-3">
                  <label for="message-text" class="col-form-label">Order Type:</label>
                  <input type="radio" name="OrderType" style="margin-left: 16px;" onclick="SelectDelivery()" checked> Delivery
                  <input type="radio" name="OrderType" style="margin-left: 16px;" onclick="SelectPickup()" > Pick-up
                </div>
                <div class="mb-3">
                  <label for="message-text" class="col-form-label" >Delivery Address:</label>
                  <textarea name="address" class="form-control" id="DeliveryAddress" required></textarea>
                </div>
                <div class="mb-3">
                  <label for="message-text" class="col-form-label" >Delivery Date:</label>
                  <input name="DelivertDate" class="form-control" type="date" id="DelivertDate" required>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <input type="submit" value="Create" name="save_data" class="btn btn-primary">
            </div>
            </form>
          </div>
        </div>
      </div>

    </div>    
  </main>
  <!-- content end -->
  
  <!-- footer setting -->
  <div class="b-example-divider" style="margin-top:-54px"></div>
  <footer class="py-3 my-4">
      <ul class="nav justify-content-center border-bottom pb-3 mb-3"></ul>
      <p class="text-center text-muted">&copy; 2022-2024 Better Limited</p>
  </footer>
</div>
  <script src="../javascript/bootstrap.bundle.min.js"></script>
  <script src="../javascript/script.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script type="text/javascript">

    function SelectPickup(){
      document.getElementById("DeliveryAddress").readOnly = true;
      document.getElementById("DelivertDate").readOnly = true;
      document.getElementById("DeliveryAddress").value = "";
      document.getElementById("DelivertDate").value = "";
    }

    function SelectDelivery(){
      document.getElementById("DeliveryAddress").readOnly = false;
      document.getElementById("DelivertDate").readOnly = false;
    }
    var myElement;
    var totalItme = 0;
    var totalAmount = 0;
    function addItemToCart(id, name, price, qty){ 
      myElement = document.getElementById("id"+id);
      if(myElement !=null){
        if((parseInt(document.getElementById("id"+id).innerHTML))>=qty)
          alert("Stock did not have enough item quantity!!");
        else{
          document.getElementById("hiddqty"+id).value = document.getElementById("id"+id).innerHTML = ++document.getElementById("id"+id).innerHTML; // add item qty if the produect id is name name
          document.getElementById("hidden"+id).value = document.getElementById("price"+id).innerHTML = price*document.getElementById("id"+id).innerHTML;  //display changing
          totalAmount = (parseInt(totalAmount)) + parseInt(price); 
        }
      }else{
        var tr = document.createElement('tr');
        
        // var td1 = tr.appendChild(document.createElement('td'));
        var td2 = tr.appendChild(document.createElement('td'));
        var td3 = tr.appendChild(document.createElement('td'));
        var td4 = tr.appendChild(document.createElement('td'));
        var td5 = tr.appendChild(document.createElement('td'));

        td2.innerHTML='<input type="hidden" name="id[]" value="' + id + '"> <label name="name">' + name +  '</label>';
        td3.innerHTML='<input type="hidden" id="hiddqty' + id + '" name="qty[]" value="' + 1 + '"> <label id="id' + id + '" >1</label>';
        td4.innerHTML='<input type="hidden" id="hidden' + id + '" name="price[]" value="' + price + '"><label name="price[]" id="price' + id + '" >' + price + '</label>';
        td5.innerHTML='<input onclick="ReomoveItem(this, price' + id + ')" type="button" name="del" class="btn btn-info" style="background-color: red;width:40px;" value="X">';
        totalAmount =  (parseInt(totalAmount) +parseInt(price)); // amount setting
        document.getElementById("tbl").appendChild(tr);
        
        totalItme++;
      }
      CreateOrderItemDisplay();
    }

    function CreateOrderItemDisplay(){
      if(totalItme>0)
        document.getElementById("Create").style.visibility = "visible";
      else 
        document.getElementById("Create").style.visibility = "hidden";
    }

    function ReomoveItem(item, price){
        var s=item.parentNode.parentNode;
        s.parentNode.removeChild(s);
        totalAmount = totalAmount - parseInt(price.innerHTML);
        totalItme--;
        CreateOrderItemDisplay();
    }

    function GetApiResult(){
        $(document).ready(function(){
        $.ajax({
            type:"GET", 
            url: "http://127.0.0.1:8000/api/discountCalculator/" + totalAmount,
            dataType:"JSON",
            success: function(JSON){
              document.getElementById("OrderAmount").value =  JSON["TotalAmount"];
            },
            error: function(err){
                alert("something get error");
            }
        });
      });
    }
  </script>
</body>
</html>