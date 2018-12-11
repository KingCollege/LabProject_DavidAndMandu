<?php
    session_start(); 
    require_once("../Private/functions.php");
    require_once("../Private/rentalClass.php");

    $rentals = new Rental();
    $games = new Stock();
    $rules = new Rule();
    $rules->getRules();
    $pageName = "Records";

    function extendRental(){
        global $rentals;
        if(isset($_POST['extend'])){
            $_POST['extend'] = NULL;
            if(!$rentals->addExtensions())
                return false;
        }
        return true;
    }

    function returnRental(){
        global $rentals;
        if(isset($_POST['return'])){
            $_POST['return'] = NULL;
            $rentals->returnGame();
        }
    }

    function addRental(){
        global $rentals;
        if(isset($_POST['add'])){
            $_POST['add'] = NULL;
            if(!$rentals->addRental()){
                return false;
            }
        }
        return true;
    }

    $dueDate = new DateTime(date("Y/m/d"));
    $dueDate->add(new DateInterval("P".(7 * (int)$rules->getRentPeriod())."D"));
    $maxExtensions = (int)$rules->getExtensionPerRent();
    $id = 1;
?>
<!doctype HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php $pageName;?></title>
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
        <div id="wrapper">
        <div class ="accessibleFeature">
            <h3  id="stocks"> Add Rental: </h3>
            <div id="add">
                <form action ="rentals.php" method="post">
                <?php
                    if(!extendRental())
                        echo "<p style=\"color: red; text-align: center;\">
                        Adding this rental has violated some Rules. Please check if this member has either been banned
                        or has too many extensions on this game.</p>";
                
                    if(!addRental())
                        echo "<p style=\"color: red; text-align: center;\">
                         Please check if this member has either been banned,
                         has rented too many games, an invalid member ID was given or an invalid game was selected.</p>";
                ?>
                <table class="table table-borderless">
                <tbody><tr>
                <td><h6>Game:</h6><select required name ="game" >
                    <option value = ""> None </option>
                    <?php $games->getAllAvailableUniqueGames();
                     for($i =0; $i< sizeOf($games->getGameName()); $i++) {?>
                        <option value = <?php echo "\"".$games->getGameName()[$i]."\""; ?>> 
                            <?php echo $games->getGameName()[$i];?>
                        </option>
                    <?php }?>
                </select></td>
                <td><h6>Member ID:</h6><input required class = "input" type="number" name ="memberID" min="0"/></td>
                <td>
                    <h6>Platforms</h6>
                    <select required name ="platform"> 
                        <option value = ""> None </option>
                        <?php
                            $platforms = $games->getAllPlatform();
                            for($i =0; $i < sizeOf($platforms) ; $i++) {?>
                            <option value = <?php echo QUOTE.$platforms[$i].QUOTE; ?> >
                                <?php echo $platforms[$i];?>
                            </option> 
                        <?php }?>
                    </select>
                </td>
                <td>
                    <h6>Media Type</h6>
                    <select required name ="mediatype"> 
                        <option value = ""> None </option>
                        <?php
                            $medias = $games->getAllMediaTypes();
                            for($i =0; $i < sizeOf($medias) ; $i++) {?>
                            <option value = <?php echo QUOTE.$medias[$i].QUOTE; ?> >
                                <?php echo $medias[$i];?>
                            </option> 
                        <?php }?>
                    </select>
                </td>
                </tr>
                <tr>
                <td><h6>Today's Date:</h6><input required readonly class = "input" type="text" value = <?php echo "\"".date("Y/m/d")."\"" ;?> name ="rentedD" /></td>
                <td><h6>Date Due:</h6><input required readonly class = "input" type="text"
                                    value= <?php echo "\"".date_format($dueDate, "Y/m/d")."\"";?> name ="expireD" /></td>
                <td><h6>Extra Extension:</h6>
                    <input required class = "input" type="number" name ="extension" value ="0" min="0" max = <?php echo QUOTE.$maxExtensions.QUOTE;?> /></td>
                </tr>
                <tr>
                <td id="moreThanThree"><input class = "input" type="reset" value="Reset" />
                <input  class = "input" type ="submit" value ="Add" name="add" /></td>
                </tr>
                </tbody>
                </table>
                </form>
            </div>
            <h3 id="stocks"> Rentals:</h3>
            <input id = "search" type="text" placeholder="Search...." style ="width: 100%; margin-top: 10px;" onkeyup ="filterTable()"/>
            <div id ="records" style="max-height: 500px; overflow-y: scroll; overflow-x: hidden;"> 
                    <?php returnRental();?>
                    <table id ="table1" class ="table table-bordered">
                    <thead class ="head">
                        <tr>
                        <th >Rent ID:</th>
                        <th >Date Rented:</th>
                        <th >Due Date:</th>
                        <th >Extra Extension:</th>
                        <th >Game ID:</th>
                        <th >Member ID:</th>
                        <th >Give Extension:</th>
                        <th >Return Game:</th>
                        </tr>
                    </thead>
                    <tbody id ="data">
                            <?php  $rentals->getAllNotReturnedGames();
                            for($i =0; $i < sizeOf($rentals->getRentID()); $i++) {?>
                            <tr>
                                <td><?php echo $rentals->getRentID()[$i] ?></td>
                                <td><?php echo $rentals->getDateRented()[$i] ?></td>
                                <td><?php echo $rentals->getDueDate()[$i] ?></td>
                                <td><?php echo $rentals->getExtensions()[$i] ?></td>
                                <td><?php echo $rentals->getGameID()[$i] ?></td>
                                <td><?php echo $rentals->getMemberID()[$i] ?></td>
                            <th><form action = "rentals.php" method="post"> 
                                <select required name="extension"  id="extensiondropdown">
                                    <option value =""> 0 </option>
                                    <?php for($l =1; $l <= $maxExtensions; $l++){?>
                                        <option value = <?php echo QUOTE.$l.QUOTE;?>> <?php echo $l;?> </option>
                                    <?php }?>
                                </select>
                                <input type ="hidden" value = <?php echo QUOTE.$rentals->getRentID()[$i].QUOTE?> name="rentID"/>
                                <input type ="hidden" value = <?php echo QUOTE.$rentals->getMemberID()[$i].QUOTE?> name="memberID"/>    
                                <input type ="hidden" value = <?php echo QUOTE.$rentals->getDueDate()[$i].QUOTE?> name="dueDate"/>  
                                <input type ="hidden" value = <?php echo QUOTE.$rentals->getExtensions()[$i].QUOTE?> name="extensionNo"/>  
                                <input type="submit" value="Add" class = "btn btn-primary" style="border-radius:10px" name ="extend" />
                            </form> </th>
                            <td>
                                <form action="rentals.php" method="post">
                                    <input type="hidden" value = <?php echo QUOTE.$rentals->getGameID()[$i].QUOTE?>  name="returnID" />
                                    <input type="submit" value ="Return" name="return" class = "btn btn-primary" style="border-radius:10px"/>
                                </form>   
                            </td>
                            </tr>
                            <?php }?>
                    </tbody>
                    </table>
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