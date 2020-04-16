<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8


     //    Get the products   ///

  $query = "SELECT DISTINCT isin_code FROM `tbl_fs_fund` where bl_live = 1;";

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $isincodes[] = $row['isin_code'];
  }

  $conn = null;        // Disconnect

}


catch(PDOException $e) {
  echo $e->getMessage();
}

$initialDate = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
?>
<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/header.php');
require_once('page-sections/header-elements.php');
?>

<div class="container">
    <div class="border-box main-content daily-data">
<a href="#" class="button button__raised button__inline">Add Fund</a>
<h1 class="heading heading__2">Daily & Historical Prices</h1>

<div class="prices-table">

<div class="prices-table__head">
    <div>
        <h3 class="heading heading__4">Fund Name</h3>
    </div>
    <div>
        <h3 class="heading heading__4">ISIN Code</h3>
    </div>
    <div>
        <h3 class="heading heading__4">Fund SEDOL</h3>
    </div>
    <div>
        <h3 class="heading heading__4">Benchmark</h3>
    </div>
    <div>
        <h3 class="heading heading__4">Current Price</h3>
        <div class="split">
            <div><h4 class="heading heading__4">Price</h4></div>
            <div><h4 class="heading heading__4">As At</h4></div>
        </div>
    </div>
    <div>
        <h3 class="heading heading__4">Add New Price</h3>
        <div class="split">
            <div><h4 class="heading heading__4">Price</h4></div>
            <div><h4 class="heading heading__4">Date</h4></div>
        </div>
    </div>
    <div></div>
</div>

<div class="recess-box">
    <?php
    try {
      // Connect and create the PDO object
      $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
      $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

      $codes = array();
         //    Get the funds   //

        foreach($isincodes as $code) {
            $query = "SELECT *  FROM `tbl_fs_fund` where isin_code LIKE '$code' AND bl_live = 1 ORDER BY correct_at DESC LIMIT 1;";

            $result = $conn->prepare($query);
            $result->execute();

              // Parse returned data
              while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $codes[] = $row['isin_code'];
                $as_at = date('j M y',strtotime($row['correct_at'])); ?>
    <form method="post" name="form<?=$row['isin_code'];?>" id="form<?=$row['isin_code'];?>">
        <div class="prices-table__account">
        <div>
            <h3 class="heading heading__4"><?= $row['fund_name'];?></h3>
        </div>
        <div>
            <h3 class="heading heading__4"><?= $row['isin_code'];?></h3>
        </div>
        <div>
            <h3 class="heading heading__4"><?= $row['fund_sedol'];?></h3>
        </div>
        <div>
            <h3 class="heading heading__4"><?= $row['benchmark'];?></h3>
        </div>
        <div>
            <div class="split">
                <div><h4 class="heading heading__4"><?= $row['current_price'];?></h4></div>
                <div><h4 class="heading heading__4"><?= $as_at;?></h4></div>
            </div>
        </div>
        <div>
            <div class="split">
                <div><input name="price<?=$row['isin_code'];?>" type="text" id="price<?=$row['isin_code'];?>" title="price" value="0.00" size="4"></div>
                <div><input name="pricedate<?=$row['isin_code'];?>" type="text" id="pricedate<?=$row['isin_code'];?>" title="pricedate" value="" size="6"></div>
            </div>
        </div>
        <div>
            <a href="#" class="button button__raised">Edit</a>
        </div>
    </div>
    <!--<table>
        <tr class="<?=$row['isin_code'];?>">
        <td align="center" colspan="10" id="daily_prices<?= $row['isin_code'];?>"></td>
        </tr>
        <tr class="<?=$row['isin_code'];?>"><td colspan="10" align="center"><!--  #Delete Fund    <a href="#" data-href="deletefund.php?ic=<?= $row['isin_code'];?>" data-toggle="modal" data-target="#confirm-delete" class="btn btn-danger" style="font-size:0.85em; font-weight:bold;">Delete Fund</a> --><!--</td></tr>
    </table>-->
    </form>
    <?php }
      }
    $conn = null;        // Disconnect

    }

    catch(PDOException $e) {
    echo $e->getMessage();
    }
    ?>

