<?php
session_start();
include("./includes/header.php");
include("./includes/database.php");
include("./includes/functions.php");

// Authentication check
if (!isset($_SESSION['loggedin'])) {
    header("Location: ./auth/login.php");
    exit();
}

if (isset($_POST['submit_vote'])) {
    if (submitVote($conn, $_SESSION['id'], $_POST['vote'])) {
        header("Location: dashboard.php");
    }
}

if (isset($_POST["change_vote"])) {
    if (changeVote($conn, $_SESSION['id'])) {
        header("Location: dashboard.php");
    }
}

if (isset($_POST["see_votes"])) {
}

$has_voted = checkUserVote($conn, $_SESSION['id']);
$poll_results = getPollResults($conn);
$user = getProfilePicture($conn, $_SESSION["id"]);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Dashboard</title>
</head>

<body>
    <h1 class="index">Hello <?php echo strtoupper($_SESSION['username']); ?></h1>
    <?php
    if ($user["profile_picture"]) {
        $web_path = str_replace("/var/www/html", "", $user["profile_picture"]);
        echo "<img class='profile-pic'  src='" . $web_path . "' alt='Profile Picture'>";
    }
    ?>

    <?php if (!$has_voted): ?>
        <form method="post">
            <h3>Drikkelse til morgenmad:</h3>
            <input type="radio" name="vote" value="milk"> Milk
            <input type="radio" name="vote" value="juice"> Juice
            <input type="radio" name="vote" value="water"> Water
            <input type="submit" name="submit_vote" value="Vote">
        </form>
    <?php else: ?>
        <form method="post">
            <input type="submit" name="change_vote" value="Change Vote">
        </form>
    <?php endif; ?>

    <div class="poll-results">
        <?php while ($row = $poll_results->fetch_assoc()): ?>
            <p><?php echo $row['vote'] . ": " . $row['count']; ?> votes</p>
            <form method="post">
                <input type="submit" name="see_votes" value="See voters">
            </form>
        <?php endwhile; ?>
    </div>

    <?php include("includes/footer.php"); ?>
</body>

</html>