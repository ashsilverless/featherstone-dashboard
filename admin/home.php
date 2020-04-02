<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$_GET['nu'] == '1' ? $flag = '2' : $flag = '1';


//    Get the graph data
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT current_price,correct_at  FROM `tbl_fs_fund` where isin_code LIKE 'GB0009346486' AND bl_live = 1 ORDER BY correct_at ASC;";
	
    $result = $conn->prepare($query); 
    $result->execute();
	
	while($row = $result->fetch(PDO::FETCH_ASSOC)) { 
		$GB0009346486 .= $row['current_price'].',';
		$labels1 .= "'".$row['correct_at']."',";
	}
	
	$query = "SELECT current_price,correct_at  FROM `tbl_fs_fund` where isin_code LIKE 'GB00B1LB2Z79' AND bl_live = 1 ORDER BY correct_at ASC;";
	
    $result = $conn->prepare($query); 
    $result->execute();
	
	while($row = $result->fetch(PDO::FETCH_ASSOC)) { 
		$GB00B1LB2Z79 .= $row['current_price'].',';
		$labels2 .= "'".$row['correct_at']."',";
	}
	
	$query = "SELECT current_price,correct_at  FROM `tbl_fs_fund` where isin_code LIKE 'GB00BJQWRN41' AND bl_live = 1 ORDER BY correct_at ASC;";
	
    $result = $conn->prepare($query); 
    $result->execute();
	
	while($row = $result->fetch(PDO::FETCH_ASSOC)) { 
		$GB00BJQWRN41 .= $row['current_price'].',';
		$labels3 .= "'".$row['correct_at']."',";
	}

	

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}

$rspaging = '<div style="margin:auto; padding:15px 0 15px 0; text-align: center; font-size:16px; font-family: \'Ubuntu\',sans-serif;"><strong>'.$num_rows.'</strong> results in <strong>'.$totalPageNumber.'</strong> pages.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Page : ';
			  
if($page<3){
	$start=1;
	$end=7;
}else{
	$start=$page-2;
	$end=$page+4;
}


if($end >= $totalPageNumber){ 
  $endnotifier = "";
  $end = $totalPageNumber; 
}else{
  $endnotifier = "...";
}

$frst = '<a href="?page=0'.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">|&laquo;</a>';
$last = '<a href="?page='.($totalPageNumber-1).'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">&raquo;|</a>';

$rspaging .=  $frst;
for($a=$start;$a<=$end;$a++){
	$a-1 == $page ? $lnk='<strong style="font-size:13px; border: solid 1px #BBB; margin:5px; padding:5px;">'.$a.'</strong>' : $lnk='<a href="?page='.($a-1).'" style="font-size:13px; margin:5px; padding:5px;">'.$a.'</a>'; 
	$rspaging .=  $lnk;
}

$ipp = '<span style="margin-left:35px;">Show <a href="?rpp=10">10</a>&nbsp;|&nbsp;<a href="?rpp=30">30</a>&nbsp;|&nbsp;<a href="?rpp=50">50</a>&nbsp;|&nbsp;<a href="?rpp=999"><strong>All</strong></a></span>';
	
