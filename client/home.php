<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


/*     
ini_set ("display_errors", "1");	error_reporting(E_ALL);
    */




$user_id = $_SESSION['featherstone_uid'];
$client_code = $_SESSION['featherstone_cc'];
$last_date = getLastDate('tbl_fs_transactions','fs_transaction_date','fs_transaction_date','fs_client_code = "'.$client_code.'"');

$lastlogin = date('g:ia \o\n D jS M y',strtotime(getLastDate('tbl_fsusers','last_logged_in','last_logged_in','id = "'.$_SESSION['user_id'].'"')));

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
	
	
     //    Get the general products data for Client   ///
		

  $query = "SELECT * FROM tbl_fsusers where fs_client_code LIKE '$client_code' AND bl_live = 1;";
	debug($query);
    
  $result = $conn->prepare($query); 
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	  $user_name = $row['user_name'];
  } 
	
    
     //    Get the products   ///

  $query = "SELECT DISTINCT fs_product_type FROM `tbl_fs_transactions` where fs_client_code LIKE '$client_code' AND bl_live = 1;";
    
  $result = $conn->prepare($query); 
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $products[] = $row;
  }
    
    //    Get the funds   ///
    
  $query = "SELECT DISTINCT fs_isin_code FROM `tbl_fs_transactions` where fs_client_code LIKE '$client_code' AND bl_live = 1;";

  $result = $conn->prepare($query); 
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $funds[] = $row;
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
    <meta name="theme-color" content="#ffffff">

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
				<a class="btn-grey2 active" href="assets.php">Holdings &amp; Asset Allocation</a>
				<a class="btn-grey2" href="current_investment.php">Current Investment Themes</a>
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
				  
				  <h1 class="h2 mt-3"><strong>Daily Valuation Data</strong></h1>
					<!--  #Data_accurate --><p>Data accurate as at <?= date('j M y',strtotime($last_date));?></p><!--  #Data_accurate -->
				  <h2><?=$user_name;?></h2>
				  <div class="table-responsive">
					<table class="table table-sm table-striped">
					  <thead>
						<tr>
						  <th>Account Name</th>
						  <th colspan="2">Invested</th>
						  <th colspan="2">Value</th>
						  <th colspan="2">Gain (&pound;)</th>
						  <th>Gain (&percnt;)</th>
						  <th>&nbsp;</th>
						</tr>
					  </thead>
					  <tbody>

						  <?php 
							  // Connect and create the PDO object
								$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
								$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
								foreach ($products as $product):
									$the_product = $product['fs_product_type'];
									$inv_ammount = $value = $total_shares_qty = 0; 
									$shares = array(); $shares_per = array();  $fund_name = array();  $invested_in_fund = array();
									$query = "SELECT * FROM `tbl_fs_transactions` where fs_deal_type NOT LIKE 'Periodic Advisor Charge' AND fs_product_type LIKE '$the_product' AND fs_client_code LIKE '$client_code' AND bl_live = 1 ORDER BY fs_transaction_date ASC;";
									debug($query); 

									$result = $conn->prepare($query); 
									$result->execute();
									debug('Record Count = '+$result->rowCount());
									  // Parse returned data
									  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
										  $account_name = $user_name." - ".$the_product;
										  $inv_ammount += $row['fs_iam'];
										  $isin = $row['fs_isin_code'];
										  //$latest_price = get_current_price($row['fs_isin_code']);
										  $shares[$isin] += $row['fs_shares'];
										  $fund_name[$isin] = $row['fs_fund_name'];
										  $invested_in_fund[$isin] += $row['fs_iam'];
										  $cur = $row['fs_currency_code'];
									  }

						  debug(count($invested_in_fund));

						  foreach($shares as $isin => $shares_qty) {
							  $value += $shares_qty * get_current_price("$isin");
							  $total_shares_qty += $shares_qty;
							  $classname = str_replace(" ","",$account_name);
							}
						  ?>
							<tr>
							  <td class="head<?=$classname;?> normal"><?=$account_name;?></td>
							  <td class="head<?=$classname;?> normal" colspan="2"><?=$cur_code[$cur] . number_format($inv_ammount,2);?></td>
							  <td class="head<?=$classname;?> normal" colspan="2"><?=$cur_code[$cur] . number_format(($value),2);?></td>
							  <td class="head<?=$classname;?> normal" colspan="2"><?=$cur_code[$cur] . number_format($value - $inv_ammount,2);?></td>
							  <td class="head<?=$classname;?> normal"><?=number_format(100*($value/$inv_ammount)-100,4);?></td>
							  <td class="head<?=$classname;?> normal"><a href="#" class="toggler indicator" data-prod-name="<?=$classname;?>"><i class="fas fa-caret-up arrow<?=$classname;?>"></i></a> </td>
							</tr>
						  <tr class="<?=$classname;?>" style="font-size:0.8em; font-weight:bold; display:none;">
							  <td> </td>
							  <td style="border-right:1px dashed #AAA;">Holding</td>
							  <td style="border-right:1px dashed #AAA;">Invested</td>
							  <td style="border-right:1px dashed #AAA;">Book Cost</td>
							  <td style="border-right:1px dashed #AAA;">Value</td>
							  <td style="border-right:1px dashed #AAA;">Growth(&pound;)</td>
							  <td style="border-right:1px dashed #AAA;">Growth(&percnt;)</td>
							  <td>Benchmark</td>
							  <td class="head<?=$classname;?>">&nbsp;</td>
							</tr>
						  <?php
						  foreach($shares as $isin => $shares_qty) {   
							  $shares_per[$isin] = ($shares_qty / $total_shares_qty) * 100;
							  $inv = $invested_in_fund[$isin];
							  $val = $shares_qty * get_current_price("$isin");
							  $growth = $val - $inv;
							  $growth_percent = ($growth/$inv) * 100;

							  if($shares_per[$isin]>0){
							  ?>
							<tr class="<?=$classname;?>" style="font-size:0.8em; display:none;">
							  <td><?=$fund_name[$isin];?>-<?=$isin;?></td>
							  <td style="border-right:1px dashed #AAA;"><?=round($shares_per[$isin],1);?>%</td>
							  <td style="border-right:1px dashed #AAA;"><?=$cur_code[$cur] . number_format($inv,2);?></td>
							  <td style="border-right:1px dashed #AAA;"></td>
							  <td style="border-right:1px dashed #AAA;"><?=$cur_code[$cur] . number_format($val,2);?></td>
							  <td style="border-right:1px dashed #AAA;"><?=$cur_code[$cur] . number_format($growth,2);?></td>
							  <td style="border-right:1px dashed #AAA;"><?=number_format($growth_percent,2);?>&percnt;</td>
							  <td><?=number_format(get_benchmark("$isin"),2);?>&percnt;</td>
							  <td class="head<?=$classname;?>"style="border-top:1px solid #999;">&nbsp;</td>
							</tr>
						  <?php } }?>


						  <?php endforeach; $conn = null;        // Disconnect?>

					  </tbody>
					</table>
				  </div>

					<div class="col-md-8 offset-2 mt-3 mb-3"><hr></div>

					<h1 class="h2 mt-3"><strong>Daily Valuation Data</strong></h1>
					<div class="container">

					  <div class="row">
						  <div class="col-md-12">
							  <canvas class="my-4 w-100 chartjs-render-monitor" id="linechart" height="400"></canvas>
						  </div>
					  </div>

				  </div>
				  
			  </div>
		  	
		  
		  </div>
		  
		  
      </div>
    </div>
	  
	  
	<!-- Footer -->
      <footer class="col-md-12 mt-5">
       <div class="auto-LogOut"></div>
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Featherstone 2020</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->
	  
	  
<!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="LogOut" aria-hidden="true">
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

   
      
   <script>
      $(".toggler").click(function(e){
        e.preventDefault();
          $('.'+$(this).attr('data-prod-name')).toggle();
          $('.head'+$(this).attr('data-prod-name')).toggleClass( "highlight normal" );
          $('.arrow'+$(this).attr('data-prod-name'), this).toggleClass("fa-caret-up fa-caret-down");
    	});
   
		Chart.defaults.global.legend.display = false;
		
