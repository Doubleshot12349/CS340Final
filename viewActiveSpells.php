<?php
	session_start();
    // Include config file
    require_once "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Active Spells</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 650px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
	   <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">View Active Spells</h2>
						<a href="uL_with_pID.php" class="btn btn-success pull-right">Swap Spellbook</a>
                    </div>
<?php

    // Prepare a select statement
    $sql = "SELECT P.player_id AS 'Player ID', P.name AS 'Player Name',SpellB.name AS 'Spell Name'
        ,SpellB.spell_id,SpellB.spellbook_id
        FROM Player AS P 
            JOIN (SELECT S.name,S.spell_id,SB.spellbook_id FROM Spellbook AS SB 
            JOIN contains AS C ON SB.spellbook_id=C.spellbook_id
            JOIN Spell AS S ON C.spell_id=S.spell_id) AS SpellB
                ON SpellB.spellbook_id=P.loadout";

    // fails to prepare
    if($stmt = mysqli_prepare($link, $sql)){
        

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
			if(mysqli_num_rows($result) > 0){
				echo "<table class='table table-bordered table-striped'>";
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th>Player ID </th>";
                            echo "<th>Player Name </th>";
                            echo "<th>Spell Name </th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";							
				// output data of each row
                    while($row = mysqli_fetch_array($result)){
                        echo "<tr>";
                        echo "<td>" . $row[0] . "</td>";
                        echo "<td>" . $row[1] . "</td>";
                        echo "<td>" . $row[2] . "</td>";
						echo "<td>";
						  echo "<a href='addSpells.php?spell_id=". $row[2]."&spellbook_id=".$row[3] ."' title='Add Spell' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                          echo "<a href='removeSpells.php?spell_id=". $row[2]."&spellbook_id=".$row[3] ."' title='Remove Spell' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                        echo "</td>";
						echo "</tr>";
                    }
                    echo "</tbody>";                            
                echo "</table>";				
				mysqli_free_result($result);
			} else {
				echo "No Dependents. ";
			}
//				mysqli_free_result($result);
        } else{
			// URL doesn't contain valid id parameter. Redirect to error page
            header("location: error.php");
            exit();
        }
    }    
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);

?>					                 					
	<p><a href="index.php" class="btn btn-primary">Back</a></p>
    </div>
   </div>        
  </div>
</div>
</body>
</html>