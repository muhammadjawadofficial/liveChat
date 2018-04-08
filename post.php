<?php

session_start();
if(isset($_SESSION['name'])){
    echo $text = $_POST['text'];
    $conn= mysqli_connect("localhost","root","","livechat");
    date_default_timezone_set("Asia/Karachi");
    $time=date("Y-m-d h:i:sa");
    $query="insert into chat(uid,message,token,createdat) values(?,?,?,?)";
    $stmt=$conn->prepare($query);
    $stmt->bind_param("isss",$_SESSION['uid'], $text, $token,$time);
    $stmt -> bind_param("s", $text);
    $stmt->execute();
    $stmt->close();
}

?>