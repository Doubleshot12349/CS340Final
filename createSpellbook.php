<?php
	session_start();
	ob_start();
	$player_id = $_SESSION["player_id"];
	$name = $_SESSION["name"];

	require_once "config.php";
?>


<?php 
	$spellbook_id = "";
	$spellbook_id_err = $player_id_err = $name_err = "" ;
	$SQL_err="";

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$spellbook_id = trim($_POST["spellbook_id"]);
		if(empty($spellbook_id)){
			$spellbook_id_err = "Please select a project.";
		} 
    
		$name = trim($_POST["name"]);
		if(empty($name)){
			$name_err = "Please enter name";     
		}
	
		if(empty($player_id)){
			$player_id_err = "No player_id.";     
		}

		if(empty($player_id_err) && empty($name_err) && empty($spellbook_id_err) ){
			$sql = "INSERT INTO `Spellbook` (`spellbook_id`, `name`, `number_spells`, `player_id`)  VALUES (?, ?, ?)";

        	if($stmt = mysqli_prepare($link, $sql)){
				mysqli_stmt_bind_param($stmt, 'sii', $param_player_id, $param_spellbook_id, $param_name);
            
				$param_player_id = $player_id;
				$param_spellbook_id = $spellbook_id;
				$param_name = $name;
        
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
                        <h3>Add a Spellbook </h3>
						<h4><?php echo $name;?> player_id = <?php echo $player_id;?></h4>
                    </div>
				
<?php
	echo $SQL_err;		
	$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	if (!$conn) {
		die('Could not connect: ' . mysqli_error());
	}
	$sql = "SELECT name FROM Player";
	$result = mysqli_query($conn, $sql);
	if (!$result) {
		die("Query to show fields from table failed");
	}
	$num_row = mysqli_num_rows($result);	
?>	

	<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
		<div class="form-group <?php echo (!empty($player_id_err)) ? 'has-error' : ''; ?>">
            <label>Player Username</label>
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
			<label>Spellbook Name </label>
			<input type="text" name="name" class="form-control" value="">
			<span class="help-block"><?php echo $name_err;?></span>
		</div>
		<div>
			<input type="submit" class="btn btn-success pull-left" value="Add Project">	
			&nbsp;
			<a href="viewProjects.php" class="btn btn-primary">List Projects</a>

		</div>
	</form>
<?php		
	mysqli_free_result($result);
	mysqli_close($conn);
?>
</body>

</html>

	