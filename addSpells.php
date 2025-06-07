<?php
	session_start();
	ob_start();
	$spell_id = $_SESSION["spell_id"];
	$spellbook_id = $_SESSION["spellbook_id"];
	// Include config file
	require_once "config.php";

?>


<?php 
	// Define variables and initialize with empty values
	$spellbook_id_err = $spell_id_err = $spellbook_id_err = "" ;
	$SQL_err="";
 
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		// Validate Project number
		$spellbook_id = trim($_POST["spellbook_id"]);
		if(empty($spellbook_id)){
			$spellbook_id_err = "Please select a spellbook.";
		} 
    
		// Validate spellbook_id
		$spellbook_id = trim($_POST["spellbook_id"]);
		if(empty($spellbook_id)){
			$spellbook_id_err = "Please enter spellbook_id";     
		}
	
		// Validate the spell_id
		if(empty($spell_id)){
			$spell_id_err = "No spell_id.";     
		}


    // Check input errors before inserting in database
		if(empty($spell_id_err) && empty($spellbook_id_err) ){
        // Prepare an insert statement
			$sql = "INSERT INTO `contains` (`spellbook_id`, `spell_id`)  VALUES (?, ?)";


        	if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, 'sii', $param_spell_id, $param_spellbook_id, $param_spellbook_id);
            
				// Set parameters
				$param_spell_id = $spell_id;
				$param_spellbook_id = $spellbook_id;
        
            // Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
               // Records created successfully. Redirect to landing page
				//    header("location: index.php");
				//	exit();
				} else{
					// Error
					echo "Error";
					//exit();
					$SQL_err = mysqli_error($link);
				}
			}
         
        // Close statement
        mysqli_stmt_close($stmt);
		
	}   
		// Close connection
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
	$sql = "SELECT name FROM Spellbook";
	$result = mysqli_query($conn, $sql);
	if (!$result) {
		die("Query to show fields from table failed");
	}
	$num_row = mysqli_num_rows($result);
    //query2	
    $sql2 = "SELECT name FROM Spell";
	$result2 = mysqli_query($conn, $sql2);
	if (!$result) {
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

	