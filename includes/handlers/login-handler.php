<?php
if (isset($_POST['loginButton'])) {
    $username = $_POST['loginUsername'];
    $password = $_POST['loginPassword'];

    $result = $account->login($username, $password);
    if ($result) {
        $user = $account->findUserByUsername($username);
        $_SESSION['userLoggedIn'] = $user['id'];
        $_SESSION['usernameLoggedIn'] = $user['username'];
        $_SESSION['userRoleLoggedIn'] = $user['userRole'];
        header("Location: dashboard.php");
    }
}
?>