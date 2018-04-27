<?php

session_start ();
$conn= mysqli_connect("localhost","root","","livechat");
function loginForm() {
    echo '
	<div class="form-group">
		<div id="loginform">
			<form action="index.php" method="post">
			<h1>Welcome To Live Chat</h1><hr/>
				<label for="name">Please Enter Your Name To Continue</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Your Name" required/>
                <br>
                <label for="name">Please Enter Your Email To Continue</label>
				<input type="email" name="email" id="email" class="form-control" placeholder="Enter Your Email" required/>
				<input type="submit" class="btn btn-default" name="enter" id="enter" value="Enter" />
			</form>
		</div>
	</div>
   ';
}

$tkid=0;

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
<?php
	if (! isset ( $_SESSION ['name'] )) {
	loginForm ();
	} else {
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


<script>
$(document).ready(function(){
});
$(document).ready(function(){
    $("#exit").click(function(){
        var exit = confirm("Are You Sure You Want To Leave This Page?");
        if(exit==true){window.location = 'index.php?logout=true';}     
    });
});
$("#submitmsg").click(function(){
        var clientmsg = $("#usermsg").val();
        if(!clientmsg=="")
        {

            $.post("post.php", {text: clientmsg, side: 'std', tkid: <?php echo gettokenid(); ?>, senderid: <?php echo $_SESSION['uid'];?>});
            loadmessages;


        }
    return false;
});
var height = 0;

$('div .msgln').each(function(i, value){
    height += parseInt($(this).height());
});

height += '';
$('div').animate({scrollTop: height});




</script>
<?php
}
// function loadmessages()
// {
//     $conn= mysqli_connect("localhost","root","","livechat");
//     $query="select id from tokens where uid=?";
//     $stmt=$conn->prepare($query);
//     $stmt->bind_param("i",$_SESSION['uid']);
//     $stmt->execute();
//     $stmt->bind_result($tkid);
//     $stmt->fetch();
//     $stmt->close();

//     $query="select createdAt, message from chat where token=?";
//     $stmt=$conn->prepare($query);
//     $stmt->bind_param("i",$tkid);
//     $stmt->execute();
//     $stmt->bind_result($createdat,$message);
//     while($stmt->fetch())
//     {
//         echo '<div class="msgln">'.$createdat.' <b>'.$_SESSION['name'].'</b>: '.stripslashes(htmlspecialchars($message)).'<br></div>';   
//     }
//     $stmt->close();
// }
function gettokenid()
{
    $conn= mysqli_connect("localhost","root","","livechat");
    $query="select id from tokens where uid=?";
    $stmt=$conn->prepare($query);
    $stmt->bind_param("i",$_SESSION['uid']);
    $stmt->execute();
    $stmt->bind_result($tkid);
    $stmt->fetch();
    $stmt->close();
    return $tkid;
}
function loadmessages()
{
    $conn= mysqli_connect("localhost","root","","livechat");   
    $tkid=gettokenid();
    $query="select senderid from chat where token=?";
    $stmt=$conn->prepare($query);
    $stmt->bind_param("i",$tkid);
    $stmt->execute();
    $stmt->bind_result($senderid);
    while($stmt->fetch())
    {
        if(!isset($supid)||!isset($stdid))
        {
            if(substr($senderid,0,3)=="sup")
            {
                $supid=substr($senderid,3);
            }
            else if(substr($senderid,0,3)=="std")
            {
                $stdid=substr($senderid,3);
            }
        }
        else
        {
            break;
        }
    }
    $stmt->close();

    $query="select name from support where id=?";
    $stmt=$conn->prepare($query);
    $stmt->bind_param("i",$supid);
    $stmt->execute();
    $stmt->bind_result($supname);
    $stmt->fetch();
    $stmt->close();

    $query="select name from students where id=?";
    $stmt=$conn->prepare($query);
    $stmt->bind_param("i",$stdid);
    $stmt->execute();
    $stmt->bind_result($stdname);
    $stmt->fetch();
    $stmt->close();

    $query="select senderid, createdAt, message from chat where token=?";
    $stmt=$conn->prepare($query);
    $stmt->bind_param("i",$tkid);
    $stmt->execute();
    $stmt->bind_result($senderid, $createdat,$message);
    while($stmt->fetch())
    {
        if(substr($senderid,0,3)=="sup")
        {
            $name=$supname;
        }
        if(substr($senderid,0,3)=="std")
        {
            $name=$stdname;
        }
        echo '<div class="msgln">'.$createdat.' <b>'.$name.'</b>: '.stripslashes(htmlspecialchars($message)).'<br></div>';   
    }
    $stmt->close();
}

?>
</body>
</html>