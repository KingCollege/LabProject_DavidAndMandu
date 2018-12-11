<?php 
    require_once("../Private/functions.php");
    require_once("../Private/registerClass.php");
    $register = new Register();
    function tryRegister(){
        global $register;
        if(isset($_POST['register'])){
            if(!$register->registeration())
            return false;
        }
        return true;
    }
?>
<!DOCTYPE html>
<html style="max-height: 700px;">
	<head>
		<title>Login Page</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel = "stylesheet "href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/design.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
	<body>
        <div id="wrapperSmall">
            <div class="accessibleFeature"> 
                <a href="loginPage.php" class="btn btn-primary" 
                    style="border:none; color: black; background-color: lightgrey; 
                    margin-top: 20px; font-size: 18px; margin-left:-5px; padding-right:20px; padding-left:20px;">
                    Back
                </a>
                <div id="add">
                    <?php 
                        if(!tryRegister()){
                            echo "<p style=\"color: red; text-align: center;\">
                            There has been a problem with registering, please check if you have all the fields filled correctly.
                            </p>
                            <p style=\"color: red; text-align: center;\">
                            Please make sure that you use an email that has not been registered before.</p>
                            <p style=\"color: red; text-align: center;\">
                            Please make sure you use the correct format for date of birth.</p>";
                        }
                    ?>
                    <form action ="" style="margin-top:100px;" method="post">
                        <h3> Register: </h3>
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                <td><h6>First Name:</h6><input required class = "input" type="text" value ="" name ="firstname" /></td>
                                <td><h6>Last Name:</h6><input required class = "input" type="text" value ="" name ="lastname" /></td>
                                <td><h6>Date Of Birth:</h6><input required class = "input" type="date" name ="dob" placeholder ="YYYY/M/D" /></td>
                                <td><h6>Phone:</h6><input required class = "input" type="text" name ="phone"/></td>
                                </tr>
                                <tr>
                                <td><h6>Email:</h6><input required class = "input" type="email" value ="" name ="email" /></td>
                                <td><h6>Password:</h6><input required class = "input" type="password" value ="" name ="password" /></td>
                                <td><h6>Type:</h6><select class = "input" name="type" >
                                            <option value = "Student"> Student </option>
                                            <option value = "Volunteer"> Volunteer </option>  
                                            </select>
                                </td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; padding-left:50px;"><input class = "input" type="reset" value="Reset" />
                                    <input class = "input" type ="submit" value ="Register" name="register"/></td>
                                </tr>
                            </thead>
                        </table>
                    </form>
                </div>
            </div>
            <?php require_once("../Private/footer.php");?>
        </div>
	</body>
</html>