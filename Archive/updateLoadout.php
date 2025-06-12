<?php
session_start();
require_once "config.php";

function changeLoadout($player_id, $spellbook_id, $link) {
    $sql_select_player = "SELECT * FROM Player WHERE player_id = ?";
    if ($stmt_select_player = mysqli_prepare($link, $sql_select_player)) {
        mysqli_stmt_bind_param($stmt_select_player, "s", $player_id);
        if (mysqli_stmt_execute($stmt_select_player)) {
            $result_player = mysqli_stmt_get_result($stmt_select_player);
            if (mysqli_num_rows($result_player) == 1) {
                $sql_update_loadout = "UPDATE Player SET loadout = ? WHERE player_id = ?";
                if ($stmt_update_loadout = mysqli_prepare($link, $sql_update_loadout)) {
                    mysqli_stmt_bind_param($stmt_update_loadout, "ss", $spellbook_id, $player_id);
                    if (mysqli_stmt_execute($stmt_update_loadout)) {
                        echo "Loadout updated successfully.";
                    } else {
                        echo "Error updating loadout.";
                    }
                    mysqli_stmt_close($stmt_update_loadout);
                } else {
                    echo "Failed to prepare update statement.";
                }
            } else {
                echo "Player not found.";
            }
        } else {
            echo "Error executing select statement.";
        }
        mysqli_stmt_close($stmt_select_player);
    } else {
        echo "Failed to prepare select statement.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    changeLoadout($player_id, $spellbook_id, $link);
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Player Loadout</title>
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
                        <h3>Change Player Loadout</h3>
                    </div>
                    <p>Please enter the Spellbook ID to update the player's loadout.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Spellbook ID</label>
                            <input type="number" min="1" name="spellbook_id" class="form-control" required>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Change Loadout">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
