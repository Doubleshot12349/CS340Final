<!-- deleteUser.php -->
<?php
session_start();
// ob_start();

// Include config file
require_once "config.php";

$player_id = "";
$message = "";
$message_class = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Check that player_id is not empty
    if (empty(trim($_POST["player_id"]))) {
        $message = "Please enter a Player ID.";
        $message_class = "alert-warning";
    } else {
        $player_id = trim($_POST["player_id"]);
        $sql_check = "SELECT player_id FROM Player WHERE player_id = ?";
        if ($stmt_check = mysqli_prepare($link, $sql_check)) {
            mysqli_stmt_bind_param($stmt_check, "s", $player_id);
            mysqli_stmt_execute($stmt_check);
            $result = mysqli_stmt_get_result($stmt_check);

            //If there's a valid player_id, proceed to delete
            if ($result && mysqli_num_rows($result) === 1) {
                $sql_delete = "DELETE FROM Player WHERE player_id = ?";
                if ($stmt_delete = mysqli_prepare($link, $sql_delete)) {
                    mysqli_stmt_bind_param($stmt_delete, "s", $player_id);
                    
                    //Delete the player
                    if (mysqli_stmt_execute($stmt_delete)) {
                        $message = "Player with ID <strong>" . htmlspecialchars($player_id) . "</strong> has been deleted.";
                        $message_class = "alert-success";
                    } else {
                        $message = "Error deleting the player: " . mysqli_error($link);
                        $message_class = "alert-danger";
                    }
                mysqli_stmt_close($stmt_delete);
                } else {
                    $message = "Failed to prepare delete statement.";
                    $message_class = "alert-danger";
                }
            } else {
                $message = "Player ID not found.";
                $message_class = "alert-warning";
            }
            mysqli_stmt_close($stmt_check);
        } else {
            $message = "Failed to prepare check statement.";
            $message_class = "alert-danger";
        }
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Delete Gladiator Mage Account</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css" />
    <style>
        .wrapper { width: 400px; margin: 40px auto; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Delete Gladiator Mage Account</h2>
        <p>Enter the Player ID to delete their account.</p>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo $message_class; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="player_id">Player ID</label>
                <input type="text" name="player_id" id="player_id" class="form-control" required />
            </div>
            <input type="submit" class="btn btn-danger" value="Delete Player" />
            <a href="index.php" class="btn btn-default">Cancel</a>
        </form>
    </div>
</body>
</html>
