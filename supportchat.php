<!DOCTYPE html>
<html>
<head>
	<title>SCOCS Live Chat</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<script src="js/jquery.min.js"></script>
    
<?php

session_start ();
$conn= mysqli_connect("localhost","root","","livechat");


$query="select id from tokens where uid = ?";
$stmt=$conn->prepare($query);
$stmt->bind_param("i",$_GET['userid']);
$stmt->execute();
$stmt->bind_result($tkid);
$stmt->close();

$query="update tokens set sid=? where uid=?";
$stmt=$conn->prepare($query);
$stmt->bind_param("ii",$_SESSION['sid'],$_GET['userid']);
$stmt->execute();
$stmt->close();

function loadmessages()
{
    $conn= mysqli_connect("localhost","root","","livechat");    
    $query="select id from tokens where sid=?";
    $stmt=$conn->prepare($query);
    $stmt->bind_param("i",$_SESSION['sid']);
    $stmt->execute();
    $stmt->bind_result($tkid);
    $stmt->fetch();
    $stmt->close();
    
    $query="select createdAt, message from chat where token=?";
    $stmt=$conn->prepare($query);
    $stmt->bind_param("i",$tkid);
    $stmt->execute();
    $stmt->bind_result($createdat,$message);
    while($stmt->fetch())
    {
        echo '<div class="msgln">'.$createdat.' <b>'.$_SESSION['name'].'</b>: '.stripslashes(htmlspecialchars($message)).'<br></div>';   
    }
    $stmt->close();
}

/*
if (isset ( $_POST ['enter'] )) {
    if ($_POST ['name'] != "") {
        if(!isset($_SESSION['name']))
        {
            $ip = isset($_SERVER['HTTP_CLIENT_IP'])?$_SERVER['HTTP_CLIENT_IP']:isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR'];
            
            $_SESSION ['name'] = stripslashes ( htmlspecialchars ( $_POST ['name'] ) );
            $query="insert into students(name,ip,email,status) values(?,?,?,'Available')";
            $stmt=$conn->prepare($query);
            $stmt->bind_param("sss",$_POST['name'],$ip,$_POST['email']);
            $stmt->execute();
            $stmt->close();
            
            $query="select id from students where email=?";
            $stmt=$conn->prepare($query);
            $stmt->bind_param("s",$_POST['email']);
            $stmt->execute();
            $stmt->bind_result($userid);
            $stmt->fetch();
            $_SESSION['uid']=$userid;
            $stmt->close();
        }
        $conn= mysqli_connect("localhost","root","","livechat");
        $status="Available";
        $query="update students set status = ? where id = ?";
        $stmt=$conn->prepare($query);
        $stmt->bind_param("si",$status,$_SESSION['uid']);
        $stmt->execute();
        $stmt->close();

        if(!isset($_SESSION['token']))
        {
            $query="select token from tokens where uid = ?";
            $stmt=$conn->prepare($query);
            $stmt->bind_param("i",$_SESSION['uid']);
            $stmt->execute();
            $stmt->bind_result($token);
            if($stmt->fetch())
            {
                $_SESSION['token']=$token;
                $stmt->close();
            }
            else
            {
                date_default_timezone_set("Asia/Karachi");
                $time=date("Y-m-d h:i:sa");
                $token=md5($_SESSION['name'].$_SERVER['REMOTE_ADDR'].$time);
                $query="insert into tokens(uid,valid,token) values(?,'1',?)";
                $stmt=$conn->prepare($query);
                $stmt->bind_param("is",$_SESSION['uid'],$token);
                $stmt->execute();
                $stmt->close();
                $_SESSION['token']=$token;
            }
        }
        // $cb = fopen ( "log.html", 'a' );
        // fwrite ( $cb, "<div class='msgln'><i>User " . $_SESSION ['name'] . " has joined the chat session.</i><br></div>" );
        // fclose ( $cb );
    } else {
        echo '<span class="error">Please Enter a Name</span>';
    }
}
if (isset ( $_GET ['logout'] )) {
    // $cb = fopen ( "log.html", 'a' );
    // fwrite ( $cb, "<div class='msgln'><i>User " . $_SESSION ['name'] . " has left the chat session.</i><br></div>" );
    // fclose ( $cb );
    $conn= mysqli_connect("localhost","root","","livechat");
    $query="update students set Status='Away' where id=?";
    $stmt=$conn->prepare($query);
    $stmt->bind_param("i",$_SESSION['uid']);
    $stmt->execute();
    $stmt->close();

    session_destroy ();
    header ( "Location: index.php" );
}
*/

?>

</head>
<body>
<?php
	if(isset($_SESSION['login'])){
?>

<div id="wrapper">
	<div id="menu">
	<h1>Live Chat!</h1><hr/>
		<p class="welcome"><b>HI - <a><?php echo $_SESSION['name']; ?></a></b></p>
        <p class="logout"><a id="exit" href="#" class="btn btn-default">Exit Live Chat</a></p>
	<div style="clear: both"></div>
	</div>
	<div id="chatbox">
        <!--<button onclick="scrollWin(0, 50)">Scroll down</button> -->
    <?php
        loadmessages();
        
		// if (file_exists ( "log.html" ) && filesize ( "log.html" ) > 0) {
		// $handle = fopen ( "log.html", "r" );
		// $contents = fread ( $handle, filesize ( "log.html" ) );
		// fclose ( $handle );

		// echo $contents;
		// }
	?>
	</div>
<form name="message">
	<input name="usermsg" class="form-control" type="text" id="usermsg" placeholder="Create A Message" />
	<input name="submitmsg" class="btn btn-default" type="submit" id="submitmsg" value="Send" />

</form>
</div>

<?php
}
else if(!isset($_SESSION['login'])){
   header("Location:supportlogin.php");
}
?>
<script>
$(document).ready(function(){
    $("#exit").click(function(){
        var exit = confirm("Are You Sure You Want To Leave This Page?");
        if(exit==true){window.location = 'supportportal.php?logout=true';}     
    });
});
$("#submitmsg").click(function(){
        var clientmsg = $("#usermsg").val();
        {
            $.post("tad.php", {text: clientmsg, sid: <?php$_SESSION['sid']?>});
            
            document.getElementById("chatbox").innerHTML='<?php loadmessages(); ?>'
                    }
    return false;
});
// var height = 0;

// $('div .msgln').each(function(i, value){
//     height += parseInt($(this).height());
// });

// height += '';
// $('div').animate({scrollTop: height});
</script>
</body>
</html>