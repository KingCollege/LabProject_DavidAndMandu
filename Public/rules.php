<?php 
    session_start(); 
    require_once("../Private/functions.php");
    require_once("../Private/ruleClass.php");
    $pageName = "Rules";
    $id = 4;
    $rules = new Rule();
    function changeRule(){
        global $rules;
        if(isset($_POST['update'])){
            $_POST['update'] = NULL;
            $rules->changeRule();
        }
    }
?>
<?php ?>
<!doctype HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $pageName;?></title>
        <link rel = "stylesheet "href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel = "stylesheet "href="css/design.css">
        <script src= "https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"> </script>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"> </script>
        <?php highlight();?>
    </head>
    <body>
        <?php 
            checkIfLoggedIn();
            checkIfNotAdmin();
            require_once("nav.php");     
        ?>
        <div id="wrapperSmall">
        <div class ="accessibleFeature">
            <h3  id="rules"> Change Rule: </h3>
            <div id="change">
                <?php 
                    changeRule();
                    $rules->getRules();
                ?>
                <form action ="rules.php" method ="post">
                <table class="table table-borderless">
                <tr>
                <td><h6>Rent Period (weeks):</h6>
                    <input class ="input" <?php disableIfNotSecretary(); ?> type="number" value ="<?php echo $rules->getRentPeriod();?>" name ="rentP" min="1" /></td>
                <td><h6>Extension per Rent:</h6>
                    <input class ="input" <?php disableIfNotSecretary(); ?>  type="number" value ="<?php echo $rules->getExtensionPerRent();?>" name ="extensionNo" min="0"/></td>
                <td><h6>Extension Period (weeks):</h6>
                    <input class ="input" <?php disableIfNotSecretary(); ?> type="number" value ="<?php echo $rules->getExtensionPeriod();?>" name ="extensionP" min="1"/></td>
                <td><h6>Violation Period (months):</h6>
                    <input class ="input" <?php disableIfNotSecretary(); ?> type="number" value ="<?php echo $rules->getViolationPeriod();?>" name ="violP" min="1"/></td> 
                </tr>
                <tr>
                <td><h6>Rent Number:</h6>
                    <input class ="input" <?php disableIfNotSecretary(); ?>  type="number" value ="<?php echo $rules->getTotalRent();?>" name ="rentNo" min="1"/></td>
                <td><h6>Ban Period (months):</h6>
                    <input class ="input" <?php disableIfNotSecretary(); ?>  type="number" value ="<?php echo $rules->getBanPeriod();?>" name ="banP" min="1"/></td>
                <td><h6>Number of Violations:</h6>
                    <input class ="input" <?php disableIfNotSecretary(); ?>  type="number" value ="<?php echo $rules->getNumberOfViolation();?>" name ="violNo" min="1"/></td>  
                </tr>
                <tr>
                    <td id= "moreThanThree">
                        <input type="reset" <?php disableIfNotSecretary(); ?>  value="Reset" />
                        <input type ="submit" <?php disableIfNotSecretary(); ?>  value ="Update" name="update" />
                    </td>
                </tr>
                </table>
                </form>
            </div>
            <h3  id="stocks"> Rules: </h3>
            <div id ="rules"> 
                <table id ="table5" class= "table table-bordered" style="max-height: 300px; overflow-y: scroll; overflow-x: hidden;">
                <thead>
                    <tr>
                    <th >Rule ID:</th>
                    <th >Rent Period (weeks):</th>
                    <th >Extension per Rent:</th>
                    <th >Extension Period (weeks):</th>
                    <th >Rent Number:</th>
                    <th >Ban Period (months):</th>
                    <th >Number of Violation:</th>
                    <th >Number of Violation Period (months):</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $rules->getRuleID();?></td>
                        <td><?php echo $rules->getRentPeriod();?></td>
                        <td><?php echo $rules->getExtensionPerRent();?></td>
                        <td><?php echo $rules->getExtensionPeriod();?></td>
                        <td><?php echo $rules->getTotalRent();?></td>
                        <td><?php echo $rules->getBanPeriod();?></td>
                        <td><?php echo $rules->getNumberOfViolation();?></td>
                        <td><?php echo $rules->getViolationPeriod();?></td>
                    </tr>
                </tbody>
                </table>
            </div>
        </div>
        <?php require_once("../Private/footer.php");?>
        </div>
    </body>
</html>