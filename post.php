<?php

session_start();
if(isset($_SESSION['name'])){
    $text = $_POST['text'];
    $conn= mysqli_connect("localhost","root","","livechat");
    date_default_timezone_set("Asia/Karachi");
    $time=date("Y-m-d h:i:sa");
    $query="select id from tokens where uid=?";
    $stmt=$conn->prepare($query);
    $stmt->bind_param("i",$_SESSION['uid']);
    $stmt->execute();
    $stmt->bind_result($tkid);
    $stmt->fetch();
    $stmt->close();

    $query="insert into chat(uid,message,token,createdat) values(?,?,?,?)";
    $stmt=$conn->prepare($query);
    $stmt->bind_param("isss",$_SESSION['uid'], $text, $tkid,$time);
    //$stmt -> bind_param("s", $text);
    $stmt->execute();
    $stmt->close();
}

?>