/* ##########################################       LINE CHART     ################################################## */
		
		<?php
		try {
		  // Connect and create the PDO object
		  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
		  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

			// Latest Date
			$monthago = Date("Y-m-d", strtotime("2020-01-23 -60 days"));				 
							 
		//    Get the price data for Client Graph   ///

		  $query = "SELECT * FROM `tbl_fs_transactions` where fs_deal_type NOT LIKE 'Periodic Advisor Charge' AND fs_product_type LIKE 'ISA' AND fs_client_code LIKE '$client_code' AND bl_live = 1 AND fs_transaction_date > '$monthago' ORDER BY fs_transaction_date ASC;";

		  $result = $conn->prepare($query); 
		  $result->execute();

		  // Parse returned data
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			  $data1 .= $row['fs_t_price']*$row['fs_shares'].',';
			  $labels1 .= "'".$row['fs_transaction_date']."',";
		  }

		}

		catch(PDOException $e) {
		  echo $e->getMessage();
		}
		?>

		var ctxline = document.getElementById('linechart');
		var myLineChart = new Chart(ctxline, {
			type: 'line',
			data: {
				datasets: [{
					fill:false,
					lineTension:.3,
					borderColor:['rgba(0, 0, 0, 1)'],
					borderWidth:2,
					label:'Performance Data',
					data:[<?=$data1;?>],
				}],
				labels: [<?=$labels1;?>]
			},
			
			options: { tooltips: {enabled: true}}
		});
    </script>
  </body>
</html>
