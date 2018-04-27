<?php

$var="std2";
$sub=intval(substr($var,3));

$conn= mysqli_connect("localhost","root","","livechat");
$query="select name from students where id=?";
    $stmt=$conn->prepare($query);
    $stmt->bind_param("i",intval($sub));
    $stmt->execute();
    $stmt->bind_result($supname);
    $stmt->fetch();
    echo $sub;
    echo $supname;
    
    $stmt->close();
// if(substr($var,0,3)=="std")
// {
//     echo "students";
// }
?>