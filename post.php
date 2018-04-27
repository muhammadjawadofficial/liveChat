<?php

session_start();
if(isset($_POST['text']))
{
    $text = $_POST['text'];
    $conn= mysqli_connect("localhost","root","","livechat");
    date_default_timezone_set("Asia/Karachi");
    $time=date("Y-m-d h:i:sa");
    // $query="select id from tokens where uid=?";
    // $stmt=$conn->prepare($query);
    // $stmt->bind_param("i",$_POST['senderid']);
    // $stmt->execute();
    // $stmt->bind_result($tkid);
    // $stmt->fetch();
    // $stmt->close();

    $id=$_POST['side'].$_POST['senderid'];
    $query="insert into chat(senderid,message,token,createdat) values(?,?,?,?)";
    $stmt=$conn->prepare($query);
    $stmt->bind_param("ssss",$id, $text, $_POST['tkid'],$time);
    //$stmt -> bind_param("s", $text);
    $stmt->execute();
    $stmt->close();
}

?>