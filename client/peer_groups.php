<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


/*     
ini_set ("display_errors", "1");
error_reporting(E_ALL);
    */




$user_id = $_SESSION['featherstone_uid'];
$client_code = $_SESSION['featherstone_cc'];
$last_date = getLastDate('tbl_fs_transactions','fs_transaction_date','fs_transaction_date','fs_client_code = "'.$client_code.'"');

$lastlogin = date('g:ia \o\n D jS M y',strtotime(getLastDate('tbl_fsusers','last_logged_in','last_logged_in','id = "'.$_SESSION['user_id'].'"')));

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8


     //    Get the Peer Group Data   ///

  $query = "SELECT * FROM tbl_fs_peers WHERE bl_live = 1 AND fs_trend_line = '0' ;";
  $peer_data = $peer_colour = $peer_name = '';

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	  $peer_data .= "{ x: ".$row['fs_peer_return'].", y:".$row['fs_peer_volatility'].", n:'".$row['fs_peer_name']."'},";
	  $peer_colour .= '"'.$row['fs_peer_color'].'",';
	  $peer_name .= '"'.$row['fs_peer_name'].'",';
	  //$peer_data .= "[ ".$row['fs_peer_return'].",".$row['fs_peer_volatility'].", '".$row['fs_peer_name']."', 'point { size: 4; fill-color: ".$row['fs_peer_color']."; }','".$row['fs_peer_volatility']."% Volatility'],";
  }


$query = "SELECT * FROM tbl_fs_peers WHERE bl_live = 1 AND fs_trend_line = '1' ;";
  $peer_data_line = '';

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	  $peer_data_line .= "{ x: ".$row['fs_peer_return'].", y:".$row['fs_peer_volatility'].", n:'".$row['fs_peer_name']."'},";
	  $peer_colour_line .= '"'.$row['fs_peer_color'].'",';
	  $peer_name_line .= '"'.$row['fs_peer_name'].'",';
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


				<h1 class="h2 mt-3"><strong>Peer Group Comparison</strong></h1>
				<!--  #Data_accurate --><p>Data accurate as at <?= date('j M y',strtotime($last_date));?></p><!--  #Data_accurate -->

						  <!-- <div id="chart_div" style="width: 900px; height: 500px;"></div>  -->
					<canvas class="chartjs-render-monitor" id="scatterchart"></canvas>




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


	function drawChart() {
	 //Chart.defaults.global.legend.display = false;
     var ctx = document.getElementById('scatterchart');

		Chart.pluginService.register({
		  beforeRender: function(chart) {
			if (chart.config.options.showAllTooltips) {
			  // create an array of tooltips
			  // we can't use the chart tooltip because there is only one tooltip per chart
			  chart.pluginTooltips = [];
			  chart.config.data.datasets.forEach(function(dataset, i) {
				chart.getDatasetMeta(i).data.forEach(function(sector, j) {
				  chart.pluginTooltips.push(new Chart.Tooltip({
					_chart: chart.chart,
					_chartInstance: chart,
					_data: chart.data,
					_options: chart.options.tooltips,
					_active: [sector]
				  }, chart));
				});
			  });

			  // turn off normal tooltips
			  chart.options.tooltips.enabled = false;
			}
		  },
		  afterDraw: function(chart, easing) {
			if (chart.config.options.showAllTooltips) {
			  // we don't want the permanent tooltips to animate, so don't do anything till the animation runs atleast once
			  if (!chart.allTooltipsOnce) {
				if (easing !== 1)
				  return;
				chart.allTooltipsOnce = true;
			  }

			  // turn on tooltips
			  chart.options.tooltips.enabled = true;
			  Chart.helpers.each(chart.pluginTooltips, function(tooltip) {
				tooltip.initialize();
				tooltip.update();
				// we don't actually need this since we are not animating tooltips
				tooltip.pivot();
				tooltip.transition(easing).draw();
			  });
			  chart.options.tooltips.enabled = false;
			}
		  }
		});

		var chart = new Chart(ctx, {
		   type: 'scatter',
		   labels: 'Peer Groups',
		   data: {
			  datasets: [{
				 label: [<?=substr($peer_name_line, 0, -1);?>],
				 data: [<?=substr($peer_data_line, 0, -1);?>],
				 borderColor: 'red',
				 borderWidth: 1,
				 pointBackgroundColor: [<?=substr($peer_colour_line, 0, -1);?>],
				 pointBorderColor: [<?=substr($peer_colour_line, 0, -1);?>],
				 pointRadius: 8,
				 pointHoverRadius: 8,
				 fill: false,
				 tension: 0,
				 showLine: true
			  	 }, {
				 label: [<?=substr($peer_name, 0, -1);?>],
				 data: [<?=substr($peer_data, 0, -1);?>],
				 pointBackgroundColor: [<?=substr($peer_colour, 0, -1);?>],
				 pointBorderColor: [<?=substr($peer_colour, 0, -1);?>],
				 pointRadius: 8,
				 pointHoverRadius: 8
			  }]
		   },
		   options: {
			  legend: {
				display: false
			 },
			  showAllTooltips: true,
			  tooltips: {
				 backgroundColor: 'rgba(255, 255, 255, 0.1)',
				 bodyFontColor: '#FFF',
				 displayColors: false,
				 callbacks: {
					label: function(tooltipItem, data) {
					   var tLabel = data.datasets[tooltipItem.datasetIndex].label[tooltipItem.index];
					   var yLabel = tooltipItem.yLabel;
					   return tLabel + '    ' + yLabel.toFixed(2) + '% Volatility';
					}
				 }
			  },
			 scales: {
				yAxes: [{
					scaleLabel: {
					  display: true,
					  labelString: 'Volatility %'
					},
					gridLines: {
					  display: true ,
					  color: "rgba(255, 255, 255, 0.15)"
					}
				}],
				xAxes: [{
					scaleLabel: {
					  display: true,
					  labelString: 'Return £'
					},
					gridLines: {
					  display: true ,
					  color: "rgba(255, 255, 255, 0.15)"
					}
				}]
        }

		   }
		});

	};
/*

tooltips: {
  callbacks: {
    label: function(tooltipItem, data) {
      var item = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
      return item.y  + ' ' + item.value;
    }
  }
}

*/

	$( document ).ready(function() {
		drawChart();
    });


    </script>
  </body>
</html>
