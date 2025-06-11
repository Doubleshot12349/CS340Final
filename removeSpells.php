<?php
session_start();
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["spell_id"]) && isset($_POST["spellbook_id"])) {
        $spell_id = $_POST["spell_id"];
        $spellbook_id = $_POST["spellbook_id"];

        $sql = "DELETE FROM contains WHERE spell_id = ? AND spellbook_id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ii", $param_spell_id, $param_spellbook_id);

            $param_spell_id = $spell_id;
            $param_spellbook_id = $spellbook_id;

            if (mysqli_stmt_execute($stmt)) {
                header("location: index.php");
                exit();
            } else {
                echo "Error removing the spell.";
            }

            mysqli_stmt_close($stmt);
        }
        mysqli_close($link);
    }
} else {
    if (empty($_GET["spell_id"]) || empty($_GET["spellbook_id"])) {
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
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
						<input type="hidden" name="spell_id" value="<?php echo htmlspecialchars($_GET['spell_id']); ?>">
						<input type="hidden" name="spellbook_id" value="<?php echo htmlspecialchars($_GET['spellbook_id']); ?>">
						<div class="alert alert-danger fade in">
							<p>Are you sure you want to remove the spell from this spellbook?</p><br>
							<input type="submit" value="Yes" class="btn btn-danger">
							<a href="index.php" class="btn btn-default">No</a>
						</div>
					</form>

                </div>
            </div>        
        </div>
    </div>
</body>
</html>