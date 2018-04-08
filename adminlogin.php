<html>
<head>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css"/>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="bootstrap/admin/js/bootstrap.min.js"></script>

</head>
<body>

<?php

session_start();
if (isset ($_GET ['logout'] )) {
    session_destroy ();
    header ("Location: adminlogin.php" );
}


if(isset($_SESSION['aid']))
{
    header("location:adminportal.php");
}else{
    loginform();
}
    function loginform(){
        echo '<div class="form-group"
        <div id="loginform" style="background-color:#ffa366;color:white">
        <form method="post">
        <h1>Welcome To Live Chat</h1>
        <h2>Admin Portal</h2>
        <hr/>
        <label for="name">Please Enter Your Admin Email</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="Enter Your Admin Email"/>
        <br>
        <label for="name">Please Enter Your Admin Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Enter Your Admin Password"/>
        <input type="submit" class="btn btn-default" name="enter" id="enter" value="Enter" />
    </form>
</div>
</div>';
}

if (isset ( $_POST ['enter'] )) {

    if (isset($_POST ['email']) && isset($_POST ['password'])) {

        $email=$_POST['email'];
        $password=$_POST['password'];
        //if (!isset($_SESSION['email'])) {

        //if(empty($email)){
        //    array_push($errors, "email is required");
        //}
        //if (empty($password)){
        //    array_push($errors,"password is required");
        //}

            $conn= mysqli_connect("localhost","root","","livechat");
            $query="select id from admin where email=? AND password=?";
            $stmt=$conn->prepare($query);
            $stmt->bind_param("ss",$email,$password);
            $stmt->execute();
            $stmt->bind_result($adminid);
            if($stmt->fetch())
            {
                $_SESSION['aid']=$adminid;
                $stmt->close();
                header("location:adminportal.php");
            }
            else{
                echo "Wrong Email/Password...";
                $stmt->close();
            }
        //}
    }
}



?>
</body>
</html>
