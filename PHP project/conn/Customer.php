<?php
if(isset($_GET["custEmail"])){
    $custEmail = $_GET['custEmail'];
    require("conn.php");
    $sql = "SELECT * FROM Orders
    WHERE customerEMail = '$custEmail'";
    $rs = mysqli_query($conn, $sql) 
    or die (mysqli_error($conn));

    while($rc = mysqli_fetch_assoc($rs)){
		$sql2 = "DELETE FROM ItemOrders
        WHERE OrderID = {$rc["orderID"]}";
        mysqli_query($conn, $sql2) or die(mysqli_error($conn));
	}

    $sql3 = "DELETE FROM Orders
        WHERE customerEmail = '$custEmail' ";
    mysqli_query($conn, $sql3) or die(mysqli_error($conn));

    $sql4 = "DELETE FROM Customer
    WHERE customerEmail = '$custEmail'";
    mysqli_query($conn, $sql4) or die(mysqli_error($conn));
    $rows = mysqli_affected_rows($conn);
    if($rows > 0){
        header("Location: ../manager/Customer.php?Deletedata=True");
    }else
        header("Location: ../manager/Customer.php?Deletedata=False");
}
?>