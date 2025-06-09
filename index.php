<?php
	session_start();
	//$currentpage="View Employees"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company DB</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
	<style type="text/css">
        .wrapper{
            width: 70%;
            margin:0 auto;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
        .navbar {
            border-radius: 0;
            margin-bottom: 30px;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar-inverse .navbar-brand {
            color: #fff;
            font-weight: bold;
        }

        .navbar-inverse .navbar-nav > li > a {
            color: #ccc;
            transition: color 0.3s ease;
        }
        .navbar-nav > li > a {
            padding: 14px 20px;
            font-size: 16px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
		 $('.selectpicker').selectpicker();
    </script>
</head>

<body>

    <?php
        // Include config file
        require_once "config.php";
        //		include "header.php";

        $sql = "SELECT player_id, name, score FROM Player ORDER BY score DESC LIMIT 10";
        $result = mysqli_query($link, $sql);

    ?>

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
		    <div class="page-header clearfix">
                <h1>Gladiator Mages</h1>
                <p>This UI allows you to perform the following CRUD operations:</p>
                <ol>
                    <li> - Create spellbooks, create relations between existing spells and spellbooks</li>
                    <li> - Read all spells in a player's equipped loadout/spellbook, or specific spells of a specific element</li>
                    <li> - Update a player's loadout</li>
                    <li> - Delete a player along with their spellbooks</li>
                </ol>

                <nav class="navbar navbar-inverse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="index.php">Home</a></li>
                        <!-- <li><a href="createSpellbook.php">Create Spellbook</a></li> -->
                        <li class="active"><a href="createSB_connor.php">Create Spellbook</a></li>
                        <li class="active"><a href="viewActiveSpells.php">View Active Spells</a></li>
                        <li class="active"><a href="updateScore.php">Update Score</a></li>
                        <li class="active"><a href="uL_with_pID.php">Update Loadout</a></li>
                        <li class="active"><a href="deleteUser.php">Delete Player</a></li>
                        <!-- <li class="active"><a href="updateLoadout.php">Update Loadout</a></li> -->
                        <!-- Add more pages as needed -->
                    </ul>
                </nav>

                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <h2>Leaderboard</h2>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Player Name</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $rank = 1; ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $rank++; ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['score']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">No players found in the leaderboard.</div>
                <?php endif; ?>


		       
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
					
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>

</body>
</html>
