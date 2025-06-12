<!-- updateScore.php -->
<?php
require_once "config.php";

function changeScore($player_id, $score, $link) {

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

    $sql_update = "UPDATE Player SET score = ? WHERE player_id = ?";
    if ($stmt_update = mysqli_prepare($link, $sql_update)) {
        mysqli_stmt_bind_param($stmt_update, "is", $score, $player_id);
        if (mysqli_stmt_execute($stmt_update)) {
            echo "<div class='alert alert-success'>Score updated successfully for Player ID: $player_id</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to update score.</div>";
        }
        mysqli_stmt_close($stmt_update);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $player_id = trim($_POST["player_id"]);
    $score = trim($_POST["score"]);
    if (!empty($player_id) && is_numeric($score)) {
        changeScore($player_id, (int)$score, $link);
    } else {
        echo "<div class='alert alert-warning'>Please enter both Player ID and a valid Score.</div>";
    }
}

$sql = "SELECT 
            player_id AS 'Player ID',
            name AS 'Player Name',
            level,
            score AS 'Score'
        FROM 
            Player
        ORDER BY 
            player_id";

$result = mysqli_query($link, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Player Scores</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style>
        .wrapper { width: 80%; margin: 40px auto; }
        table { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Scores by Player</h2>
        <p>List of players and their scores:</p>

        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Player ID</th>
                        <th>Player Name</th>
                        <th>Level</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Player ID']); ?></td>
                            <td><?php echo htmlspecialchars($row['Player Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['level']); ?></td>
                            <td><?php echo htmlspecialchars($row['Score']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning">Score found for any player.</div>
        <?php endif;
        mysqli_free_result($result);
        ?>

        <hr>
        <h3>Change Player score</h3>
        <form method="post" class="form">
            <div class="form-group">
                <label for="player_id">Player ID</label>
                <input type="text" name="player_id" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="score">Score</label>
                <input type="number" name="score" class="form-control" required>
            </div>
            <input type="submit" class="btn btn-primary" value="Change score">
        </form>

        <br>
        <a href="index.php" class="btn btn-default">Return to Home</a>
    </div>
</body>
</html>

<?php mysqli_close($link); ?>
