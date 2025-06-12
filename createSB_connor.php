<!-- createSB_connor.php -->
<!-- used over createSpellbook.php -->
<?php
require_once "config.php";

function createSpellbook($player_id, $spellbook_name, $link) {
    //Check if player exists
    $sql_check_player = "SELECT * FROM Player WHERE player_id = ?";
    if ($stmt = mysqli_prepare($link, $sql_check_player)) {
        mysqli_stmt_bind_param($stmt, "s", $player_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) !== 1) {
            echo "<div class='alert alert-danger'>Player ID not found.</div>";
            return;
        }
        mysqli_stmt_close($stmt);
    }

    //Check if spellbook name is unique for the player
    $sql_check_name = "SELECT * FROM Spellbook WHERE player_id = ? AND name = ?";
    if ($stmt = mysqli_prepare($link, $sql_check_name)) {
        mysqli_stmt_bind_param($stmt, "ss", $player_id, $spellbook_name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            echo "<div class='alert alert-warning'>Spellbook name already exists for this player.</div>";
            return;
        }
        mysqli_stmt_close($stmt);
    }

    //Get the next spellbook ID
    $sql_max_id = "SELECT MAX(spellbook_id) AS max_id FROM Spellbook";
    $result = mysqli_query($link, $sql_max_id);
    $new_id = 1;
    if ($row = mysqli_fetch_assoc($result)) {
        $new_id = $row["max_id"] + 1;
    }

    //Insert the new spellbook
    $sql_insert = "INSERT INTO Spellbook (spellbook_id, name, player_id) VALUES (?, ?, ?)";
    if ($stmt = mysqli_prepare($link, $sql_insert)) {
        mysqli_stmt_bind_param($stmt, "iss", $new_id, $spellbook_name, $player_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "<div class='alert alert-success'>Spellbook '$spellbook_name' created with ID $new_id.</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to create spellbook.</div>";
        }
        mysqli_stmt_close($stmt);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $player_id = trim($_POST["player_id"]);
    $spellbook_name = trim($_POST["spellbook_name"]);

    if (!empty($player_id) && !empty($spellbook_name)) {
        createSpellbook($player_id, $spellbook_name, $link);
    } else {
        echo "<div class='alert alert-warning'>Please fill in both fields.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Spellbook</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style>.wrapper { width: 500px; margin: 40px auto; }</style>
</head>
<body>
    <div class="wrapper">
        <h2>Create New Spellbook</h2>
        <p>Enter a player ID and unique spellbook name.</p>
        <form method="post">
            <div class="form-group">
                <label>Player ID</label>
                <input type="text" name="player_id" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Spellbook Name</label>
                <input type="text" name="spellbook_name" class="form-control" required>
            </div>
            <input type="submit" class="btn btn-primary" value="Create Spellbook">
            <a href="index.php" class="btn btn-default">Cancel</a>
        </form>
    </div>
</body>
</html>

<?php mysqli_close($link); ?>
