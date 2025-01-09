<?php

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
    <title>Register</title>
</head>

<body>
    <h1 class="index">Register</h1>
    <form method="post" action="register.php">
        <input type="text" name="username" placeholder="Brugernavn">
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Adgangskode">
        <input type="submit" name="register" value="Register">
    </form>
</body>

</html>

<?php

if (isset($_POST["register"])) {
    if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
        echo "All fields are required";
        exit();
    }

    $username = trim($_POST['username']);
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!validateUserName($username) || !validateEmail($email) || !validatePassword($password)) {
        echo "Invalid input format";
        exit();
    }

    $result = register($conn, $username, $email, $password);
    if ($result === true) {
        header("Location: ./login.php");
    } else {
        echo $result;
    }
    mysqli_close($conn);
}

?>


<?php include("../includes/footer.php"); ?>