<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
$asset_id = $_GET['id'];

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT *  FROM `tbl_fs_assets` where id = $asset_id;";

    $result = $conn->prepare($query); 
    $result->execute();

          // Parse returned data
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {  
			  $asset_name = $row['fs_asset_name'];
			  $asset_narrative = $row['fs_asset_narrative'];

			  
			  $row['fs_growth_steady'] == '0' ? $steady = '' : $steady = $row['fs_growth_steady'];
			  $row['fs_growth_sensible'] == '0' ? $sensible = '' : $sensible = $row['fs_growth_sensible'];
			  $row['fs_growth_serious'] == '0' ? $serious = '' : $serious = $row['fs_growth_serious'];
			  
			  
			  $confirmed_by = $row['confirmed_by'];
			  $confirmed_date = $row['confirmed_date'];
			  $cat_id = $row['cat_id'];
		  }

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}


try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT *  FROM `tbl_fs_categories` where bl_live = 1;";

    $result = $conn->prepare($query); 
    $result->execute();

          // Parse returned data
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {  
			  $cats[] = $row;
		  }
	
	//    Get the categories associated with this asset   //
	$query = "SELECT *  FROM `tbl_fs_asset_cats` where fs_asset_id = $asset_id;";

    $result = $conn->prepare($query); 
    $result->execute();

          // Parse returned data
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {  
			  $catArray[] = $row['fs_cat_id'];
		  }

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}


?>


        
<form action="editasset.php?id=<?=$asset_id;?>" method="post" id="editasset" name="editasset">
			<div id="theme_details" class="col-md-6" style="float:left;">		
					<h4>Details</h4>
					<p>Asset Name<br>
					<input type="text" id="asset_name" name="asset_name" value="<?= $asset_name;?>"></p>
					<p>Narrative<br>
			  <textarea name="asset_narrative" style="width:90%; min-height:240px;" id="asset_narrative"><?= $asset_narrative;?></textarea></p>
				<h5>Growth</h5>
				<div class="col-md-4" style="float:left;">
				<p>Steady<br>
					<input type="text" name="growth_steady" id="growth_steady" class="calculator-input" onkeypress="return event.charCode >= 46 && event.charCode <= 57" size="5" value="<?= $steady;?>"></p>
				</div>
				<div class="col-md-4" style="float:left;">
				<p>Sensible<br>
					<input type="text" name="growth_sensible" id="growth_sensible" class="calculator-input" onkeypress="return event.charCode >= 46 && event.charCode <= 57" size="5" value="<?= $sensible;?>"></p>
				</div>
				<div class="col-md-4" style="float:left;">
				<p>Serious<br>
					<input type="text" name="growth_serious" id="growth_serious" class="calculator-input" onkeypress="return event.charCode >= 46 && event.charCode <= 57" size="5" value="<?= $serious;?>"></p>
				</div>
	
			</div>
	
			<div id="asset_categories" class="col-md-3" style="float:left;"><h4>Categories</h4><!--<a href="#" class="addasset"><i data-feather="plus-square"></i> Edit Categories</a>-->
				<?php $idString = ''; 
				for($a=0;$a<count($cats);$a++){ 
					$idString .= $cats[$a]['id'].'|';
					$cats[$a]['id']== $cat_id ? $thisCheck = 'checked = "checked"' : $thisCheck = ''; 
				
				?>
					<label><input type="radio" name="cat" value="<?=$cats[$a]['id'];?>" id="cat" <?=$thisCheck;?>>  <?=$cats[$a]['cat_name'];?></label><a href="#" data-href="deletecat.php?id=<?=$cats[$a]['id'];?>" data-toggle="modal" data-target="#confirm-catdelete" class="delcat"><img src="images/trash-2.svg" width="15"></a><br>
				<?php } ?>
				<p>Add Category<br>
				<input type="text" id="cat_new" name="cat_new"><input type="hidden" id="cat_ids" name="cat_ids" value="<?=substr($idString, 0, -1);?>"></p>
			</div>
	
	
			<div id="fund_actions" class="col-md-3" style="float:left;">
				<h5>Asset Actions</h5>
				<p>Last edit by <?= $confirmed_by;?></p>
				<p>Edited On <?= date('j M y',strtotime($confirmed_date));?></p>
				<input type="submit" class="btn btn-grey" value="Edit Asset">
			</div>
	
</form>			
		<div class="col-md-8 offset-2 mt-3 mb-3"><hr></div>

    <script>

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
