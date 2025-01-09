<?php
$db_server = "xxxx";
$db_user = "yyyy";
$db_pass = "aaaa";
$db_name = "bbbb";

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

// AUTHENTICATION FUNCTIONS

function login($conn, $username)
{
    try {
        $login_stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $login_stmt->bind_param("s", $username);
        $login_stmt->execute();
        $result = $login_stmt->get_result();
        return $result->fetch_assoc();
    } catch (mysqli_sql_exception $e) {
        return false;
    } finally {
        $login_stmt->close();
    }
}

function register($conn, $username, $email, $password)
{
    try {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $register_stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $register_stmt->bind_param("sss", $username, $email, $hashed_password);
        $register_stmt->execute();
        return true;
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), "Duplicate entry") !== false) {
            if (strpos($e->getMessage(), "@")) {
                return "Email already registered";
            }
            return "Username already taken";
        }
        return "Something went wrong";
    } finally {
        $register_stmt->close();
    }
}

function updateProfilePicture($conn, $target_file, $user_id)
{
    try {
        $update_profilepic_stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
        $update_profilepic_stmt->bind_param("si", $target_file, $user_id);
        $update_profilepic_stmt->execute();
        return true;
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), "Duplicate entry") !== false) {
            return "Duplicate";
        }
        return "Error updating profile picture";
    } finally {
        $update_profilepic_stmt->close();
    }
}

function getProfilePicture($conn, $user_id)
{
    $stmt = $conn->prepare("SELECT profile_picture FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    return $user;
}

function updateUsername($conn, $new_username, $user_id)
{
    try {
        $update_name_stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
        $update_name_stmt->bind_param("si", $new_username, $user_id);
        $update_name_stmt->execute();
        return true;
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), "Duplicate entry") !== false) {
            return "Username already taken";
        }
        return "Error updating username";
    } finally {
        $update_name_stmt->close();
    }
}

function updateEmail($conn, $new_email, $user_id)
{
    try {
        $update_name_stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
        $update_name_stmt->bind_param("si", $new_email, $user_id);
        $update_name_stmt->execute();
        return true;
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), "Duplicate entry") !== false) {
            return "Email already taken";
        }
        return "Error updating email";
    } finally {
        $update_name_stmt->close();
    }
}

function updatePassword($conn, $new_password, $user_id)
{
    try {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_name_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update_name_stmt->bind_param("si", $hashed_password, $user_id);
        $update_name_stmt->execute();
        return true;
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), "Duplicate entry") !== false) {
            return "Something went wrong";
        }
        return "Error updating password";
    } finally {
        $update_name_stmt->close();
    }
}

// AUTHENTICATION FUNCTIONS


// DASHBOARD VOTING FUNCTIONS

function checkUserVote($conn, $user_id)
{
    $check_vote_stmt = $conn->prepare("SELECT user_id FROM polls WHERE user_id = ?");
    $check_vote_stmt->bind_param("i", $user_id);
    $check_vote_stmt->execute();
    return $check_vote_stmt->get_result()->num_rows > 0;
}

function submitVote($conn, $user_id, $vote)
{
    try {
        $vote_stmt = $conn->prepare("INSERT INTO polls (user_id, vote) VALUES (?, ?)");
        $vote_stmt->bind_param("is", $user_id, $vote);
        $vote_stmt->execute();
        return true;
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

function changeVote($conn, $user_id)
{
    try {
        $vote_stmt = $conn->prepare("DELETE FROM polls WHERE user_id = ?");
        $vote_stmt->bind_param("i", $user_id);
        $vote_stmt->execute();
        return true;
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

function seeVotes($conn)
{
    return $conn->query("SELECT ");
}



function getPollResults($conn)
{
    return $conn->query("SELECT vote, COUNT(*) as count FROM polls GROUP BY vote");
}

// DASHBOARD VOTING FUNCTIONS