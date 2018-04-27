<!DOCTYPE html>
<html>
<?php
session_start();
if (isset($_GET['logout']))
  session_destroy();

$conn= mysqli_connect("localhost","root","","livechat");
if($_POST)
{
	$email = $_POST["email"];
	$pass =$_POST["password"];
  if(!empty($email)&& !empty($pass))
  {
    $query="select name, email, designation from support where email=? and password=? ";
    $sql = $conn->prepare($query);
    $sql -> bind_param("ss",$email,$pass);
    $sql -> execute();
    $sql->bind_result($name,$em,$designation);
    if($sql->fetch())
    {
      $sql->close();

      $query="select id from support where email=?";
      $sql = $conn->prepare($query);
      $sql -> bind_param("s",$email);
      $sql -> execute();
      $sql->bind_result($sid);
      $sql->fetch();
      $_SESSION['name'] = $name;
      $_SESSION['login']=true;
      $_SESSION['id']=$sid;
      if($designation=="support")
      {
        header("location:support/");
      }
      else if($designation=="admin")
      {
        header("location:admin/");
      }
    }
  }
}

?>
<head>
  <title>Login | LiveChat</title>
  <link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel="stylesheet" href="../css/loginstyle.css"> 
</head>

<body>
  <div class="form">
      <div class="tab-content">
          <div id="login">   
              <h1>Welcome!</h1>
              
              <form method="post">
              
               <div class="field-wrap">
                <label>
                  Email Address<span class="req">*</span>
                </label>
                <input name="email" type="email"required autocomplete="off"/>
               </div>
              
              <div class="field-wrap">
                <label>
                  Password<span class="req">*</span>
                </label>
                <input name="password" type="password"required autocomplete="off"/>
              </div>
              
              
              <input type="submit" class="button button-block" value="Log In">
              
              </form>
    
          </div>
      </div><!-- tab-content -->
  </div> <!-- /form -->
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script  src="../js/loginscript.js"></script>

</body>
</html>
