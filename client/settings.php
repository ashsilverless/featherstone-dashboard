<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$msg = $_GET['msg'];
$user_id = $_SESSION['featherstone_uid'];
$client_code = $_SESSION['featherstone_cc'];
$last_date = getLastDate('tbl_fs_transactions','fs_transaction_date','fs_transaction_date','fs_client_code = "'.$client_code.'"');

$lastlogin = date('g:ia \o\n D jS M y',strtotime(getLastDate('tbl_fsusers','last_logged_in','last_logged_in','id = "'.$_SESSION['user_id'].'"')));

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8


	$query = "SELECT * FROM tbl_fsusers where fs_client_code LIKE '$client_code' AND bl_live = 1;";

    $result = $conn->prepare($query); 
    $result->execute();
	
	while($row = $result->fetch(PDO::FETCH_ASSOC)) { 

		$clientData[] = $row;
	}

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="apple-touch-icon" sizes="57x57" href="../favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="../favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
    <link rel="manifest" href="../favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="../favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#333">

    <title>Dashboard</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/dashboard.css" rel="stylesheet">

  </head>

  <body>
    <nav class="navbar navbar-dark sticky-top bg-dkgrey flex-md-nowrap p-0 col-md-12 mb-3">
		<div id="logo" class="col-md-2"><img src="images/fs_logo1.jpg" alt="" width="96%" align="left"/></div>
		<div id="topmenu" class="col-md-10 flex-md-nowrap">
			<div id="menuitems" class="mt-4">
				<a class="btn-grey2 " href="home.php">Daily Valuation Data</a>
				<a class="btn-grey2  active" href="assets.php">Holdings &amp; Asset Allocation</a>
				<a class="btn-grey2 " href="current_investment.php">Current Investment Themes</a>
				<a class="btn-grey2 " href="peer_groups.php">Peer Group Comparison</a>
			</div>
		</div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        
		  <div class="col-md-3">
		  
			<div class="col-md-12 whtbrdr">

				  <p class="welcomename">Hello <?=$_SESSION['name'];?></p>
				  <p class="smaller">Not you ?  Click <a href="#">here</a></p>
				  <p class="small mt-4">Last Login:<br><?=$lastlogin;?></p>
				  <a class="btn-grey2 w100" href="settings.php"><i data-feather="settings"></i> Account Settings</a>
				  <a class="btn-grey2 w100" href="#" data-toggle="modal" data-target="#logoutModal"><i data-feather="corner-up-left"></i>  Log Out</a>
				  <a class="btn-grey2 w100" href="#"><i data-feather="download"></i> Download as PDF</a>

        	</div>
		  
		</div>


        <div class="col-md-9">
			  
		  <div class="col-md-12 whtbrdr">

			
			<h1 class="h2 mt-3 mb-5"><strong>Account Settings</strong></h1>
			
			<?php if($msg=='updated'){?>
			  <fieldset class="whtbrdr">
				<div id='updated'>
				<h3>Account Settings Successfully Updated.</h3>
				</div>
			</fieldset>
			<?php } ?>
			  
			<!-- ##########################		     Client Settings    ####################### -->
			<form action="editclient.php" method="post" id="editclient" name="editclient" class="mt-5">
				<div class="col-md-2" style="float:left;">
					<p>Prefix<br>
						<select name="user_prefix" id="user_prefix">
						  <option value="Mr" <?php if($clientData[0]['first_name']=='Mr'){?> selected<?php }?>>Mr</option>
						  <option value="Mrs" <?php if($clientData[0]['first_name']=='Mrs'){?> selected<?php }?>>Mrs</option>
						  <option value="Miss" <?php if($clientData[0]['first_name']=='Miss'){?> selected<?php }?>>Miss</option>
						  <option value="Dr" <?php if($clientData[0]['first_name']=='Dr'){?> selected<?php }?>>Dr</option>
						</select>
						</p>
				</div>

				<div class="col-md-5" style="float:left;">
					<p>First Name<br>
						<input type="text" id="first_name" name="first_name" style="width:90%" value="<?=$clientData[0]['first_name'];?>"></p>
				</div>

				<div class="col-md-5" style="float:left;">
					<p>Surname<br>
						<input type="text" id="last_name" name="last_name" style="width:90%" value="<?=$clientData[0]['last_name'];?>"></p>
				</div>

				<!-- #################################### -->

				<div class="col-md-4" style="float:left;">
					<p>User Name<br>
						<input type="text" id="user_name" name="user_name" style="width:90%" value="<?=$clientData[0]['user_name'];?>"></p>
				</div>

				<div class="col-md-4" style="float:left;">
					<p>Email<br>
						<input type="text" id="email_address" name="email_address" style="width:90%" value="<?=$clientData[0]['email_address'];?>"></p>
				</div>

				<div class="col-md-4" style="float:left;">
					<p>Mobile Phone<br>
						<input type="text" id="telephone" name="telephone" style="width:90%" value="<?=$clientData[0]['telephone'];?>"></p>
				</div>

				<!-- #################################### -->

				<div class="col-md-4" style="float:left;">
					<p>Password <br>
						<input type="password" id="password" name="password" style="width:90%" value="<?=$clientData[0]['password'];?>"></p>
				</div>

				<div class="col-md-4" style="float:left;">
					<p>New Password <br>
						<input type="password" id="newpassword" name="newpassword" style="width:90%" value=""></p>
				</div>

				<div class="col-md-4" style="float:left;">
					<p>Confirm Password <br>
						<input type="password" id="confirmpassword" name="confirmpassword" style="width:90%" value=""></p>
					<span id="message"></span>
				</div>

				<!-- ##########################		     Client Settings    ####################### -->
				<input name="client_code" type="hidden" id="client_code" value="<?=$client_code?>">

				<input id="submit" type="submit" name="submit" value="Save Changes" />
			</form>
			<div class="clearfix"></div>
            
          </div>  
        </div>
      </div>
    </div>
	  
	  
	<!-- Footer -->
	  <div class="auto-LogOut"></div>
      <footer class="col-md-12 mt-5">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Featherstone 2020 <?=$user_id;?></span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->
	  
	  
<!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="../index.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

<!--    Logged Out  -->
    <div class="modal fade" id="loggedout" tabindex="-1" role="dialog" aria-labelledby="LoggedOut" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Your Session has Timed Out</h5>
        </div>
        <div class="modal-body">Select "Login" below if you want to continue your session.</div>
        <div class="modal-footer">
		  <a class="btn btn-primary" href="../index.php">Login</a>
          <a class="btn btn-secondary quit" href="">Quit</a>
        </div>
      </div>
    </div>
  </div>
	  
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/js/all.min.js"></script>
      
    <!-- Graphs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
	<!-- Custom Scripts -->
    <script src="js/custom.js"></script>
    <script>
      feather.replace()
    </script>
	
    <script type="text/javascript">

		$('#newpassword, #confirmpassword').on('keyup', function() {
		  if ($('#newpassword').val() == $('#confirmpassword').val()) {
			$('#message').html('Matching').css('color', 'green');
			$('#submit').prop('disabled', false);
		  } else {
			$('#message').html('Not Matching').css('color', 'red');
			$('#submit').prop('disabled', true);
		  }
		});
        
    </script>
  </body>
</html>