$rspaging .= $endnotifier.$last.$ipp.'</div>';

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <title>Dashboard</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
	
	<!-- Upload script -->
	<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>

  </head>

  <body>

	<nav class="navbar navbar-dark sticky-top bg-white flex-md-nowrap p-0">
		<div id="logo" class="col-md-2"><img src="images/fs_logo1.jpg" alt="" height="110" align="left"/></div>
		<div id="righthandside" class="col-md-10">
			<div id="title" style="cleath:both;"><h2><strong>Client Portal Admin Area</strong></h2></div>
			<div id="menuitems" class="mt-4">
				<a class="btn btn-admin shadow-sm active" href="home.php">Dashboard</a>
				<a class="btn btn-admin shadow-sm " href="funds.php">Funds</a>
				<a class="btn btn-admin shadow-sm " href="assets.php">Asset Allocation &amp; Holdings</a>
				<a class="btn btn-admin shadow-sm " href="themes.php">Themes</a>
				<a class="btn btn-admin shadow-sm " href="peers.php">Peers</a>
				<a class="btn btn-admin shadow-sm " href="clients.php">Clients</a>
				<a class="btn btn-admin shadow-sm" href="staff.php">Staff</a>
				
				<span style="float:right;"><a class="btn btn-grey shadow-sm" href="#" data-toggle="modal" data-target="#logoutModal">Log Out</a></span>
			</div>
		</div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
          <div class="sidebar-sticky mt-115 ml-3">
			  <h4>Hello <?=$_SESSION['username'];?></h4>
			  <p>Last Login:<br>1:24pm on Tue 12 Dec 19.</p>
			  <p><a href="#">Not You? Click here</a></p>
			  <a class="btn btn-admin shadow-sm" href="#">Account Settings</a>
			  <a class="btn btn-admin shadow-sm" href="#">Help &amp; Support</a>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4 mb-5">
			
		<!--   Upload TransFile  -->
			<h1 class="h2">Upload Transaction File</h1>
			
			<div class="col-md-4 mb-3">
				<div id="transfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="transcontainer"><a id="picktrans" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Choose File]</a></div><input type="text" id="trans_file" name="trans_file" readonly>
			</div>

			<div id="result" class="col-md-12 mb-3" style="height:300px; max-height:300px; overflow-y: scroll;"><div id="data_info" class="col-md-12 text-center" style="height:300px; max-height:300px; overflow-y: scroll;"></div></div>
			
		<div class="col-md-8 offset-2 mt-3 mb-3"><hr></div>
			
		<!-- / Upload Trans File -->

        <h1 class="h2">Fund Performance</h1>
			
		<div class="row">
              <div class="col-md-12">
                  <canvas class="my-4 w-100 chartjs-render-monitor" id="linechart1" height="400"></canvas>
				  <canvas class="my-4 w-100 chartjs-render-monitor" id="linechart2" height="400"></canvas>
				  <canvas class="my-4 w-100 chartjs-render-monitor" id="linechart3" height="400"></canvas>
              </div>
          </div>

			
		<div class="col-md-8 offset-2 mt-3 mb-3"><hr></div>
            
		<div id="assetdetails" class="col-md-12 mt-5"></div>
            
        </main>
      </div>
    </div>


	  
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
          <a class="btn btn-primary" href="index.php">Logout</a>
        </div>
      </div>
    </div>
  </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/js/all.min.js"></script>
      
     <!-- Graphs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <!-- Date Picker -->	  
	<link rel="stylesheet" href="css/bootstrap-datepicker3.css">
	<script src="js/bootstrap-datepicker.min.js"></script>
	  
    <script>
      feather.replace()
    </script>

   
      
    <script>
		
		/* #########################    Trans File Upload and display    ####################### */
		$("#result").load("showdata.php?f=<?=$flag;?>");
		
		
		var uploader = new plupload.Uploader({
			runtimes : 'html5,flash,silverlight,html4',
			browse_button : 'picktrans',
			container: document.getElementById('transcontainer'),
			url : 'upload.php',
			flash_swf_url : 'js/plupload/Moxie.swf',
			silverlight_xap_url : '.js/plupload/Moxie.xap',
			unique_names : true,
			filters : {
				max_file_size : '10mb',
				mime_types: [
					{title : "Data files", extensions : "txt,csv"}
				]
			},

			init: {
				PostInit: function() {
					document.getElementById('transfilelist').innerHTML = '';
				},

				FilesAdded: function(up, files) {
					uploader.start();
				},

				UploadProgress: function(up, file) {
					$('#data_info').html('<strong>Uploading & Parsing Datafile</strong><br>Please wait.....<br><br><img src="images/animated_progress.gif">');
				},

				FileUploaded: function(up, file, info) {
					var myData;
						try {
							myData = eval(info.response);
						} catch(err) {
							myData = eval('(' + info.response + ')');
						}
				   $('#data_info').html(''); 
					
					if(myData.error=="NULL"){
						$( "#trans_file" ).val(myData.result);  
				   		$("#result").load("showdata.php");
					}else{
						$("#result").html('<h3 style="text-align:center; color:red;"><strong>Error : '+myData.error+'</strong></h3>'); 
					}
				},


				Error: function(up, err) {
					console.log("\nError #" + err.code + ": " + err.message);
				}
			}
		});
		
		uploader.init();
		
	/* #########################    / Trans File Upload and display    ####################### */

		
		$(".toggler").click(function(e){
          e.preventDefault();
          $('.'+$(this).attr('data-prod-name')).toggle();
          $('.head'+$(this).attr('data-prod-name')).toggleClass( "highlight normal" );
          $('.arrow'+$(this).attr('data-prod-name'), this).toggleClass("fa-caret-up fa-caret-down");
    	});
		
		Chart.defaults.global.legend.display = false;
		
/* ##########################################       LINE CHART     ################################################## */



		var ctxline = document.getElementById('linechart1');
		var myLineChart = new Chart(ctxline, {
			type: 'line',
			data: {
				datasets: [{
					fill:false,
					lineTension:0,
					pointRadius:0,
					borderColor:['rgba(0, 0, 150, 0.75)'],
					borderWidth:2,
					label:'T. Bailey Growth Fund A Accumulation',
					data:[<?=mb_substr($GB0009346486, 0, -1);?>],
				}],
				labels: [<?=mb_substr($labels1,0, -1);?>]
			},
			
			options: { tooltips: {enabled: true}, legend: {display: true}}
		});
		
		var ctxline = document.getElementById('linechart2');
		var myLineChart = new Chart(ctxline, {
			type: 'line',
			data: {
				datasets: [{
					fill:false,
					lineTension:0,
					pointRadius:0,
					borderColor:['rgba(0, 150, 150, 0.75)'],
					borderWidth:2,
					label:'T. Bailey Dynamic Fund A  Accumulation',
					data:[<?=mb_substr($GB00B1LB2Z79, 0, -1);?>],
				}],
				labels: [<?=mb_substr($labels2,0, -1);?>]
			},
			
			options: { tooltips: {enabled: true}, legend: {display: true}}
		});
		
		var ctxline = document.getElementById('linechart3');
		var myLineChart = new Chart(ctxline, {
			type: 'line',
			data: {
				datasets: [{
					fill:false,
					lineTension:0,
					pointRadius:0,
					borderColor:['rgba(150, 0, 150, 0.75)'],
					borderWidth:2,
					label:'T. Bailey Dynamic Fund F Accumulation',
					data:[<?=mb_substr($GB00BJQWRN41, 0, -1);?>],
				}],
				labels: [<?=mb_substr($labels3,0, -1);?>]
			},
			
			options: { tooltips: {enabled: true}, legend: {display: true}}
		});

		
	function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

	 

    </script>
  </body>
</html>
