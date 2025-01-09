<?php

include("../includes/header.php");
include("../includes/database.php");
include("../includes/functions.php");

session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ./auth/login.php");
    exit();
}

$user = getProfilePicture($conn, $_SESSION["id"]);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Settings</title>
</head>

<body>

    <?php
    if ($user["profile_picture"]) {
        $web_path = str_replace("/var/www/html", "", $user["profile_picture"]);
        echo "<img class='profile-pic'  src='" . $web_path . "' alt='Profile Picture'>";
    }
    ?>

    <h1 class="index">Tilføj profilbilled:</h1>
    <form method="post" action="userpage.php" enctype="multipart/form-data">
        <input type="file" name="profile_picture" placeholder="Profilbilled">
        <input type="submit" name="add_picture" value="Tilføj">
    </form>

    <h1 class="index">Ændre informationer:</h1>

    <form method="post" action="userpage.php">
        <input type="text" name="username" placeholder="Skift brugernavn">
        <input type="submit" name="change_name" value="Ændre">
    </form>

    <form method="post" action="userpage.php">
        <input type="email" name="email" placeholder="Skift email">
        <input type="submit" name="change_email" value="Ændre">
    </form>

    <form method="post" action="userpage.php">
        <input type="password" name="password" placeholder="Skift adgangskode">
        <input type="submit" name="change_pass" value="Ændre">
    </form>
</body>

</html>

<?php

/// https://stackoverflow.com/questions/38509334/full-secure-image-upload-script

if (isset($_POST["add_picture"])) {
    $target_dir = "/var/www/html/auth/uploads/";
    $imageFileType = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . $_SESSION["id"] . "_profilepic." . $imageFileType;

    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            updateProfilePicture($conn, $target_file, $_SESSION["id"]);
            echo "Profile picture updated";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }
}

if (isset($_POST["change_name"])) {
    if (empty($_POST['username'])) {
        echo "All fields are required";
        exit();
    }

    $new_username = trim($_POST['username']);
    if (!validateUserName($new_username)) {
        echo "Invalid username format.";
        exit();
    }

    $result = updateUsername($conn, $new_username, $_SESSION['id']);

    if ($result === true) {
        $_SESSION["username"] = $new_username;
        header("Location: userpage.php");
    } else {
        echo $result;
    }
    mysqli_close($conn);
}

if (isset($_POST["change_email"])) {
    if (empty($_POST['email'])) {
        echo "All fields are required";
        exit();
    }

    $new_email = trim($_POST['email']);
    if (!validateEmail($new_email)) {
        echo "Invalid email format.";
        exit();
    }

    $result = updateEmail($conn, $new_email, $_SESSION['id']);

    if ($result === true) {
        $_SESSION["email"] = $new_email;
        header("Location: userpage.php");
    } else {
        echo $result;
    }
    mysqli_close($conn);
}

if (isset($_POST["change_pass"])) {
    if (empty($_POST['password'])) {
        echo "All fields are required";
        exit();
    }

    $new_password = trim($_POST['password']);
    if (!validatePassword($new_password)) {
        echo "Invalid password format.";
        exit();
    }

    $result = updatePassword($conn, $new_password, $_SESSION['id']);

    if ($result === true) {
        $_SESSION["password"] = $new_password;
        header("Location: userpage.php");
    } else {
        echo $result;
    }
    mysqli_close($conn);
}

?>


<?php include("../includes/footer.php"); ?>