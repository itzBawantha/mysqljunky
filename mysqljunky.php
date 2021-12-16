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
    <script type="text/javascript">
        function tableToCSV() {
 
            // Variable to store the final csv data
            var csv_data = [];
 
            // Get each row data
            var rows = document.getElementsByTagName('tr');
            for (var i = 0; i < rows.length; i++) {
 
                // Get each column data
                var cols = rows[i].querySelectorAll('td,th');
 
                // Stores each csv row data
                var csvrow = [];
                for (var j = 0; j < cols.length; j++) {
 
                    // Get the text data of each cell
                    // of a row and push it to csvrow
                    csvrow.push(cols[j].innerText);
                }
 
                // Combine each column value with comma
                csv_data.push(csvrow.join(","));
            }
 
            // Combine each row data with new line character
            csv_data = csv_data.join('\n');
 
            // Call this function to download csv file 
            downloadCSVFile(csv_data);
 
        }
 
        function downloadCSVFile(csv_data) {
 
            // Create CSV file object and feed
            // our csv_data into it
            CSVFile = new Blob([csv_data], {
                type: "text/csv"
            });
 
            // Create to temporary link to initiate
            // download process
            var temp_link = document.createElement('a');
 
            // Download csv file
            temp_link.download = "<?php if (isset($_GET['table'])) { print(htmlspecialchars($_GET['table']));}else{ if (isset($_SESSION['Database'])) { print(htmlspecialchars($_SESSION['Database'])); }else{ print("SQLData"); }}?>.csv";
            var url = window.URL.createObjectURL(CSVFile);
            temp_link.href = url;
 
            // This link should not be displayed
            temp_link.style.display = "none";
            document.body.appendChild(temp_link);
 
            // Automatically click the link to
            // trigger download
            temp_link.click();
            document.body.removeChild(temp_link);
        }
    </script>
  </head>
  <body>
<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
<h5 class="my-0 mr-md-auto font-weight-normal">MYSQL<b>junkie</b></h5>
<nav class="my-2 my-md-0 mr-md-3">
<button type="button" class="btn btn-outline-secondary" onclick="tableToCSV()">
   Download Data
</button>
</nav>
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
              <li class="breadcrumb-item"><a href="?">Dashboard</a></li>
              <li class="breadcrumb-item active">Charts</li>
                              </ol>
<?php
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

//session Reset and page refresh
if (isset($_REQUEST['Reset_Session'])) {
    session_destroy();
    echo "<script>window.location = window.location.pathname; </script>";
    // header("Refresh:0");
}

if (isset($_REQUEST['Change_DB'])) {
    unset($_SESSION["Database"]);
    echo "<script>window.location = window.location.pathname; </script>";
    // header("Refresh:0");
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
        //load table names and rows if database is selected
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

        if(isset($_GET['table'])){

          get_all_data();

        }else{

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
                  <td><a href="?table=<?php echo $rows['table_name'] ?>"><?php echo $rows['table_name'] ?></a></td>
                  <td><?php echo $rows['table_rows'] ?></td>
                </tr>
              <?php
        } ?>
              </tbody>
         </table>
<?php
      }  
  }

}

function get_all_data(){

  if (connect()) {
    //set database
    $conn = connect();
    $conn->select_db($_SESSION['Database']);

    //get all of the data from table
    $sql =  'SELECT *
             FROM  `'.$_GET['table'].'`';
    
    $sqlcolumns = 'SELECT COLUMN_NAME
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = "'.$_SESSION['Database'].'" AND TABLE_NAME = "'.$_GET['table'].'" ;';

    $queryResult1 = $conn->query($sqlcolumns);
    echo "<table class=\"table\">";
    echo "<thead>";
    echo "<tr>";
    while ($queryRow1 = $queryResult1->fetch_row()) {
        for($i = 0; $i < $queryResult1->field_count; $i++){
            echo "<th scope=\"col\">$queryRow1[$i]</th>";
        }
    }
    echo "</tr>";
    echo "</thead>";

    echo "<tbody>";

    $queryResult = $conn->query($sql);
    while ($queryRow = $queryResult->fetch_row()) {
        echo "<tr>";
        for($i = 0; $i < $queryResult->field_count; $i++){
            echo "<td>$queryRow[$i]</td>";
        }
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
    // $conn->close();

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