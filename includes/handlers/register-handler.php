<?php

function sanitizeFormString($inputText)
{
    $inputText = strip_tags($inputText);
    $inputText = str_replace(" ", "", $inputText);
    return $inputText;
}

function sanitizeFormName($inputText)
{
    $inputText = strip_tags($inputText);
    $inputText = str_replace(" ", "", $inputText);
    $inputText = ucfirst(strtolower($inputText));
    return $inputText;
}

if (isset($_POST['registerButton'])) {
    $username = sanitizeFormString($_POST['username']);
    $firstName = sanitizeFormName($_POST['firstName']);
    $lastName = sanitizeFormName($_POST['lastName']);
    $email = sanitizeFormString($_POST['email']);
    $email2 = sanitizeFormString($_POST['email2']);
    $password = sanitizeFormString($_POST['password']);
    $password2 = sanitizeFormString($_POST['password2']);

    $wasSuccessful = $account->register($username, $firstName, $lastName, $email, $email2, $password, $password2);
    echo $wasSuccessful;
    if ($wasSuccessful == true) {
        $_SESSION['userLoggedIn'] = $username;
        header("Location: dashboard.php");
    }
}
