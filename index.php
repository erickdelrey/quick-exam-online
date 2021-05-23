<?php
include("includes/config.php");
include("includes/classes/Account.php");
include("includes/classes/Constants.php");

$account = new Account($con);

include("includes/handlers/register-handler.php");
include("includes/handlers/login-handler.php");

function getInputValue($name)
{
    if (isset($_POST[$name])) {
        echo $_POST[$name];
    }
}
?>

<html>

<head>
    <title>Welcome to Quick Exam Online!</title>
    <link rel="icon" href="assets/images/logo-icon.png" sizes="32x32" type="image/png">
    <?php include("includes/roboto.php") ?>
    <?php include("includes/bootstrap.php") ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/register.css" />
    <script src="assets/js/register.js"></script>
</head>

<body>
    <?php

    if (isset($_POST['registerButton'])) {
        echo '<script>
            $(document).ready(function() {
                $("#loginForm").hide();
                $("#registerForm").show();
            });
        </script>';
    } else {
        echo '<script>
            $(document).ready(function() {
                $("#loginForm").show();
                $("#registerForm").hide();
            });
        </script>';
    }

    ?>
    <div id="background">
        <div id="loginContainer">
            <div id="inputContainer">
                <form id="loginForm" method="POST" action="index.php">
                    <h2>Login to your account</h2>
                    <p>
                        <?php echo $account->getError(Constants::$LOGIN_FAIL) ?>
                        <label for="loginUsername">Username</label>
                        <input id="loginUsername" name="loginUsername" type="text" placeholder="e.g. juan.delacruz" required required value="<?php getInputValue('loginUsername') ?>"></p>
                    <p>
                        <label for="loginPassword">Password</label>
                        <input id="loginPassword" name="loginPassword" type="password" placeholder="Your password" required>
                    </p>
                    <button type="submit" name="loginButton">LOG IN</button>
                    <div class="hasAccountText">
                        <span id="hideLogin">Don't have any account yet? Signup here.</span>
                    </div>
                </form>

                <form id="registerForm" method="POST" action="index.php">
                    <h2>Create your free account</h2>
                    <p>
                        <?php echo $account->getError(Constants::$USERNAME_INVALID_LENGTH) ?>
                        <?php echo $account->getError(Constants::$USERNAME_TAKEN) ?>
                        <label for="username">Username</label>
                        <input id="username" name="username" type="text" placeholder="e.g. juan.delacruz" required value="<?php getInputValue('username') ?>">
                    </p>
                    <p>
                        <?php echo $account->getError(Constants::$FIRSTNAME_INVALID_LENGTH) ?>
                        <label for="firstName">First Name</label>
                        <input id="firstName" name="firstName" type="text" placeholder="e.g. Juan" required value="<?php getInputValue('firstName') ?>">
                    </p>
                    <p>
                        <?php echo $account->getError(Constants::$LASTNAME_INVALID_LENGTH) ?>
                        <label for="lastName">Last Name</label>
                        <input id="lastName" name="lastName" type="text" placeholder="e.g. dela Cruz" required value="<?php getInputValue('lastName') ?>">
                    </p>
                    <p>
                        <?php echo $account->getError(Constants::$EMAIL_INVALID) ?>
                        <?php echo $account->getError(Constants::$EMAIL_DONT_MATCH) ?>
                        <?php echo $account->getError(Constants::$EMAIL_TAKEN) ?>
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" placeholder="e.g. juan.delacruz@gmail.com" required value="<?php getInputValue('email') ?>">
                    </p>
                    <p>
                        <label for="email2">Confirm email</label>
                        <input id="email2" name="email2" type="email" placeholder="e.g. juan.delacruz@gmail.com" required required value="<?php getInputValue('email2') ?>">
                    </p>
                    <p>
                        <?php echo $account->getError(Constants::$PASSWORD_INVALID_LENGTH) ?>
                        <?php echo $account->getError(Constants::$PASSWORD_NOT_ALPHANUMERIC) ?>
                        <?php echo $account->getError(Constants::$PASSWORDS_DO_NOT_MATCH) ?>
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" placeholder="Your password" required>
                    </p>
                    <p>
                        <label for="password2">Confirm password</label>
                        <input id="password2" name="password2" type="password" placeholder="Your password" required>
                    </p>
                    <button type="submit" name="registerButton">SIGN UP</button>
                    <div class="hasAccountText">
                        <span id="hideRegister">Already have an account? Log in here.</span>
                    </div>
                </form>
            </div>
            <div id="loginText">
                <h1>Expand your knowledge!</h1>
                <h2>Ace your exam!</h2>
                <ul>
                    <span>For Test Creators:</span>
                    <li>Create an exam seamlessly</li>
                    <br/>
                    <span>For Test Takers:</span>
                    <li>Focus on answering the exam</li>
                </ul>
            </div>
        </div>
    </div>
    <?php include("includes/footer.php"); ?>
</body>

</html>