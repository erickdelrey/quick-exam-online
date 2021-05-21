<?php

class Account {
    private $con;
    private $errorArray;

    public function __construct($con) {
        $this->con = $con;
        $this->errorArray = array();
    }

    public function login($un, $pw) {
        $pw = md5($pw);
        $query = mysqli_query($this->con, "SELECT * FROM users where username='$un' AND password='$pw'");
        if (mysqli_num_rows($query) == 1) {
            return true;
        } else {
            array_push($this->errorArray, Constants::$LOGIN_FAIL);
            return false;
        }
    }

    public function register($username, $firstName, $lastName, $email, $email2, $password, $password2) {
        $this->validateUsername($username);
        $this->validateFirstName($firstName);
        $this->validateLastName($lastName);
        $this->validateEmails($email, $email2);
        $this->validatePasswords($password, $password2);

        if(empty($this->errorArray) == true) {
            //Insert to db
            return $this->insertUserDetails($username, $firstName, $lastName, $email, $password);
        } else {
            return false;
        }
    }

    public function getError($error) {
        if(!in_array($error, $this->errorArray)) {
            $error = "";
        }
        return "<span class='errorMessage'>$error<span>";
    }

    private function insertUserDetails($un, $fn, $ln, $em, $pw) {
        $encryptedPw = md5($pw);
        $date = date("Y-m-d H:i:s");
        $profilePic = "assets/images/profile-pics/florence.jpg";
        $result = mysqli_query($this->con, "INSERT INTO users (username, firstName, lastName, email, password, signUpDate, profilePic) 
            VALUES ('$un', '$fn', '$ln', '$em', '$encryptedPw', '$date', '$profilePic')");
        echo "INSERT INTO users (username, firstName, lastName, email, password, signUpDate, profilePic) 
        VALUES ('$un', '$fn', '$ln', '$em', '$encryptedPw', '$date', '$profilePic')";
        return $result;
    }

    private function validateUsername($un) {
        if(strlen($un) > 25 || strlen($un) < 5) {
            array_push($this->errorArray, Constants::$USERNAME_INVALID_LENGTH);
            return;
        }

        $checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username='$un'");
        if (mysqli_num_rows($checkUsernameQuery) != 0) {
            array_push($this-> errorArray, Constants::$USERNAME_TAKEN);
            return;
        }
    }
    
    private function validateFirstName($fn) {
        if(strlen($fn) > 50 || strlen($fn) < 1) {
            array_push($this->errorArray, Constants::$FIRSTNAME_INVALID_LENGTH);
            return;
        }
    }
    
    private function validateLastName($ln) {
        if(strlen($ln) > 50 || strlen($ln) < 1) {
            array_push($this->errorArray, Constants::$LASTNAME_INVALID_LENGTH);
            return;
        }
    }
    
    private function validateEmails($em, $em2) {
        if ($em != $em2) {
            array_push($this->errorArray, Constants::$EMAIL_DONT_MATCH);
            return;
        }

        if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Constants::$EMAIL_INVALID);
            return;
        }

        $checkEmailQuery = mysqli_query($this->con, "SELECT email FROM users WHERE email='$em'");
        if (mysqli_num_rows($checkEmailQuery) != 0) {
            array_push($this-> errorArray, Constants::$EMAIL_TAKEN);
            return;
        }
    }
    
    private function validatePasswords($pw, $pw2) {
        if(strlen($pw) > 30 || strlen($pw) < 5) {
            array_push($this->errorArray, Constants::$PASSWORD_INVALID_LENGTH);
            return;
        }

        if ($pw != $pw2) {
            array_push($this->errorArray, Constants::$PASSWORDS_DO_NOT_MATCH);
            return;
        }

        if (preg_match('/[^A-Za-z0-9]/', $pw)) {
            array_push($this->errorArray, Constants::$PASSWORD_NOT_ALPHANUMERIC);
            return;
        }
    }
}





?>










