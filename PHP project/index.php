<?php
session_start();
$_SESSION["Position"]= "";
if(!empty($_POST)){
    extract($_POST);
    require_once("conn/conn.php");
    $sql = "SELECT * FROM staff";
    $result = mysqli_query($conn, $sql);
    while($rc = mysqli_fetch_assoc($result)){
        if($staffId == $rc["staffID"] && $password == $rc["password"]){
            if($rememberMe == "on"){
                $_SESSION["Account"] =  $staffId;
                $_SESSION["Password"] = $password;
            }else
                session_unset();
            $_SESSION["Position"]= $rc["position"];
            $_SESSION["staffID"]= $rc["staffID"];
            if($rc["position"] == "Manager"){
                $_SESSION["Position"]= "Manager";
                header("Location: ./manager/ProductItem.php");    
            }else if($rc["position"] == "Staff"){
                $_SESSION["Position"]= "Staff";
                header("Location: ./sales/PlaceOrder.php");
            }
        }
    }
    echo '<script>alert("Invaild staff ID or password!")</script>';
    mysqli_free_result($result);
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Document</title>
	<link rel="stylesheet" href="./css/loginUI.css">
	<link rel="icon" href="./src/logo.jpg" type="image/x-icon">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body onclick="getSessionValue()">
	<div class="hero">
			<video autoplay loop muted class="bgvideo"><source src="./src/loopbk.mp4" type="video/mp4" ></video>
		<div class="form-box">
				<p class="LoginText">Login Page</p>
			<form id="login" action="<?php $_SERVER["PHP_SELF"]; ?>" class="input-group" method="POST">
            <?php
                    if(!empty($_SESSION['Account'])){
                        echo <<<EOF
                        <input type="text" class ="input-field" name="staffId" id="staffID"  value="{$_SESSION['Account']}" placeholder="Enter Staff ID" required>
                        <input type="password" name="password" id="password" value="{$_SESSION['Password']}" class ="input-field" placeholder="Enter Password" required>
EOF;
                    }else{
                ?>
                <input type="text" name="staffId" id="staffID" class ="input-field" placeholder="Enter Staff ID" required>
                <input type="password" name="password" id="password" class ="input-field" placeholder="Enter Password" required>
                <?php }  ?>
                <input type="checkbox" name="rememberMe" id="remember" class ="chech-box"><span>Remember Me</span>
                <input type="submit" class="submit-btn" value="Log In">
			</form>
		</div>
	</div>
</body>
</html>