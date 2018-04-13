<?php
session_start();
if (isset($_GET['logout']))
session_destroy();
$email="";
$pass="";
$conn= mysqli_connect("localhost","root","","livechat");
if($_POST){
	$email = $_POST["email"];
	$pass =$_POST["password"];
	if(!empty($email)&& !empty($pass)){
	$query="select name, email from support where email=? and password=? ";
	$sql = $conn->prepare($query);
	$sql -> bind_param("ss",$email,$pass);
	$sql -> execute();
	$sql->bind_result($name,$em);
	if($sql->fetch())
	{
		$sql->close();

		$query="select id from support where email=?";
		$sql = $conn->prepare($query);
		$sql -> bind_param("s",$email);
		$sql -> execute();
		$sql->bind_result($sid);
		$sql->fetch();
		$_SESSION['sid']=$sid;
		$_SESSION['name'] = $name;
		$_SESSION['login']=true;
		header("location:supportportal.php");
	}
	else{

	}
}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>SCOCS Live Chat</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<script src="js/jquery.min.js"></script>
</head>
<body>

<div class="form-group"
		<div id="loginform" style="background-color:#ffa366;color:white">
			<form method="post">
			<h1>Welcome To Live Chat</h1>
			<h2>Support Portal</h2>
			<hr/>
				<label for="name">Please Enter Your Support Email</label>
				<input type="email" name="email" id="email" class="form-control" placeholder="Enter Your Support Email"/>
				<br>
				<label for="name">Please Enter Your Support Password</label>
				<input type="password" name="password" id="password" class="form-control" placeholder="Enter Your Support Password"/>
				<input type="submit" class="btn btn-default" name="enter" id="enter" value="Enter" />
			</form>
		</div>
	</div>

</body>
</html>