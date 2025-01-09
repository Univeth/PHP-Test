<?php

session_start();

include("../includes/database.php");
include("../includes/header.php");
include("../includes/functions.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Login</title>
</head>

<body>
    <h1 class="index">Login</h1>
    <form method="post" action="login.php">
        <input type="text" name="username" placeholder="Brugernavn">
        <input type="password" name="password" placeholder="Adgangskode">
        <input type="submit" name="login" value="Login">
    </form>
</body>

</html>

<?php

// Login Form Logik
if (isset($_POST["login"])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        echo "All fields are required";
        exit();
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!validateUserName($username) || !validatePassword($password)) {
        echo "Invalid or Wrong username or password.";
        exit();
    }

    try {
        $user = login($conn, $username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $username;
            header("Location: ../dashboard.php");
            exit();
        } else {
            echo "Wrong username or password";
        }
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        mysqli_close($conn);
    }
}

?>


<?php include("../includes/footer.php"); ?>