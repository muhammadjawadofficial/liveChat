<!DOCTYPE html>
<html>
<head>
	<title>SCOCS Live Chat</title>
	<link rel="stylesheet" href="../../css/style.css">
	<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
	<script src="../../js/jquery.min.js"></script>
    
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
$stmt->bind_param("ii",$_SESSION['id'],$_GET['userid']);
$stmt->execute();
$stmt->close();

function gettokenid()
{
    $conn= mysqli_connect("localhost","root","","livechat");    
    $query="select id from tokens where sid=?";
    $stmt=$conn->prepare($query);
    $stmt->bind_param("i",$_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($tkid);
    $_SESSION['tokenid']=$tkid;
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
        if(!isset($supid)||!isset($stdid))
        {
            if(substr($senderid,0,3)=="sup")
                $supid=substr($senderid,3);
            else if(substr($senderid,0,3)=="std")
                $stdid=substr($senderid,3);
        }
        else
            break;
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
    <input type = "hidden" name="userid" value="<?php echo $_GET['userid'] ?>">
    <input name="submitmsg" class="btn btn-default" type="submit" id="submitmsg" value="Send" />

</form>
</div>

<?php
}
else if(!isset($_SESSION['login'])){
   header("Location:../");
}
?>
<script>
$(document).ready(function(){
    $("#exit").click(function(){
        var exit = confirm("Are You Sure You Want To Leave This Page?");
        if(exit==true)
        {
            window.location = '../support/?logout=true&userid=<?php echo $_GET['userid']?>';
        }     
    });
});
$("#submitmsg").click(function(){
        var clientmsg = $("#usermsg").val();
        if(!clientmsg==""){
            $.post("../../post.php", {text: clientmsg, side: 'sup', tkid: <?php echo gettokenid(); ?>,senderid: <?php echo $_SESSION['id'];?>});
            
            //document.getElementById("chatbox").innerHTML='<?php //loadmessages(); ?>'
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
</body>
</html>