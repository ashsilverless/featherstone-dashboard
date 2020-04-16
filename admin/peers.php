<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


//    Get the user details
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT * FROM `tbl_fs_peers` where bl_live > 0 ORDER BY id ASC;";

    $result = $conn->prepare($query);
    $result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$peerGroup[] = $row;
	}

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}

?>
<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/header.php');
require_once('page-sections/header-elements.php');
?>

<div class="container">
    <div class="border-box main-content">
<h1 class="heading heading__2">Peer Comparison</h1>

<div class="peer-table">
    <div class="peer-table__head">
        <h3 class="heading heading__4">Peer</h3>
        <h3 class="heading heading__4">Return</h3>
        <h3 class="heading heading__4">Volatility</h3>
        <h3 class="heading heading__4">Trend Line</h3>
    </div>

    <div class="recess-box">

    <?php foreach($peerGroup as $peer) {
        $peer['fs_trend_line'] == '0' ? $trendLine = '<img src="images/square.svg" width="15">' : $trendLine = '<img src="images/check-square.svg" width="15">';
        ?>
    <div class="peer-table__item">
        <h3 class="heading heading__4"><?= $peer['fs_peer_name'];?></h3>
        <p><?= $peer['fs_peer_return'];?></p>
        <p><?= $peer['fs_peer_volatility'];?></p>
        <a href="edittrend.php?id=<?= $peer['id'];?>&tl=<?=$peer['fs_trend_line'];?>" class="btn btn-admin" style="font-size:0.8em; font-weight:bold;"><?=$trendLine;?></a>
        <a href="edit_peer.php?id=<?= $peer['id'];?>" class="button button__raised">Edit</a>
        <a href="#" data-href="deletepeer.php?id=<?= $peer['id'];?>" data-toggle="modal" data-target="#confirm-delete" class="button button__raised button__danger">Delete</a>
    </div><!--item-->
<?php } ?>
</div>
</div><!--table-->

</div>
</div><!--container-->
        <!--<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4 mb-5">

			<div id="peergroups" class="col-md-12 mt-5">
					<div id="theme_details" class="col-md-8" style="float:left;">
					  <h4>Peer Comparison</h4>
						<table class="table table-sm table-striped">
							<thead>
								<tr>
								  <th width="45%" valign="middle" bgcolor="#FFFFFF"><strong>Peer <i data-feather="maximize-2" style="transform: rotate(-45deg)"></i></strong></th>
								  <th width="15%" valign="middle" bgcolor="#FFFFFF"><strong>Return <i data-feather="maximize-2" style="transform: rotate(-45deg)"></i></strong></th>
								  <th width="15%" valign="middle" bgcolor="#FFFFFF"><strong>Volatility <i data-feather="maximize-2" style="transform: rotate(-45deg)"></i></strong></th>
								  <th width="5%" valign="middle" bgcolor="#FFFFFF"><strong>Trend<br>Line</strong></th>
								  <th width="20%" valign="middle" bgcolor="#FFFFFF"></td>
							  </tr>
							</thead>
							<tbody>
								<?php foreach($peerGroup as $peer) {
									$peer['fs_trend_line'] == '0' ? $trendLine = '<img src="images/square.svg" width="15">' : $trendLine = '<img src="images/check-square.svg" width="15">';
									?>
                                    <tr>
										<td style="border-right:1px dashed #999;"><span class="c" style="--c: <?= $peer['fs_peer_color'];?>"><?= $peer['fs_peer_name'];?></span></td>
                                      <td style="border-right:1px dashed #999;"><?= $peer['fs_peer_return'];?></td>
                                      <td style="border-right:1px dashed #999;"><?= $peer['fs_peer_volatility'];?></td>
										<td style="border-right:1px dashed #999;"><a href="edittrend.php?id=<?= $peer['id'];?>&tl=<?=$peer['fs_trend_line'];?>" class="btn btn-admin" style="font-size:0.8em; font-weight:bold;"><?=$trendLine;?></a></td>
                                      <td><a href="edit_peer.php?id=<?= $peer['id'];?>" class="edit btn btn-admin" style="font-size:0.8em; font-weight:bold;">Edit</a><a href="#" data-href="deletepeer.php?id=<?= $peer['id'];?>" data-toggle="modal" data-target="#confirm-delete" class="btn btn-danger" style="font-size:0.8em; font-weight:bold;">Delete</a></td>
                                  </tr>
									<?php } ?>
							  </tbody>
							</table>






					</div>



					<div id="peer_actions" class="col-md-4" style="float:left;">
						<h5>Edit/Add Peer</h5>
						<div id="peer">
							<form action="addpeer.php" method="post" id="addpeer" name="addpeer">
								<table width="100%" border="0">
								  <tbody>
									<tr>
									  <td colspan="2"><p>Peer Group Name<br> <input type="text" id="fs_peer_name" name="fs_peer_name" style="width:90%;"></p></td>
									  </tr>
									<tr>
									  <td><p>Return<br><input type="text" name="fs_peer_return" id="fs_peer_return" class="calculator-input" onkeypress="return event.charCode >= 46 && event.charCode <= 57" size="5"></p>
										<p>Trend Line<br><input type="checkbox" name="fs_trend_line" id="fs_trend_line" value="1"><label for="fs_trend_line">Yes </label></p></td>
									  <td><p>Volatility<br><input type="text" name="fs_peer_volatility" id="fs_peer_volatility" class="calculator-input" onkeypress="return event.charCode >= 46 && event.charCode <= 57" size="5"></p>
										<p>Trend Colour<br><input size="7" id="fs_peer_color" name="fs_peer_color" class="jscolor {hash:true}" value="000000"></p>	</td>
									</tr>

									<tr>
									  <td colspan="2"><input type="submit" style="font-size:0.8em;" class="btn btn-grey" value="Save Changes" <?php if($_SESSION['agent_level']< '2'){ ?>disabled<?php }?>></td>
									  </tr>
								  </tbody>
								</table>
						  </form>
						</div>
					</div>

			</div>

        </main>-->
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
  <div class="modal deletepeer" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModal">Delete this Peer Group ?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Continue" below if you are ready to<br>delete this Peer Group.</div>
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
	<!-- Table Sorter -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.2/js/jquery.tablesorter.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.2/js/jquery.tablesorter.widgets.min.js"></script>

	<!-- Colour Picker -->
	<script src="js/jscolor.js"></script>

    <script>

	$(document).ready(function() {

		feather.replace()

		$(".table").tablesorter();

		$(".edit").click(function(e){
          e.preventDefault();
		  var peer_id = getParameterByName('id',$(this).attr('href'));
		  $("#peer").load("edit_peer.php?id="+peer_id);
		});

		$('#confirm-delete').on('show.bs.modal', function(e) {
			$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
		});

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
