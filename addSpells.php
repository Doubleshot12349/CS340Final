<?php
	session_start();
	ob_start();
	$spell_id = $_SESSION["spell_id"];
	$spellbook_id = $_SESSION["spellbook_id"];
	require_once "config.php";
?>


<?php 
$spellbook_id_err = $spell_id_err = $spellbook_id_err = "" ;
$SQL_err="";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
	$spellbook_id = trim($_POST["spellbook_id"]);
	if(empty($spellbook_id)){
		$spellbook_id_err = "Please enter a spellbook_id";     
	}

	$spell_id = trim($_POST["spell_id"]);
	if(empty($spell_id)){
		$spell_id_err = "Please enter a spell_id";     
	}

	if(empty($spell_id_err) && empty($spellbook_id_err) ){
		$sql = "INSERT INTO `contains` (`spellbook_id`, `spell_id`)  VALUES (?, ?)";

		if($stmt = mysqli_prepare($link, $sql)){
			mysqli_stmt_bind_param($stmt, 'ii', $param_spellbook_id, $param_spell_id);
		
			$param_spellbook_id = $spellbook_id;
			$param_spell_id = $spell_id;
	
			if(mysqli_stmt_execute($stmt)){
			//exit();
			} else{
				echo "Error";
				//exit();
				$SQL_err = mysqli_error($link);
			}
		}
		mysqli_stmt_close($stmt);
	}   
	mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company DB</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <div class="page-header">
                        <h3>Insert into a Spellbook </h3>
						<h4><?php echo $spellbook_id;?> spell_id = <?php echo $spell_id;?></h4>
                    </div>
				
<?php
	echo $SQL_err;		
	$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	if (!$conn) {
		die('Could not connect: ' . mysqli_error());
	}
	$sql = "SELECT spellbook_id, name FROM Spellbook";
	$result = mysqli_query($conn, $sql);
	if (!$result) {
		die("Query to show fields from table failed");
	}
	$num_row = mysqli_num_rows($result);
    //query2	
    $sql2 = "SELECT spell_id, name FROM Spell";
	$result2 = mysqli_query($conn, $sql2);
	if (!$result2) {
		die("Query to show fields from table failed");
	}
	$num_row2 = mysqli_num_rows($result2);
?>	

	<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
		<div class="form-group <?php echo (!empty($spell_id_err)) ? 'has-error' : ''; ?>">
            <label>Spellbook</label>
			<select name="spellbook_id" class="form-control">
			<?php

				for($i=0; $i<$num_row; $i++) {
					$spellbook_ids=mysqli_fetch_row($result);
					echo "<option value='$spellbook_ids[0]' >".$spellbook_ids[0]."  ".$spellbook_ids[1]."</option>";
				}
			?>
			</select>	
            <span class="help-block"><?php echo $spellbook_id_err;?></span>
		</div>
		<div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
			<label>Spell</label>
			<select name="spell_id" class="form-control">
			<?php
				for($i=0; $i<$num_row2; $i++) {
					$spell_ids=mysqli_fetch_row($result2);
					echo "<option value='$spell_ids[0]' >".$spell_ids[0]."  ".$spell_ids[1]."</option>";
				}
			?>
		</div>
		<div>
			<input type="submit" class="btn btn-success pull-left" value="Add Spell">	
			&nbsp;
			<a href="viewActiveSpells.php" class="btn btn-primary">View Active Spells</a>

		</div>
		<div>
			<a href="index.php" class="btn btn-default">Home</a>
		</div>

	</form>
<?php		
	mysqli_free_result($result);
	mysqli_close($conn);
?>
</body>

</html>

	