<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
?>
<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/header.php');
require_once('page-sections/header-elements.php');
?>

<div class="container">
    <div class="border-box main-content">
<a href="#" class="button button__raised button__inline">Add New Theme</a>
<h1 class="heading heading__2">Themes</h1>

<div class="themes-table">
    <div class="themes-table__head">
        <h3 class="heading heading__4">Theme Name</h3>
        <h3 class="heading heading__4">Icon</h3>
        <h3 class="heading heading__4">Narrative</h3>
    </div>

    <div class="recess-box">

<?php
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT *  FROM `tbl_fs_themes` where bl_live = 1;";

    $result = $conn->prepare($query);
    $result->execute();

          // Parse returned data
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {  ?>
    <div class="themes-table__item">
        <h3 class="heading heading__4"><?= $row['fs_theme_title'];?></h3>
        <img src="../icons_folder/<?= $row['fs_theme_icon'];?>">
        <p><?= substr($row['fs_theme_narrative'],0,385);?>...</p>
        <a href="#?id=<?=$row['id'];?>" class="button button__raised">Edit Theme</a>
        <a href="#" data-href="deletetheme.php?id=<?= $row['id'];?>" data-toggle="modal" data-target="#confirm-delete" class="button button__raised button__danger">Delete Theme</a></td>
    </div>
<?php }
$conn = null;        // Disconnect
}
catch(PDOException $e) {
echo $e->getMessage();
}?>


    </div>
</div><!--themes table-->


</div><!--col-12-->

        <!--<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4 mb-5">

        <h1 class="h2">Themes</h1>
			<a href="#" class="addtheme btn btn-add"><i data-feather="plus-square"></i> Add Theme</a>

			<div class="table-responsive mt-5">
			  <table class="table table-sm table-striped">
			    <tbody>
					<tr>
				      <td width="16%" bgcolor="#FFFFFF"><strong>Theme Name</strong></td>
					  <td width="40%" bgcolor="#FFFFFF"><strong>Narrative</strong></td>
					  <td width="12%" bgcolor="#FFFFFF"><strong>Actioned By</strong></td>
					  <td width="12%" bgcolor="#FFFFFF"><strong>Date</strong></td>
					  <td width="20%" bgcolor="#FFFFFF"></td>
				  </tr>
					<?php
					try {
					  // Connect and create the PDO object
					  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
					  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

						$query = "SELECT *  FROM `tbl_fs_themes` where bl_live = 1;";

					  	$result = $conn->prepare($query);
					  	$result->execute();

							  // Parse returned data
							  while($row = $result->fetch(PDO::FETCH_ASSOC)) {  ?>
								<tr>
								  <td><img src="../icons_folder/<?= $row['fs_theme_icon'];?>" style="margin-right:10px; max-width:40px;"><?= $row['fs_theme_title'];?></td>
								  <td><?= substr($row['fs_theme_narrative'],0,85);?>...</td>
								  <td><?= $row['confirmed_by'];?></td>
								  <td><?= date('j M y',strtotime($row['confirmed_date']));?></td>
								  <td><a href="#?id=<?=$row['id'];?>" class="edittheme btn" style="font-size:0.6em; font-weight:bold; margin-right:10px;">Edit Theme</a><a href="#" data-href="deletetheme.php?id=<?= $row['id'];?>" data-toggle="modal" data-target="#confirm-delete" class="btn btn-danger" style="font-size:0.6em; font-weight:bold;">Delete Theme</a></td>
							    </tr>
							<?php }

					  $conn = null;        // Disconnect

					}

					catch(PDOException $e) {
					  echo $e->getMessage();
					}
					?>
			      </tbody>
				</table>
		  </div>





		<div class="col-md-8 offset-2 mt-3 mb-3"><hr></div>

		<div id="themedetails" class="col-md-12 mt-5"></div>

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
          <h5 class="modal-title" id="deleteModal">Delete this Theme?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Continue" below if you are ready to<br>delete this Theme.</div>
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

		$(".addtheme").click(function(e){
          e.preventDefault();
		  $("#themedetails").load("add_theme.php");
		});

		$(".edittheme").click(function(e){
          e.preventDefault();
		  var theme_id = getParameterByName('id',$(this).attr('href'));
			console.log(theme_id);
		  $("#themedetails").load("edit_theme.php?id="+theme_id);
		});

		$('#confirm-delete').on('show.bs.modal', function(e) {
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