</div>




</div>
</div>
</div>

        <!--<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4 mb-5">
			<div id="funddetails" class="col-md-12"></div>

			<div class="table-responsive mt-5">
			  <table class="table table-sm table-striped">
			    <tbody>
					<tr>
					  <td colspan="2" rowspan="2" valign="middle" bgcolor="#FFFFFF"><strong>Fund Name</strong></td>
					  <td rowspan="2" align="center" valign="middle" bgcolor="#FFFFFF"><strong>ISIN Code</strong></td>
					  <td rowspan="2" align="center" valign="middle" bgcolor="#FFFFFF"><strong>Fund Sedol</strong></td>
					  <td rowspan="2" align="center" valign="middle" bgcolor="#FFFFFF"><strong>Benchmark</strong></td>
					  <td colspan="2" align="center" bgcolor="#FFFFFF" style="border-top:1px solid #666; border-left:1px solid #666; border-right:1px solid #666;"><strong>Current Price</strong></td>
					  <td colspan="2" align="center" bgcolor="#FFFFFF" style="border-top:1px solid #666; border-left:1px solid #666; border-right:1px solid #666;"><strong>Add Price</strong></td>
					  <td bgcolor="#FFFFFF">&nbsp;</td>
				  </tr>
					<tr>
					  <td align="center" bgcolor="#FFFFFF" style="border-bottom:1px solid #666; border-left:1px solid #666;"><strong>Price</strong></td>
					  <td align="center" bgcolor="#FFFFFF" style="border-bottom:1px solid #666; border-right:1px solid #666;"><strong>As At</strong></td>
					  <td align="center" bgcolor="#FFFFFF" style="border-bottom:1px solid #666; border-left:1px solid #666;"><strong>Price</strong></td>
					  <td align="center" bgcolor="#FFFFFF" style="border-bottom:1px solid #666; border-right:1px solid #666;"><strong>As At</strong></td>
					  <td bgcolor="#FFFFFF">&nbsp;</td>
					</tr>
					<?php
					try {
					  // Connect and create the PDO object
					  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
					  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

					  $codes = array();
						 //    Get the funds   //

						foreach($isincodes as $code) {
							$query = "SELECT *  FROM `tbl_fs_fund` where isin_code LIKE '$code' AND bl_live = 1 ORDER BY correct_at DESC LIMIT 1;";

					  		$result = $conn->prepare($query);
					  		$result->execute();

							  // Parse returned data
							  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
								$codes[] = $row['isin_code'];
								$as_at = date('j M y',strtotime($row['correct_at'])); ?>
				<form method="post" name="form<?=$row['isin_code'];?>" id="form<?=$row['isin_code'];?>">
								  <tr>
									  <td class="head<?=$row['isin_code'];?> normal"><?= $row['fund_name'];?></td>
									  <td class="head<?=$row['isin_code'];?> normal text-nowrap" align="center"><a href="#" class="toggler indicator" data-prod-name="<?=$row['isin_code'];?>"><i class="fas fa-caret-up arrow<?=$row['isin_code'];?>" style="font-size:2em;"></i></a></td>
									  <td class="head<?=$row['isin_code'];?> normal text-nowrap" align="center"><?= $row['isin_code'];?></td>
									  <td class="head<?=$row['isin_code'];?> normal text-nowrap" align="center"><?= $row['fund_sedol'];?></td>
									  <td class="head<?=$row['isin_code'];?> normal text-nowrap" align="center"><?= $row['benchmark'];?></td>
									  <td class="head<?=$row['isin_code'];?> normal text-nowrap" align="center"><?= $row['current_price'];?></td>
									  <td class="head<?=$row['isin_code'];?> normal text-nowrap" align="center"><?= $as_at;?></td>
									  <td class="head<?=$row['isin_code'];?> normal text-nowrap" align="center"><input name="price<?=$row['isin_code'];?>" type="text" id="price<?=$row['isin_code'];?>" title="price" value="0.00" size="4"></td>
									  <td class="head<?=$row['isin_code'];?> normal text-nowrap" align="center"><input name="pricedate<?=$row['isin_code'];?>" type="text" id="pricedate<?=$row['isin_code'];?>" title="pricedate" value="" size="6"></td>
									  <td class="head<?=$row['isin_code'];?> normal"><input type="submit" style="font-size:0.8em" class="btn btn-admin" value="Add Price"></td>
								  </tr>
								</form>
								  <tr class="<?=$row['isin_code'];?>" style="font-size:0.8em; background-color:white; font-weight:bold; display:none;">
									<td align="center" colspan="10" id="daily_prices<?= $row['isin_code'];?>"></td>
								  </tr>
					<tr class="<?=$row['isin_code'];?>" style="font-size:0.8em; background-color:white; font-weight:bold; display:none;"><td colspan="10" align="center"><!--  #Delete Fund    <a href="#" data-href="deletefund.php?ic=<?= $row['isin_code'];?>" data-toggle="modal" data-target="#confirm-delete" class="btn btn-danger" style="font-size:0.85em; font-weight:bold;">Delete Fund</a> --><!--</td></tr>
					  <?php }
						}
					  $conn = null;        // Disconnect

					}

					catch(PDOException $e) {
					  echo $e->getMessage();
					}
					?>
			      </tbody>
				</table>
		  </div>
      </main>-->
      </div>
    </div>

