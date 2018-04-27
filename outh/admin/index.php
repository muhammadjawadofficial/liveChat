
<!DOCTYPE HTML>
<html>
<head>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.css"/>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="../../bootstrap/admin/js/bootstrap.min.js"></script>

</head>
<body>
<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "livechat");
if(isset($_GET["delete"])&& isset($_GET["id"])){
    $query="delete from support where id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i",$_GET["id"]);
    $stmt->execute();
    $stmt->close();
}

if(isset($_GET["update"])&& isset($_GET["id"])){
    $query="update support set name=? where id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si",$_GET['name'],$_GET["id"]);
    $stmt->execute();
    $stmt->close();
}
if (isset($_SESSION['id']))
{
?>
<div class="container">
    <div class="row">
        <div id="wrapper">
            <div id="menu">
            <h1>Live Chat!</h1><hr/>
                <!--<p class="welcome"><b>HI - <a><?php //echo $_SESSION['aname']; ?></a></b></p>-->

                <p style="align:center;"><a id="exit" href="#" class="btn btn-default">Exit Live Chat</a></p>
            <div style="clear: both"></div>
        </div>
     </div>

    <hr>
<div class="row">
        <div class="col-sm-6">
            <form method="get">
                <fieldset>
                    <legend>Find user</legend>
                    <input class="form-control" type="email"
                           required placeholder="Enter email" name="email"/>
                    <input class="btn btn-primary" name="submit" type="submit" value="Search"/>
                </fieldset>
            </form>
            <table class="table table-bordered table-stripped table-hover">
                <?php
                if(isset($_GET['submit']))
                {
                $query = "Select id,name,email,password from support where email=?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $_GET["email"]);
                $stmt->execute();
                $stmt->bind_result($id, $name, $email, $password);
                if ($stmt->fetch()){
                $stmt->close();
                ?>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password</th>
                </tr>
                </thead>
                <tbody>
                <?php
                echo "<tr>
                                        <td>$id</td>
                                        <td>$name</td>
                                        <td>$email</td>
                                        <td>$password</td>
                                    </tr>";
                } else {
                    echo "No user found";
                }

                }?>
                </tbody>
            </table>

            <form method="POST">
                <fieldset>
                    <legend>Add Support Person</legend>
                    <input class="form-control" type="text"
                           required placeholder="Enter name" name="name"/>
                    <input class="form-control" type="email"
                           required placeholder="Enter email" name="email"/>
                    <input class="form-control" type="password"
                           required placeholder="Enter password" name="password"/>
                    <input class="btn btn-primary" name="enter" type="submit" value="Enter"/>
                </fieldset>
            </form>

            <?php

            if (isset ( $_POST ['enter'] )) {
                if ($_POST ['name'] != "" && $_POST['email']!="" && $_POST['password']!="") {
                     {
                         $query="select id from support where email=?";
                         $stmt = $conn->prepare($query);
                         $stmt->bind_param("s", $_POST['email']);
                         $stmt->execute();
                         if($stmt->fetch())
                         {
                             $stmt->close();
                         }
                         else{
                             $stmt->close();
                             $query = "insert into support (name,email,password,designation) values(?,?,?,'support')";
                             $stmt = $conn->prepare($query);
                             $stmt->bind_param("sss", $_POST['name'], $_POST['email'], $_POST['password']);
                             $stmt->execute();
                             $stmt->close();

                         }

                     }
                        //$_SESSION['name'] = stripslashes(htmlspecialchars($_POST ['name']));

                    }
                }
            ?>
        </div>
        <div class="col-sm-6">
            <form>
            <legend> All Support Persons </legend>
            </form>
            <table class="table table-bordered table-stripped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Designation</th>
                </tr>
                </thead>
                <tbody>
                <?php

                $query="Select id,name,email,designation from support where designation='support'";
                $stmt = $conn->prepare($query);
                if($stmt->execute()){
                    $stmt->bind_result($id,$name,$email,$designation);
                    while($stmt->fetch()){
                        echo "<tr>
                                        <td>$id</td>
                                        <td><input type='hidden' value='$id'>";
                        ?>
                        <input name ="name"class='form-control' id="name_<?php echo $id;?>"
                               type='text' value='<?php echo $name;?>' onKeyDown='
                                var btn=document.getElementById("upd_btn_<?php echo $id?>")
                                var name=document.getElementById("name_<?php echo $id;?>").value
                                btn.href="?id=<?php echo $id;?>&update=true&name="+name;' />
                        <?php
                        echo "</td>
                                        <td>$email</td>
                                        <td>$designation</td>
                                        <td><a class='btn btn-warning' id='upd_btn_$id' href='?id=$id&update=true&name='> Update </a>
                                        <a class='btn btn-danger' href='?id=$id&delete=true'> Delete </a></td>
                                        </tr>";
                    }
                }else{
                    echo '<h3> error occurred</h3>';
                }
                $stmt->close();
                ?>
                </tbody>
            </table>
        <?php if(isset($_GET["submit"]) && isset($_GET['email'])){

            ?>

            <?php
        }?>
    </div>
    </div>
</div>

</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <?php
    }
    else if (!isset($_SESSION['id']))
    {
        header("location:../");
    }

    ?>

</body>

<script>
    $(document).ready(function(){
        $("#exit").click(function(){
            var exit = confirm("Are You Sure You Want To Leave This Page?");
            if(exit==true){window.location = '../?logout=true';}
        });
    });

</script>
</html>
