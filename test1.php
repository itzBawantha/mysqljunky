<form class="" action="" method="post">
  <input type="text" style="margin-bottom:5px" name="ip" value="localhost"><br>
  <input type="text" style="margin-bottom:5px" name="username" value="" placeholder="username"><br>
  <input type="password" style="margin-bottom:5px" name="password" value="" placeholder="password"><br>
  <input type="submit" style="margin-bottom:5px" name="db_login" value="Submit">
</form>

<?php
// print_r($_REQUEST);

if (isset($_REQUEST['db_login']) && !empty($_REQUEST['username']) && isset($_REQUEST['password']) && !empty($_REQUEST['ip'])) {

  $conn = mysqli_connect($_REQUEST['ip'],$_REQUEST['username'],$_REQUEST['password']);
  
}







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
