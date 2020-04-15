<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
?>
<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/header.php');
require_once('page-sections/header-elements.php');
?>

<div class="container">
    <div class="border-box main-content daily-data">
<a href="#" class="button button__raised button__inline">Add Asset</a>
<h1 class="heading heading__2">Asset Allocation & Holdings</h1>

<div class="asset-table">
    <div class="asset-table__head">
        <h3 class="heading heading__4">Asset Name</h3>
        <h3 class="heading heading__4">Category</h3>
        <h3 class="heading heading__4">Steady</h3>
        <h3 class="heading heading__4">Sensible</h3>
        <h3 class="heading heading__4">Serious</h3>
    </div>
    <?php
    try {
      // Connect and create the PDO object
      $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
      $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

        $query = "SELECT *  FROM `tbl_fs_assets` where bl_live = 1;";

        $result = $conn->prepare($query);
        $result->execute();

              // Parse returned data
              while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $row['fs_growth_steady'] == '0' ? $show_steady = '' : $show_steady = $row['fs_growth_steady'].'%';
                $row['fs_growth_sensible'] == '0' ? $show_sensible = '' : $show_sensible = $row['fs_growth_sensible'].'%';
                $row['fs_growth_serious'] == '0' ? $show_serious = '' : $show_serious = $row['fs_growth_serious'].'%';
              ?>

    <div class="asset-table__item">
        <p><?= $row['fs_asset_name'];?></p>
        <p><?= getField('tbl_fs_categories','cat_name','id',$row['cat_id']);?></p>
        <p><?= $show_steady;?></p>
        <p><?= $show_sensible;?></p>
        <p><?= $show_serious;?></p>
        <p><a href="#?id=<?=$row['id'];?>" class="button button__raised">Edit Asset</a></p>
    </div>
    <?php
        $steady += $row['fs_growth_steady'];      $sensible += $row['fs_growth_sensible'];      $serious += $row['fs_growth_serious'];
    }

    $conn = null;        // Disconnect
    $steady < 100 ? $steadyStyle = 'color:red;' : $steadyStyle = 'color:black;';
    $sensible < 100 ? $sensibleStyle = 'color:red;' : $sensibleStyle = 'color:black;';
    $$serious < 100 ? $seriousStyle = 'color:red;' : $seriousStyle = 'color:black;';
    }

    catch(PDOException $e) {
    echo $e->getMessage();
    }
    ?>





</div>


        <!--<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4 mb-5">

			<div class="table-responsive mt-5">
			  <table class="table table-sm table-striped">
			    <tbody>
					<tr>
				      <td bgcolor="#FFFFFF"><strong>Asset Name</strong></td>
					  <td bgcolor="#FFFFFF"><strong>Category</strong></td>
					  <td bgcolor="#FFFFFF"><strong>Steady</strong></td>
					  <td bgcolor="#FFFFFF"><strong>Sensible</strong></td>
					  <td bgcolor="#FFFFFF"><strong>Serious</strong></td>
					  <td bgcolor="#FFFFFF"></td>
				  </tr>
					<?php
					try {
					  // Connect and create the PDO object
					  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
					  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

						$query = "SELECT *  FROM `tbl_fs_assets` where bl_live = 1;";

					  	$result = $conn->prepare($query);
					  	$result->execute();

							  // Parse returned data
							  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
								$row['fs_growth_steady'] == '0' ? $show_steady = '' : $show_steady = $row['fs_growth_steady'].'%';
								$row['fs_growth_sensible'] == '0' ? $show_sensible = '' : $show_sensible = $row['fs_growth_sensible'].'%';
								$row['fs_growth_serious'] == '0' ? $show_serious = '' : $show_serious = $row['fs_growth_serious'].'%';
							  ?>
								<tr>
								  <td><?= $row['fs_asset_name'];?></td>
								  <td><?= getField('tbl_fs_categories','cat_name','id',$row['cat_id']);?></td>
								  <td><?= $show_steady;?></td>
								  <td><?= $show_sensible;?></td>
								  <td><?= $show_serious;?></td>
								  <td><a href="#?id=<?=$row['id'];?>" class="editasset btn" style="font-size:0.6em; font-weight:bold; margin-right:10px;">Edit Asset</a><a href="#" data-href="deleteasset.php?id=<?= $row['id'];?>" data-toggle="modal" data-target="#confirm-delete" class="btn btn-danger" style="font-size:0.6em; font-weight:bold;">Delete Asset</a></td>
							    </tr>
							<?php
								$steady += $row['fs_growth_steady'];      $sensible += $row['fs_growth_sensible'];      $serious += $row['fs_growth_serious'];
							}

					  $conn = null;        // Disconnect
						$steady < 100 ? $steadyStyle = 'color:red;' : $steadyStyle = 'color:black;';
						$sensible < 100 ? $sensibleStyle = 'color:red;' : $sensibleStyle = 'color:black;';
						$$serious < 100 ? $seriousStyle = 'color:red;' : $seriousStyle = 'color:black;';
					}

					catch(PDOException $e) {
					  echo $e->getMessage();
					}
					?>
					<tr>
								  <td></td>
								  <td>Totals :</td>
								  <td><strong style="<?=$steadyStyle;?>"><?= $steady;?>&percnt;</strong></td>
								  <td><strong style="<?=$sensibleStyle;?>"><?= $sensible;?>&percnt;</strong></td>
								  <td><strong style="<?=$seriousStyle;?>"><?= $serious;?>&percnt;</strong></td>
								  <td></td>
							    </tr>
			      </tbody>
				</table>
		  </div>





		<div class="col-md-8 offset-2 mt-3 mb-3"><hr></div>

		<div id="assetdetails" class="col-md-12 mt-5"></div>

    </main>->>
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


<!-- Delete Modal-->
  <div class="modal deletefund" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModal">Delete this Asset?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Continue" below if you are ready to<br>delete this Asset.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-danger btn-ok">Delete</a>
        </div>
      </div>
    </div>
  </div>

   <div class="modal deletecat" id="confirm-catdelete" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModal">Delete this Category?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Continue" below if you are ready to<br>delete this Category.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-danger btn-ok">Delete</a>
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

		$(".toggler").click(function(e){
          e.preventDefault();
          $('.'+$(this).attr('data-prod-name')).toggle();
          $('.head'+$(this).attr('data-prod-name')).toggleClass( "highlight normal" );
          $('.arrow'+$(this).attr('data-prod-name'), this).toggleClass("fa-caret-up fa-caret-down");
    	});

		$(".addasset").click(function(e){
          e.preventDefault();
		  $("#assetdetails").load("add_asset.php");
		});

		$(".editasset").click(function(e){
          e.preventDefault();
		  var theme_id = getParameterByName('id',$(this).attr('href'));
			console.log(theme_id);
		  $("#assetdetails").load("edit_asset.php?id="+theme_id);
		});

		$('#confirm-delete, #confirm-catdelete').on('show.bs.modal', function(e) {
			$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
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
