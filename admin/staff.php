<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$user_type = array("1"=>"Admin", "2"=>"Super Admin", "999"=>"<i style='color:red;font-weight:bold;font-size:0.9em;'>! Temporary Block !</i>");

//  Order By
if($_GET['ob']!=""){
	$_SESSION["ob"] = $_GET['ob'];
	$_SESSION["od"] = $_GET['od'];
}

//  Record per page
if($_GET['rpp']!=""){
	$_SESSION["rpp"] = $_GET['rpp'];
}

if($_GET['page']!=""){
	$page=$_GET['page'];
}

if($page==""){
	$page = 0;
}

$recordsPerPage = $_SESSION["rpp"];

if($recordsPerPage==""){
	$recordsPerPage = 10;
}


//    Get the user details
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT id FROM `tbl_fsadmin` where bl_live > 0 ORDER BY last_name ASC;";

    $result = $conn->prepare($query);
    $result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $row;
	}

	$num_rows = count($rows);

	$totalPageNumber = ceil($num_rows / $recordsPerPage);
	$offset = $page*$recordsPerPage;

	debug($num_rows);

	$query = "SELECT *  FROM `tbl_fsadmin` where bl_live > 0 ORDER BY last_name ASC LIMIT $offset,$recordsPerPage;";

    $result = $conn->prepare($query);
    $result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$userData[] = $row;
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
<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/header.php');
require_once('page-sections/header-elements.php');
?>

<div class="container">
    <div class="border-box main-content">
		<a href="#" class="button button__raised button__inline">Add New Staff Member</a>
		<h1 class="heading heading__2">Staff Members</h1>

<div class="staff-table">
    <div class="staff-table__head">
        <h3 class="heading heading__4">Staff Member</h3>
        <h3 class="heading heading__4">Email Address</h3>
        <h3 class="heading heading__4">Type</h3>
        <h3 class="heading heading__4">Last Login</h3>
    </div>

    <div class="recess-box">
		<?php
		foreach($userData as $staff) {?>
    <div class="staff-table__item">

<h3 class="heading heading__4"><?= $staff['user_prefix'].' '.$staff['first_name'].' '.$staff['last_name'];?></h3>
<p><?= $staff['email_address'];?></p>

<p><?= $user_type[$staff['agent_level']];?></p>

<p><?= date('H:i j M y',strtotime($staff['last_logged_in']));?></p>

<a href="#?id=<?= $staff['id'];?>" class="button button__raised">Edit</a>

	</div>
<?php } ?>
</div><!--recess-->


</div><!--staff-table-->
</div>
</div><!--container-->

        <!--<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4 mb-5">

        <h1 class="h2">Staff</h1>
			<?php if($_SESSION['agent_level']>1){ ?><a href="#" class="add btn btn-add"><i data-feather="plus-square"></i> Add New Staff</a><?php }?>

			<div class="col-md-10  table-responsive mt-5">
			  <table class="table table-sm table-striped">

				<thead>
					<tr>
				      <th width="30%" bgcolor="#FFFFFF"><strong>Staff Member <i data-feather="maximize-2" style="transform: rotate(-45deg)"></i></strong></td>
					  <th width="20%" bgcolor="#FFFFFF"><strong>Email Address <i data-feather="maximize-2" style="transform: rotate(-45deg)"></i></strong></td>
					  <th width="20%" bgcolor="#FFFFFF"><strong>Type  <i data-feather="maximize-2" style="transform: rotate(-45deg)"></i></strong></td>
					  <th width="25%" bgcolor="#FFFFFF"><strong>Last Login  <i data-feather="maximize-2" style="transform: rotate(-45deg)"></i></strong></td>
					  <th width="5%" bgcolor="#FFFFFF"></td>
				  </tr>
				  </thead>
					<tbody>
					<?php
					foreach($userData as $staff) {?>
								<tr>
								  <td style="border-right:1px dashed #999;"><?= $staff['user_prefix'].' '.$staff['first_name'].' '.$staff['last_name'];?></td>
								  <td style="border-right:1px dashed #999;"><?= $staff['email_address'];?></td>
								  <td style="border-right:1px dashed #999;"><?= $user_type[$staff['agent_level']];?></td>
								  <td><?= date('H:i j M y',strtotime($staff['last_logged_in']));?></td>
								  <td><a href="#?id=<?= $staff['id'];?>" class="edit btn btn-admin" style="font-size:0.8em; font-weight:bold;">Edit</a></td>
							  </tr>
						<?php } ?>
			      </tbody>
				</table>


		  </div>

			<?=$rspaging;?>



		<div class="col-md-8 offset-2 mt-3 mb-3"><hr></div>

		<div id="staffdetails" class="col-md-12 mt-5"></div>

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
  <div class="modal deletefund" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModal">Delete this Staff Member?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Continue" below if you are ready to<br>delete this Staff Member.</div>
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

    <script>
      feather.replace()
    </script>



    <script>

		$(".add").click(function(e){
          e.preventDefault();
		  $("#staffdetails").load("add_staff.php");
		});

		$(".edit").click(function(e){
          e.preventDefault();
		  var staff_id = getParameterByName('id',$(this).attr('href'));
			console.log(staff_id);
		  $("#staffdetails").load("edit_staff.php?id="+staff_id);
		});

		$('#confirm-delete').on('show.bs.modal', function(e) {
			$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
		});


$( document ).ready(function() {

	$(".table").tablesorter();

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
