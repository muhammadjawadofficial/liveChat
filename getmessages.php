<?php
        $conn= mysqli_connect("localhost","root","","livechat");
        $query="select createdAt, message from chat where uid=?";
        $stmt=$conn->prepare($query);
        $stmt->bind_param("i",$_SESSION['uid']);
        $stmt->execute();
        $stmt->bind_result($createdat,$message);
        while($stmt->fetch())
        {
            echo '<div class="msgln">'.$createdat.' <b>'.$_SESSION['name'].'</b>: '.stripslashes(htmlspecialchars($message)).'<br></div>';   
        }
		
	?>