<?php require_once('page-sections/footer-elements.php');?>


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

<?php require_once('page-sections/footer-elements.php');
require_once('modals/delete.php');
require_once('modals/logout.php');
require_once(__ROOT__.'/global-scripts.php');?>

    <script>

		$(".toggler").click(function(e){
          e.preventDefault();
          $('.'+$(this).attr('data-prod-name')).toggle();
          $('.head'+$(this).attr('data-prod-name')).toggleClass( "highlight normal" );
          $('.arrow'+$(this).attr('data-prod-name'), this).toggleClass("fa-caret-up fa-caret-down");
    	});

		$(".addfund").click(function(e){
          e.preventDefault();
		  $("#funddetails").load("add_fund.php");
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

	<?php for($a=0;$a<count($codes);$a++){ ?>


		$('#pricedate<?=$codes[$a]?>').datepicker({  format: "yyyy-mm-dd" , todayHighlight: true });

		$("#form<?=$codes[$a]?>").submit(function(e) {
			e.preventDefault(); // avoid to execute the actual submit of the form.
			var form = $(this);
			$.ajax({
				   type: "POST",
				   url: 'addfundprice.php?ic=<?=$codes[$a]?>',
				   data: form.serialize(), // serializes the form's elements.
				   success: function(data){ $("#daily_prices<?=$codes[$a]?>").load("getrcalendarprices.php?dt=<?= $initialDate ;?>&ic=<?=$codes[$a]?>"); }
				 });
		});

		$("#daily_prices<?=$codes[$a]?>").load("getrcalendarprices.php?dt=<?= $initialDate ;?>&ic=<?=$codes[$a]?>");

		$(document).on('click', '.monthback<?=$codes[$a]?>', function(e) {
            e.preventDefault();
            var dt = getParameterByName('dt',$(this).attr('href'));
            $("#daily_prices<?=$codes[$a]?>").load("getrcalendarprices.php?dt="+dt+"-01&ic=<?=$codes[$a]?>");
        });

        $(document).on('click', '.monthnext<?=$codes[$a]?>', function(e) {
            e.preventDefault();
            var dt = getParameterByName('dt',$(this).attr('href'));
            $("#daily_prices<?=$codes[$a]?>").load("getrcalendarprices.php?dt="+dt+"-01&ic=<?=$codes[$a]?>");
        });
	<?php } ?>

    </script>
  </body>
</html>
