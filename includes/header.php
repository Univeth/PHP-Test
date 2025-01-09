<?php session_start(); ?>

<div class="header">
    <a href="../index.php">
        <img id="virksomhed-header-logo" src="../assets/images/virksomhed.png" alt="">
    </a>

    <a href="../index.php">Home</a>

    <?php if (!isset($_SESSION['loggedin'])) { ?>
        <a href="../auth/login.php">Login</a>
        <a href="../auth/register.php">Register</a>
    <?php } ?>

    <?php if (isset($_SESSION['loggedin'])) { ?>
        <a href="../dashboard.php">Dashboard</a>
        <a href="../auth/userpage.php">Edit—<?php echo $_SESSION['username']; ?></a>
        <a href="../auth/logout.php">Logout</a>
    <?php } ?>
</div>