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
  <form method="post">
  <input type="submit" class="btn btn-outline-secondary" value="Change Database" name="Change_DB">
  </form>
</nav>
<form method="post">
  <input type="submit" class="btn btn-outline-primary" value="Reset" name="Reset_Session">
</form>
</div>
    <div class="container-fluid">
      <ol class="breadcrumb mb-4">
              <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
              <li class="breadcrumb-item active">Charts</li>
                              </ol>
<?php
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

//session Reset and page refresh
if (isset($_REQUEST['Reset_Session'])) {
    session_destroy();
    header("Refresh:0");
}

if (isset($_REQUEST['Change_DB'])) {
    unset($_SESSION["Database"]);
    header("Refresh:0");
}

//setting creds to sessions
if (isset($_REQUEST['db_login'])) {
    $_SESSION['db_login'] = $_REQUEST['db_login'];
    $_SESSION['username'] = $_REQUEST['username'];
    $_SESSION['password'] = $_REQUEST['password'];
    $_SESSION['ip']       = $_REQUEST['ip'];
}

//set database to session
if (isset($_REQUEST['Database'])) {
    $_SESSION['Database'] = $_REQUEST['Database'];
}

//Main Query Starts
if (connect()) {
    //check if Database is selected or not
    if (isset($_SESSION['Database'])) {
        load_basic_data();
    } else {
        $conn = connect();
        $res = $conn -> query("SHOW DATABASES");
        echo '<form class="input-group mb-2" method="post">';
        while ($rows = $res->fetch_assoc()) {
            echo "<input type=\"submit\" style=\"width:100%;margin-bottom:0px\" class=\"btn btn-secondary\" name=\"Database\" value=".$rows['Database'].">";
        }
        echo '</form>';
    }
} else {
    echo "<font color='red'>".mysqli_connect_error()."</font>";
}

//mysql connection function
function connect()
{
    if (isset($_SESSION['db_login']) && isset($_SESSION['username']) && isset($_SESSION['password']) && isset($_SESSION['ip'])) {
        if ($conn = mysqli_connect($_SESSION['ip'], $_SESSION['username'], $_SESSION['password'])) {

      //setting connection session
            $_SESSION['connection'] = "true";
            return $conn;
        } else {
            return $conn;
            // echo "<font color='red'>".mysqli_connect_error()."</font>";
        }
    }
}

function load_basic_data()
{
    if (connect()) {
        //set database
        $conn = connect();
        $conn->select_db($_SESSION['Database']);

        //get all of the tables and rows
        $sql =  'SELECT table_name, table_rows
                 FROM INFORMATION_SCHEMA.TABLES
                 WHERE TABLE_SCHEMA = "'.$_SESSION['Database'].'"';
        $res = $conn->query($sql); ?>

        <table class="table table-secondary">
          <thead>
            <tr>
              <th scope="col">table_name</th>
              <th scope="col">table_rows</th>
            </tr>
          </thead>
              <tbody>
        <?php
        while ($rows = $res->fetch_assoc()) {
            ?>
                <tr>
                  <td><?php echo $rows['table_name'] ?></td>
                  <td><?php echo $rows['table_rows'] ?></td>
                </tr>
              <?php
        } ?>
              </tbody>
         </table>
<?php
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
