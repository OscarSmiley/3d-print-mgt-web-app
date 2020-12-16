<?php
session_start();
require ('auth-sec.php'); //Gets CAS & db
//auth-sec includes: $user, $user_email, $user_type, $user_name
$stm = $conn->query("SELECT id, job_name, status, submission_date, priced_date, paid_date, printing_date, completed_date FROM print_job WHERE netlink_id = '$user' ORDER BY id DESC");
$data = $stm->fetchAll();
//split results by Status
$pending_payment = [];
$complete = [];
$paid_print_subm = [];
$other_jobs = [];
foreach ($data as $job) {
  if ($job['status'] == "pending payment") {
    $pending_payment[] = $job;
  }elseif ($job['status'] == "completed") {
    $complete[] = $job;
  }elseif ($job['status'] == "paid" || $job['status'] == "printing" || $job['status'] == "submitted") {
    $paid_print_subm[] = $job;
  }else{
    $other_jobs[] = $job;
  }

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
    <title>Your Dashboard</title>
    <!--header link-->
    <link rel="stylesheet" href="css/uvic_banner.css">
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
    <link href="form-validation.css" rel="stylesheet">
  </head>
  <body class="bg-light">

    <!--Header-->
    <div id="custom_header"><div class="wrapper" style="min-height: 6em;" id="banner">
     <div style="position:absolute; left: 5px; top: 26px;">
      <a href="http://www.uvic.ca/" id="logo"><span>University of Victoria</span></a>
     </div>
     <div style="position:absolute; left: 176px; top: 26px;">
      <a href="http://www.uvic.ca/library/" id="unit"><span>Libraries</span></a>
     </div>
     <div class="edge" style="position:absolute; margin: 0px;right: 0px; top: 0px; height: 96px; width:200px;">&nbsp;</div>
    </div>
    <!--Header end-->

    <div class="container">
  <div class="py-5 text-center">

    <h1><b> DSC 3D printing dashboard</b></h1>
  </div>

  <div class="row">
    <div class="col-md-4 order-md-2 mb-4">
      <h4 class="d-flex justify-content-between align-items-center mb-3">
    </div>

    <div class="col-md-12 order-md-1">

      <div class="row">
        <div class="btn-auto mr-auto">
        <a href="customer-new-job.php">
          <button class="btn btn-primary btn-lg" type="submit">Create New Print Job</button>
        </a>
        </div>
        <div class="btn-auto mr-auto">
        <a href="3dfaq.html">
          <button class="btn btn-danger btn-lg" type="submit">FAQ</button>
        </a>
      </div>
      <?php if ($user_type == 0){ ?>
        <div class="btn-auto mr-auto">
          <a href="admin-dashboard.php">
            <button class="btn btn-primary btn-lg" type="submit">Admin Dashboard</button>
          </a>
        </div>
    <?php } ?>
    </div>

        <hr class="mb-12">

        <h2>Your Print Job</h2>
      <div class="table-responsive">
        <table class="table table-striped table-md">
        <tbody>
          <?php
          //pending payment jobs
          if (!empty($pending_payment)) {
            ?>
            <thead>
              <tr>
                <th>Priced Date</th>
                <th>Name</th>
                <th>Status</th>
              </tr>
            </thead>
            <?php foreach ($pending_payment as $row) {
            ?>
            <tr>
              <td><?php echo $row["priced_date"]; ?></td>
              <td><a href="customer-job-information.php?job_id=<?php echo $row["id"]; ?>"><?php echo $row["job_name"]; ?></a></td>
              <td><?php echo $row["status"]; ?></td>
            </tr>
            <?php
            }
          }
          //Completed jobs
          if (!empty($complete)) {
            ?>
            <thead>
              <tr>
                <th>Completed Date</th>
                <th>Name</th>
                <th>Status</th>
              </tr>
            </thead>
            <?php foreach ($complete as $row) {
            ?>
            <tr>
              <td><?php echo $row["completed_date"]; ?></td>
              <td><a href="customer-job-information.php?job_id=<?php echo $row["id"]; ?>"><?php echo $row["job_name"]; ?></a></td>
              <td><?php echo $row["status"]; ?></td>
            </tr>
            <?php
            }
          }
          //Paid & printing jobs
          if (!empty($paid_print_subm)) {
            ?>
            <thead>
              <tr>
                <th>Last Updated</th>
                <th>Name</th>
                <th>Status</th>
              </tr>
            </thead>
            <?php foreach ($paid_print_subm as $row) {
            ?>
            <tr>
              <?php if ($row["status"] == "paid") { ?>
                <td><?php echo $row["paid_date"]; ?></td>
              <?php }elseif ($row["status"] == "printing") { ?>
                <td><?php echo $row["printing_date"]; ?></td>
              <?php } else { ?>
                <td><?php echo $row["submission_date"]; ?></td>
              <?php } ?>
              <td><a href="customer-job-information.php?job_id=<?php echo $row["id"]; ?>"><?php echo $row["job_name"]; ?></a></td>
              <td><?php echo $row["status"]; ?></td>
            </tr>
            <?php
            }
          }
          //Other jobs
          if (!empty($other_jobs)) {
            ?>
            <thead>
              <tr>
                <th>Date</th>
                <th>Name</th>
                <th>Status</th>
              </tr>
            </thead>
            <?php foreach ($other_jobs as $row) {
            ?>
            <tr>
              <td><?php echo $row["completed_date"]; ?></td>
              <td><a href="customer-job-information.php?job_id=<?php echo $row["id"]; ?>"><?php echo $row["job_name"]; ?></a></td>
              <td><?php echo $row["status"]; ?></td>
            </tr>
            <?php
            }
          }
          ?>

           </tbody>
        </table>
      </div>

        <hr class="mb-12">

        <a class="btn btn-md btn-block" href="?logout=" role="button">Log Out</a>

    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="/docs/4.5/assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="/docs/4.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd" crossorigin="anonymous"></script>
        <script src="form-validation.js"></script></body>
</html>
