<?php 
    session_start(); 
    $pageName = "Damages";
    $id = 5;
    require_once("../Private/functions.php");
    require_once("../Private/stockClass.php");
    require_once("../Private/memberClass.php");
    require_once("../Private/banClass.php");
    require_once("../Private/ruleClass.php");
    $games = new Stock();
    
    function tryBan(){
        if(isset($_POST['ban'])){
            $_POST['ban'] = NULL;
            $ban = new Ban();
            if(!$ban->banMember()){
                return false;
            }
        }
        return true;
    }
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
                <?php 
                
                    if(!tryBan()){
                        echo "<p style=\"color: red; text-align: center;\">
                        Something went wrong with banning this member. Please check if this member can be banned.</p>";
                    }
                
                ?>
                <h3 > Ban Member:</h3>
                <div id="banmember" >
                    <form method="post">
                    <table class="table table-borderless">
                        <tr>
                            <td><h6>Game ID:</h6><input <?php disableIfNotSecretary(); ?> class ="input" type="number" required name="gameID" min="0"/>
                        </tr>
                        <tr>
                            <td><h6>Member ID:</h6><input <?php disableIfNotSecretary(); ?>  required type="text" value ="" name ="memberID" style = "width: 200px" min="0"/></td>
                        </tr>
                        <tr>
                            <td><h6>Fee:</h6><input <?php disableIfNotSecretary(); ?>  required type="number" value ="0" name ="fee" style = "width: 200px" min="1" /></td>
                        </tr>
                        <tr>
                            <td><h6>Info:</h6><input <?php disableIfNotSecretary(); ?>  required type="text" value ="" name ="info" style = "width: 200px"/></td>
                        </tr>
                        <tr>
                            <td><input <?php disableIfNotSecretary(); ?>  type="reset" value="Reset" />
                            <input <?php disableIfNotSecretary(); ?>  type ="submit" value ="Ban" name="ban" /></td>
                        </tr>
                    </table>
                    </form>
                </div>
            </div>
            <?php require_once("../Private/footer.php");?>
         </div>

        <script>
            function filterTable(){
                var tr, tbody, input, td, filter;
                input = document.getElementById("search");
                filter = input.value.toLowerCase();
                tbody = document.getElementById("data");
                tr = tbody.getElementsByTagName("tr");
                for(i =0; i < tr.length; i++){
                    td = tr[i].getElementsByTagName("td")[0];
                    if(td){
                    if(td.innerHTML.toLowerCase().indexOf(filter) > -1)
                        tr[i].style.display = "";
                    else
                        tr[i].style.display = "none";
                    }
                }
            }
        </script>
    </body>
</html>