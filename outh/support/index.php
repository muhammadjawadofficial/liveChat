<!DOCTYPE HTML>
<html>
<head>

<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
<script src="../../js/jquery.min.js"></script>
</head>

<body style="background-image:url('../../images/3.jpg');">
<?php
session_start();
if (isset($_GET['logout'])&&isset($_GET['userid']))
{
    $conn= mysqli_connect("localhost","root","","livechat");
    $query="update tokens set sid=NULL where uid=?";
    $stmt=$conn->prepare($query);
    $stmt->bind_param("i",$_GET['userid']);
    $stmt->execute();
    $stmt->close();
}

if(isset($_SESSION['login'])){
?>
   <div class="container">
    <div cass="row" style="text-align:center">
        <div col-md-4>
            <div id="menu">
                <h1>Available Students</h1> 
                    <p class="logout"><a id="exit" href="#" class="btn btn-default">Exit Live Chat</a></p>
                <div style="clear: both"></div>
            </div>
        </div>
    </div>
<div cass="row">
    <?php
        $conn= mysqli_connect("localhost","root","","livechat");
        // $query="select uid from tokens where sid = NULL";
        // $stmt=$conn->prepare($query);
        // $stmt->execute();
        // $stmt->bind_results($usid);
        // while($stmt->fetch())
        // {

        // }
        $status="Available";
        $query="select id,name from students where status = ?";
        $stmt=$conn->prepare($query);
        $stmt->bind_param("s",$status);
        $stmt->execute();
        $stmt->bind_result($usid,$nam);
        while($stmt->fetch())
        {
            echo "<a class='btn btn-warning' id='upd_btn_$usid' href='supportchat.php?userid=$usid' target='_blank'> $nam </a>";
        }
        $stmt->close();
    ?>
</div>  

<?php
}
else if(!isset($_SESSION['login'])){
    header("Location:../");
}
?>
</body>
<script>
$(document).ready(function(){
    $("#exit").click(function(){
        var exit = confirm("Are You Sure You Want To Leave This Page?");
        if(exit==true)
        {
            window.location = '../?logout=true';
        }     
    });
});
</script>
</html>