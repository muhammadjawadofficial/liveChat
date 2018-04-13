<html>
<head>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<script src="js/jquery.min.js"></script>
	
	<link href="css/chatstyle.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="css/chatscript.js"></script>
</head>

<?php
session_start();
if(isset($_SESSION['login'])){
 ?>
    <div id="wrapper" style="background-color:#ffa366;color:white">
	<div id="menu">
	<h1>Live Chat!</h1><hr/>
		<p class="welcome"><b>HI - <a><?php echo $_SESSION['name']; ?></a></b></p>
		<p class="logout"><a id="exit" href="#" class="btn btn-default">Exit Live Chat</a></p>
	<div style="clear: both"></div>
	</div>
	</div>

<!--side chat box-->
	<div class="chat_box">
		<div class="chat_head"> Chat Box</div>
		<div class="chat_body"> 
			<?php
				$conn= mysqli_connect("localhost","root","","livechat");
				$status="Available";
				$query="select id,name from students where status = ?";
				$stmt=$conn->prepare($query);
				$stmt->bind_param("s",$status);
				$stmt->execute();
				$stmt->bind_result($usid,$nam);
				while($stmt->fetch())
				{
					echo "<div class='user' id='u_<?php echo $usid;?>'>$nam</div>";
				}
				$stmt->close();
			?>
			<!-- <div class="user"> Abdul Waris</div> -->
		</div>
  	</div>

<div class="msg_box" style="right: 290px; display: none;">
	<div class="msg_head"><?php echo $nam;?>
		<div class="close">X</div>
	</div>
	<div class="msg_wrap">
		<div class="msg_body">
			<div class="msg_a">This is from AbdulWaris	</div>
			<div class="msg_b">This is from Me, and its amazingly kool nah... i know it even i liked it :)</div>
			<div class="msg_a">Wow, Thats great to hear from you man </div>	
			<div class="msg_push"></div>
		</div>
		<div class="msg_footer"><textarea placeholder="Enter Message Here..." class="msg_input" rows="4"></textarea></div>
	</div>
</div>
<div class="msg_box" style="right: 290px; display: none;">
	<div class="msg_head"><?php echo $nam;?>
		<div class="close">X</div>
	</div>
	<div class="msg_wrap">
		<div class="msg_body">
			<div class="msg_a">This is from AbdulWaris	</div>
			<div class="msg_b">This is from Me, and its amazingly kool nah... i know it even i liked it :)</div>
			<div class="msg_a">Wow, Thats great to hear from you man </div>	
			<div class="msg_push"></div>
		</div>
		<div class="msg_footer"><textarea placeholder="Enter Message Here..." class="msg_input" rows="4"></textarea></div>
	</div>
</div>


	<!-- <div id="chatbox" style="color:black;">
	<?php
		// if (file_exists ( "log.html" ) && filesize ( "log.html" ) > 0) {
		// $handle = fopen ( "log.html", "r" );
		// $contents = fread ( $handle, filesize ( "log.html" ) );
		// fclose ( $handle );

		// echo $contents;
		// }
	?>
	</div>
<form name="message" action="">
	<input name="usermsg" class="form-control" type="text" id="usermsg" placeholder="Create A Message" />
	<input name="submitmsg" class="btn btn-default" type="submit" id="submitmsg" value="Send" />
</form> -->

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
		if(exit==true)
		{
			window.location = 'supportlogin.php?logout=true';
		}     
	});
});
$(document).ready(function(){

$('.chat_head').click(function(){
	$('.chat_body').slideToggle('slow');
});
$('.msg_head').click(function(){
	$('.msg_wrap').slideToggle('slow');
});

$('.close').click(function(){
	$('.msg_box').hide();
});

$('.user').click(function(){


	$('.msg_wrap').show();
	$('.msg_box').show();
});

$('textarea').keypress(
function(e){
	if (e.keyCode == 13)
	{
		e.preventDefault();
		var msg = $(this).val();
		$(this).val('');
		if(msg!='')
		$('<div class="msg_b">'+msg+'</div>').insertBefore('.msg_push');
		$('.msg_body').scrollTop($('.msg_body')[0].scrollHeight);
	}
});

});
// $("#submitmsg").click(function(){
//         var clientmsg = $("#usermsg").val();
//         $.post("post.php", {text: clientmsg});             
//         $("#usermsg").attr("value", "");
//         loadLog;
//     return false;
// });
</script>
</html>