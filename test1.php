<!-- MYSQLjunkie Coded By Bawantha Chanadula AKA ShanD -->
<!-- Use wisely if victems server is created using mysql you can easily perform CURD functions using this -->
<?php

error_reporting(0);
session_start();

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>MYSQLjunkie</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.7/paper/bootstrap.css" integrity="sha256-F57nvcuc/M42/hIkW4XQboIGmLlKxXpKN3oHLFY+v9M=" crossorigin="anonymous" /> -->
    <link rel="stylesheet" href="https://getbootstrap.com/docs/4.4/dist/css/bootstrap.min.css"/>
  </head>
  <body>
<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
<h5 class="my-0 mr-md-auto font-weight-normal">MYSQL<b>junkie</b></h5>
<nav class="my-2 my-md-0 mr-md-3">
  <!-- <a class="p-2 text-dark" href="#">Pricing</a> -->
</nav>
<form method="post">
  <input type="submit" class="btn btn-outline-primary" value="Reset" name="Reset_Session">
</form>
</div>
    <div class="container-fluid">

<?php
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

if (isset($_REQUEST['Reset_Session'])) {
  session_destroy();
}
//setting creds to sessions
if (isset($_REQUEST['db_login'])) {
  $_SESSION['db_login'] = $_REQUEST['db_login'];
  $_SESSION['username'] = $_REQUEST['username'];
  $_SESSION['password'] = $_REQUEST['password'];
  $_SESSION['ip']       = $_REQUEST['ip'];
}

//start of the SQL Commands
if (connect()) {
  $conn = connect();
  $res = $conn -> query("SHOW DATABASES");
  while ($rows = $res->fetch_assoc()) {
    ?>
    <form class="input-group mb-2" >
    <?php
    echo "<input type=\"submit\" style=\"width:100%\" class=\"btn btn-primary\" name=".$rows['Database']." value=".$rows['Database'].">";
  }
}else {
  echo "<font color='red'>".mysqli_connect_error()."</font>";
}


function connect()
{
  if (isset($_SESSION['db_login']) && isset($_SESSION['username']) && isset($_SESSION['password']) && isset($_SESSION['ip'])) {

    if ($conn = mysqli_connect($_SESSION['ip'],$_SESSION['username'],$_SESSION['password'])) {

      //setting connection session
      $_SESSION['connection'] = "true";
      return $conn;

    }else {
      return $conn;
      // echo "<font color='red'>".mysqli_connect_error()."</font>";
    }
  }
}


if (!isset($_SESSION['connection'])) {

?>

<form action="" method="post"><br>
  <input type="text" class="form-control" style="margin-bottom:5px" name="ip" value="localhost">
  <input type="text" class="form-control" style="margin-bottom:5px" name="username" value="" placeholder="username">
  <input type="password" class="form-control" style="margin-bottom:5px" name="password" value="" placeholder="password"><br>
  <input type="submit" class="btn btn-outline-secondary" name="db_login" value="Submit">
</form>

<?php

};




// function connect($ip,$username,$password,$database)
// {
//   if ($conn= mysqli_connect($ip,$username,$password,$database)) {
//     return 1;
//   }else {
//     return 0;
//   }
// }
//
//
//
// if (connect("localhost","root","","test")) {
//
//  $res= $conn->query("SELECT * FROM $database");
//  echo "success";
// }else {
//   echo "failed";
// }
//
//



?>
</div>
</body>
</html>
