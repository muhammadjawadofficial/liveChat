<!DOCTYPE HTML>
<html>
<head>

<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body>
<div class="container">
    <div cass="row" style="text-align:center">
        <div col-md-4>
            <h1>Available Students</h1>
        </div>
    </div>
<div cass="row">
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
            echo "<a class='btn btn-warning' id='upd_btn_$usid' href='supportchat.php?userid=$usid' target='_blank'> $nam </a>";
        }
        $stmt->close();
    ?>
</div>   
</body>
</html>