<?php
session_start();
require ('auth-sec.php'); //Gets CAS & db
//auth-sec includes: $user, $user_email, $user_type, $user_name
//Is user Admin check
if ($user_type == 1) {
  header("Location: customer-dashboard.php");
  die();
}

//record search parameters
$getcheck = array_fill(0,3, FALSE);
if (isset($_GET['searchdate_start']) && $_GET['searchdate_start'] != "" && $_GET['searchdate_start'] != NULL) {
  $getcheck[0] = True;
}if (isset($_GET['searchdate_end']) && $_GET['searchdate_end'] != "" && $_GET['searchdate_end'] != NULL) {
  $getcheck[1] = True;
}if (isset($_GET['searchorder_id']) && $_GET['searchorder_id'] != "" && $_GET['searchorder_id'] != NULL) {
  $getcheck[2] = True;
}

//Check if parameters are empty
if ($getcheck[0]==FALSE && $getcheck[1]==FALSE && $getcheck[2]==FALSE) {
  $stm = $conn->query("SELECT * FROM moneris_fields ORDER BY id");
}
//find out what parameters are being searched for
else{
  $get_var = array(); //search varible
  $sql_line =array(); //sql builder
  if ($getcheck[0] == TRUE) {
    $sql_line[] = "date_stamp >= ?";
    $get_var[] = $_GET['searchdate_start'];
  }if ($getcheck[1] == TRUE) {
    $sql_line[] = "date_stamp <= ?";
    $get_var[] = $_GET['searchdate_end'];
  }if ($getcheck[2] == TRUE) {
    $sql_line[] = "response_order_id LIKE ?";
    $get_var[] = $_GET['searchorder_id']."%";
  }

  //create sql query line
  $searchline = "SELECT * FROM moneris_fields WHERE " . implode(" AND ", $sql_line);
  $searchline .= " ORDER BY id";
  echo $searchline . "\n";
  $stm = $conn->prepare($searchline);
  $count = 1;
  foreach ($get_var as $key) {
    $stm->bindParam($count, $key, PDO::PARAM_STR);
    echo " the count: " . $count . "--" . $key . " \n";
    $count += 1;
  }
  $stm->execute();

}


$all_users = $stm->fetchAll();
$get_line = array();

//Seach button clicked
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  if (isset($_POST["searchdate_start"])) {
    $get_line[] = "searchdate_start=" . $_POST["searchdate_start"];
  }
  if (isset($_POST["searchdate_end"])) {
    $get_line[] = "searchdate_end=" . $_POST["searchdate_end"];
  }
  if (isset($_POST["searchorder_id"])) {
    $get_line[] = "searchorder_id=" . $_POST["searchorder_id"];
  }
  header("Location: admin-reports.php?". implode("&", $get_line));
}
 ?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>User Management</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/checkout/">

    <!-- Bootstrap core CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <!-- Favicons -->
<link rel="apple-touch-icon" href="/docs/4.5/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
<link rel="icon" href="/docs/4.5/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
<link rel="icon" href="/docs/4.5/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
<link rel="manifest" href="/docs/4.5/assets/img/favicons/manifest.json">
<link rel="mask-icon" href="/docs/4.5/assets/img/favicons/safari-pinned-tab.svg" color="#563d7c">
<link rel="icon" href="/docs/4.5/assets/img/favicons/favicon.ico">
<meta name="msapplication-config" content="/docs/4.5/assets/img/favicons/browserconfig.xml">
<meta name="theme-color" content="#563d7c">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

    </style>
    <!-- Custom styles for this template -->

  </head>
  <body class="bg-light">

  <div class="row">

  <div class="container">
  <div class="py-3 text-left">
    <h3>Users</h3>
    <br>

    <div class="row">
      <div class="col-md-4">
        <form method="POST">
          <div>
            <label for = "searchdate_start">Start date:</label>
            <input type="date" id= "searchdate_start" name="searchdate_start">
          </div>
          <div class="">
            <label for = "searchdate_end">End date:</label>
            <input type="date" id= "searchdate_end" name="searchdate_end">
          </div>
          <div class="">
            <label for = "searchorder_id">order_id:</label>
            <input type="text" id= "searchorder_id" name="searchorder_id">
          </div>
          <input type="submit" name="Search" value="Search">
        </form>
      </div>
      <div class="col-md-4 offset-md-4">
        <a class="btn btn-md btn-primary btn-" href="admin-dashboard.php" role="button">Back to Dashboard</a>
      </div>
    </div>

  <br>
  <div class="table-responsive">
    <table class="table table-striped table-md">
      <tbody>
        <tr>
          <thread>
            <th>response_order_id</th>
            <th>response_code</th>
            <th>date_stamp</th>
            <th>time_stamp</th>
            <th>result</th>
            <th>trans_name</th>
            <th>cardholder</th>
            <th>card</th>
            <th>charge_total</th>
            <th>f4l4</th>
            <th>message</th>
            <th>iso_code</th>
            <th>bank_approval_code</th>
            <th>bank_transaction_id</th>
            <th>txn_num</th>
            <th>avs_response_code</th>
            <th>cavv_result_code</th>
            <th>INVOICE</th>
            <th>ISSCONF</th>
            <th>ISSNAME</th>
          </thread>
        </tr>
        <!------------------------------------------->
        <?php foreach ($all_users as $row) {
        ?>
        <tr>
          <td><?php echo $row["response_order_id"]; ?></td>
          <td><?php echo $row["response_code"]; ?></td>
          <td><?php echo $row["date_stamp"]; ?></td>
          <td><?php echo $row["time_stamp"]; ?></td>
          <td><?php echo $row["result"]; ?></td>
          <td><?php echo $row["trans_name"]; ?></td>
          <td><?php echo $row["cardholder"]; ?></td>
          <td><?php echo $row["card"]; ?></td>
          <td><?php echo $row["charge_total"]; ?></td>
          <td><?php echo $row["f4l4"]; ?></td>
          <td><?php echo $row["message"]; ?></td>
          <td><?php echo $row["iso_code"]; ?></td>
          <td><?php echo $row["bank_approval_code"]; ?></td>
          <td><?php echo $row["bank_transaction_id"]; ?></td>
          <td><?php echo $row["txn_num"]; ?></td>
          <td><?php echo $row["avs_response_code"]; ?></td>
          <td><?php echo $row["cavv_result_code"]; ?></td>
          <td><?php echo $row["INVOICE"]; ?></td>
          <td><?php echo $row["ISSCONF"]; ?></td>
          <td><?php echo $row["ISSNAME"]; ?></td>
        </tr>
        <?php
        }
        ?>
      <!------------------------------------------->
      </tbody>
    </table>
  </div>

<hr class="mb-12">

</div>
</div>
</div>

</body>
</html>
