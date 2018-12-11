<?php 
    session_start(); 
    $pageName = "Reports";
    $id = 6;
    require_once("../Private/functions.php");
    require_once("../Private/banClass.php");
    $banned = new Ban();
?>
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
                <h3 id="reports"> Report From System: </h3>
                <input id = "search" type="text" placeholder="Search...." style ="width: 100%; margin-top: 10px;" onkeyup ="filterTable()"/>
                <div id ="damages" style="max-height: 500px; overflow-y: scroll; overflow-x: hidden;"> 
                    <table id ="table4" class= "table table-bordered">
                    <thead>
                        <tr>
                        <th >Report ID:</th>
                        <th >Game ID:</th>
                        <th >Member ID:</th>
                        <th >Fee (£):</th>
                        <th >Refunded (£):</th>
                        <th >Info:</th>
                        <th >Ban Until:</th>
                        </tr>
                    </thead>
                    <tbody id="data">
                        <?php 
                            $banned->getAllBanned();
                            for($i=0; $i< sizeOf($banned->getReportID()) ;$i++){
                        ?>
                        <tr>
                            <td><?php echo $banned->getReportID()[$i];?></td>
                            <td><?php if($banned->getGameID()[$i] == "-1") echo "N/A"; else echo $banned->getGameID()[$i];?></td>
                            <td><?php echo $banned->getMemberID()[$i];?></td>
                            <td><?php echo $banned->getFee()[$i];?></td>
                            <td><?php echo $banned->getRefunded()[$i];?></td>
                            <td><?php echo $banned->getInfo()[$i];?></td>
                            <td><?php echo $banned->getUnbanDate()[$i];?></td>
                        </tr>

                        <?php } ?>
                    </tbody>
                    </table>
                </div>
            </div>
            <?php require_once("../Private/footer.php");?>
        </div>
    </body>
</html> 