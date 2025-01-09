<?php

function validateUserName($username)
{
    $pattern = '/^[a-z]\w{2,23}[^_]$/i';
    if (preg_match($pattern, $username)) {
        return true;
    }
    return false;
}

function validateEmail($email)
{
    $pattern = '/^[a-zA-Z0-9._%+-]+@(virksomhed\.dk)$/';
    if (preg_match($pattern, $email)) {
        return true;
    } else {
        return false;
    }
}

function validatePassword($password)
{
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
    if (preg_match($pattern, $password)) {
        return true;
    } else {
        return false;
    }
}
