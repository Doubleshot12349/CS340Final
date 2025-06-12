<!-- uL_with_pID.php -->
<!-- used over updateLoadout.php -->
 <!-- Created by Group 5: Connor Sun and Brett Thompson -->
<?php
require_once "config.php";

function changeLoadout($player_id, $spellbook_id, $link) {
    //Check if spellbook exists
    $sql_check_spellbook = "SELECT * FROM Spellbook WHERE spellbook_id = ?";
    if ($stmt_spellbook = mysqli_prepare($link, $sql_check_spellbook)) {
        mysqli_stmt_bind_param($stmt_spellbook, "i", $spellbook_id);
        mysqli_stmt_execute($stmt_spellbook);
        $result = mysqli_stmt_get_result($stmt_spellbook);
        if (mysqli_num_rows($result) === 0) {
            echo "<div class='alert alert-danger'>Invalid Spellbook ID.</div>";
            return;
        }
        mysqli_stmt_close($stmt_spellbook);
    }

    //Check if player exists
    $sql_check_player = "SELECT * FROM Player WHERE player_id = ?";
    if ($stmt_check = mysqli_prepare($link, $sql_check_player)) {
        mysqli_stmt_bind_param($stmt_check, "s", $player_id);
        mysqli_stmt_execute($stmt_check);
        $result = mysqli_stmt_get_result($stmt_check);
        if (mysqli_num_rows($result) !== 1) {
            echo "<div class='alert alert-danger'>Player ID not found.</div>";
            return;
        }
        mysqli_stmt_close($stmt_check);
    }

    //Update loadout
    $sql_update = "UPDATE Player SET loadout = ? WHERE player_id = ?";
    if ($stmt_update = mysqli_prepare($link, $sql_update)) {
        mysqli_stmt_bind_param($stmt_update, "is", $spellbook_id, $player_id);
        if (mysqli_stmt_execute($stmt_update)) {
            echo "<div class='alert alert-success'>Loadout updated successfully for Player ID: $player_id</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to update loadout.</div>";
        }
        mysqli_stmt_close($stmt_update);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $player_id = trim($_POST["player_id"]);
    $spellbook_id = trim($_POST["spellbook_id"]);
    if (!empty($player_id) && is_numeric($spellbook_id)) {
        changeLoadout($player_id, (int)$spellbook_id, $link);
    } else {
        echo "<div class='alert alert-warning'>Please enter both Player ID and a valid Spellbook ID.</div>";
    }
}

$sql = "SELECT 
            P.player_id AS 'Player ID',
            P.name AS 'Player Name',
            SB.spellbook_id AS 'Spellbook ID',
            SB.name AS 'Spellbook Name',
            SB.number_spells AS 'Number of Spells'
        FROM 
            Player AS P
        JOIN 
            Spellbook AS SB ON P.player_id = SB.player_id
        ORDER BY 
            P.player_id, SB.spellbook_id";

$result = mysqli_query($link, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Player Spellbooks & Loadout Change</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style>
        .wrapper { width: 80%; margin: 40px auto; }
        table { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Spellbooks by Player</h2>
        <p>List of players and their spellbooks:</p>

        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Player ID</th>
                        <th>Player Name</th>
                        <th>Spellbook ID</th>
                        <th>Spellbook Name</th>
                        <th>Number of Spells</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Player ID']); ?></td>
                            <td><?php echo htmlspecialchars($row['Player Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['Spellbook ID']); ?></td>
                            <td><?php echo htmlspecialchars($row['Spellbook Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['Number of Spells']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning">No spellbooks found for any player.</div>
        <?php endif;
        mysqli_free_result($result);
        ?>

        <hr>
        <h3>Change Player Loadout</h3>
        <form method="post" class="form">
            <div class="form-group">
                <label for="player_id">Player ID</label>
                <input type="text" name="player_id" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="spellbook_id">Spellbook ID</label>
                <input type="number" name="spellbook_id" class="form-control" required>
            </div>
            <input type="submit" class="btn btn-primary" value="Change Loadout">
        </form>

        <br>
        <a href="index.php" class="btn btn-default">Return to Home</a>
    </div>
</body>
</html>

<?php mysqli_close($link); ?>
