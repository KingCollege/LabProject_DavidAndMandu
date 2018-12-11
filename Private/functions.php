<?php
    ob_start();
    define("SEC", "Secretary");
    define("VLT", "Volunteer");
    define("STD", "Student");
    define("LOG", "Login");
    define("QUOTE", "\"");
    define("LogOut", "loginPage.php?restartSession=true");
    require_once("databaseinfo.php");
    $differentSecretary = false;

    function restart(){
        if(isset($_GET['restartSession'])){
            session_destroy();
            redirectTo("loginPage.php");
        }
    }

    function disableIfNotSecretary(){
        if(isset($_SESSION[LOG])){
            if($_SESSION[LOG] != SEC)
                echo "disabled";
        }
    }

    function checkIfLoggedIn(){
        if(!isset($_SESSION[LOG]))
            redirectTo(LogOut);
    }

    function checkIfNotAdmin(){
        if($_SESSION[LOG] != SEC && $_SESSION[LOG] != VLT)
            redirectTo("listOfitems.php");
    }

    function checkIfAdmin(){
        if($_SESSION[LOG] == SEC || $_SESSION[LOG] == VLT)
            redirectTo("rentals.php");
    }

    function addDaysToDate($date, $days){
        $date->add(new DateInterval("P".$days."D"));
        return date_format($date, "Y/m/d");
    }

    function highlight(){
        global $id;
        switch($id){
            case 1: 
                echo "<style type=\"text/css\"> #rental{background-color: darkgrey;} </style>";
                break;
            case 2: echo "<style type=\"text/css\"> #game{background-color: darkgrey;} </style>";
                break;
            case 3: echo "<style type=\"text/css\"> #member{background-color: darkgrey;} </style>";
                 break;
            case 4: echo "<style type=\"text/css\"> #rule{background-color:darkgrey;} </style>";
                break;
            case 5: echo "<style type= \"text/css\"> #ban{background-color: darkgrey;} </style>";
                break;
            case 6: echo "<style type=\"text/css\"> #report{background-color: darkgrey;} </style>";
                break;
        }
    }

    function reportError($error = NULL){
        if(isset($error))
            return $error;
        return NULL;
    }

    function redirectTo($page){
        return header("Location: ../Public/".$page);
    }

    function tryLogin(){
        if(isset($_POST['login'])){
            $login = new Login();
            $member = new Member();
            if($login->tryLogin()){
                $accountType = $member->getMemberTypeByEmail();
                if(strtolower($accountType) == strtolower(SEC)){
                    $_SESSION[LOG] = SEC;
                    $_SESSION['adminAccess'] = true;
                    redirectTo("rentals.php");
                }
                else if(strtolower($accountType) == strtolower(VLT)){
                    $_SESSION[LOG] = VLT;
                    $_SESSION['adminAccess'] = true;
                    redirectTo("rentals.php");
                }
                else if(strtolower($accountType) == strtolower(STD)){
                    $_SESSION[LOG] = STD;
                    $_SESSION['adminAccess'] = false;
                    redirectTo("listOfitems.php");
                }
            }
            echo "<p style=\"color: red; text-align: center;\">
            Login failure. Please check if you have the correct password and email combination and whether you are banned.</p>
            <p style=\"color: red; text-align: center;\"> 
            If you have forgotten your password, please see the secretary.
            </p>";;  
        }
    }
    
?>