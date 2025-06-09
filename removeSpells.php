<?php
	session_start();
	if(isset($_GET["spell_id"]) && !empty(trim($_GET["spellbook_id"]))){
		$_SESSION["spell_id"] = $_GET["spell_id"];
		$spellbook_id = $_GET["spellbook_id"];
	}

    require_once "config.php";
	// Remove a spell from a spellbook after confirmation
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if(isset($_SESSION["spell_id"]) && !empty($_SESSION["spell_id"])){ 
			$Espell_id = $_SESSION['spell_id'];
			$spellbook_id = $_SESSION['spellbook_id'];

			
			// Prepare a delete statement
			$sql = "DELETE FROM contains WHERE spell_id = ? 
						AND spellbook_id = ?";
   
			if($stmt = mysqli_prepare($link, $sql)){
			// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "ss", $param_spell_id, $param_spellbook_id);
 
				// Set parameters
				$param_spell_id = $spell_id;
				$param_spellbook_id = $spellbook_id;
				//echo $Espell_id;
				//echo $spellbook_id;

				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					// Records deleted successfully. Redirect to landing page
					header("location: index.php");
					exit();
				} else{
					echo "Error removing the spell";
				}
			}
		}
		// Close statement
		mysqli_stmt_close($stmt);
    
		// Close connection
		mysqli_close($link);
	} else{
		// Check existence of id parameter
		if(empty(trim($_GET["spellbook_id"]))){
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
    <title>Spell Removal</title>
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
                        <h1>Remove Spell</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="spell_id" value="<?php echo ($_SESSION["spell_id"]); ?>"/>
                            <p>Are you sure you want to remove the spell from this spellbook?</p><br>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="index.php" class="btn btn-default">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>