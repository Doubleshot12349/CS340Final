<?php
	session_start();	
// Include config file
	require_once "config.php";
 
// Define variables and initialize with empty values
// Note: You can not update player_id 
$score = $level = $name = $loadout = "";
$score_err = $level_err = $name_err = $loadout_err = "" ;
// Form default values

if(isset($_GET["player_id"]) && !empty(trim($_GET["player_id"]))){
	$_SESSION["player_id"] = $_GET["player_id"];

    // Prepare a select statement
    $sql1 = "SELECT * FROM Player WHERE player_id = ?";
  
    if($stmt1 = mysqli_prepare($link, $sql1)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt1, "s", $param_player_id);      
        // Set parameters
       $param_player_id = trim($_GET["player_id"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt1)){
            $result1 = mysqli_stmt_get_result($stmt1);
			if(mysqli_num_rows($result1) > 0){

				$row = mysqli_fetch_array($result1);

                $name = $row['name'];
                $level = $row['level'];
                $score = $row['score'];
                $loadout = $row['loadout'];
			} else {
                header("location: error.php");
                exit();
            }
		} else {
            echo "Error fetching player details.";
        }
        mysqli_stmt_close($stmt1);
	}
}
 
// Post information about the Player when the form is submitted
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // the id is hidden and can not be changed
    $player_id = $_SESSION["player_id"];
    // Validate form data this is similar to the create Player file
    // Validate name
    $name = trim($_POST["name"]);
    $level = trim($_POST["level"]);
    $score = trim($_POST["score"]);
    $loadout = trim($_POST["loadout"]);

    if(empty($name)){
        $name_err = "Please enter a first name.";
    } elseif(!filter_var($name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid first name.";
    } 

    // Check input errors before inserting into database
    if(empty($name_err) && empty($level_err) && empty($score_err) && empty($loadout_err)){
        // Prepare an update statement
        $sql = "UPDATE Player SET name=?, level=?, score=?, loadout=? WHERE player_id=?";
    
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssdis", $name, $level, $score,$loadout, $player_id);
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "<center><h2>Error when updating</center></h2>";
            }
        }        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else {

    // Check existence of sID parameter before processing further
	// Form default values

	if(isset($_GET["player_id"]) && !empty(trim($_GET["player_id"]))){
		$_SESSION["player_id"] = $_GET["player_id"];

		// Prepare a select statement
		$sql1 = "SELECT * FROM Player WHERE player_id = ?";
  
		if($stmt1 = mysqli_prepare($link, $sql1)){
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt1, "s", $param_player_id);      
			// Set parameters
			$param_player_id = trim($_GET["player_id"]);

			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt1)){
				$result1 = mysqli_stmt_get_result($stmt1);
				if(mysqli_num_rows($result1) == 1){

					$row = mysqli_fetch_array($result1);

					$param_name = $name;
                    $param_level = $level;
                    $param_score = $score;
                    $param_loadout = $loadout;
                    $param_player_id = $player_id;
				} else{
					// URL doesn't contain valid id. Redirect to error page
					header("location: error.php");
					exit();
				}                
			} else{
				echo "Error in player_id while updating";
			}		
		}
			// Close statement
			mysqli_stmt_close($stmt1);
        
			// Close connection
			mysqli_close($link);
	}  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
	}	
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
                <div class="col-md-12">
                    <div class="page-header">
                        <h3>Update Record for player_id =  <?php echo $_GET["player_id"]; ?> </H3>
                    </div>
                    <p>Please edit the input values and submit to update.
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
						<div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>First Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($level_err)) ? 'has-error' : ''; ?>">
                            <label>level</label>
                            <input type="text" name="level" class="form-control" value="<?php echo $level; ?>">
                            <span class="help-block"><?php echo $level_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($score_err)) ? 'has-error' : ''; ?>">
                            <label>score</label>
                            <input type="text" name="score" class="form-control" value="<?php echo $score; ?>">
                            <span class="help-block"><?php echo $score_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($loadout_err)) ? 'has-error' : ''; ?>">
                            <label>Department Number</label>
                            <input type="number" min="1" max="20" name="loadout" class="form-control" value="<?php echo $loadout; ?>">
                            <span class="help-block"><?php echo $loadout_err;?></span>
                        </div>						
                        <input type="hidden" name="player_id" value="<?php echo $player_id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>