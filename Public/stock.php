<?php 
    session_start(); 
    require_once("../Private/functions.php");
    require_once("../Private/stockClass.php");
    $games = new Stock();

    if(isset($_POST['add'])){
        $_POST['add'] = NULL;
        $games->addGame();
    }
    $pageName = "Stock";
    $id = 2;
?>
<!doctype HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $pageName; ?></title>
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
                <h3> Add Game: </h3>
                <div id="add">
                    <form method ="post">
                    <table class="table table-borderless" >
                    <tr>
                    <td><h6>Game Name:</h6><input required class = "input" type="text" value ="" name ="gameName" /></td>
                    <td><h6>Release Year:</h6><input required class = "input" type="number" name ="release" /></td>
                    <td><h6>Image:</h6><input required class = "input" type="url" value ="" name ="img" /></td>
                    <td><h6>Description:</h6><input required class = "input" type="text" name="des" /></td>
                    </tr>
                    <tr>
                    <td><h6>Rating (out of 10):</h6><select  class = "input" name ="rating"> 
                        <option value = "0"> 0 </option>
                        <option value = "1"> 1 </option>
                        <option value = "2"> 2 </option>
                        <option value = "3"> 3</option>
                        <option value = "4"> 4</option>
                        <option value = "5">5</option>
                        <option value = "6"> 6</option>
                        <option value = "7"> 7</option>
                        <option value = "8"> 8 </option>
                        <option value = "9"> 9</option>
                        <option value = "10"> 10 </option>
                    </select></td>
                    <td><h6>Platform:</h6><select class = "input" name ="platform"> 
                        <option value = ""> None </option>
                        <option value = "Playstation"> Playstation</option>
                        <option value = "Playstation 2"> Playstation 2 </option>
                        <option value = "Playstation 3"> Playstation 3</option>
                        <option value = "Playstation 4"> Playstation 4</option>
                        <option value = "Xbox"> Xbox</option>
                        <option value = "Xbox 360"> Xbox 360 </option>
                        <option value = "Xbox One"> Xbox One</option>
                        <option value = "PC"> PC </option>
                        <option value = "Nintendo DS"> Nintendo DS</option>
                        <option value = "Nintendo Wii"> Nintendo Wii </option>
                        <option value = "Gameboy"> Gameboy</option>
                        <option value = "NES"> NES </option>
                        <option value = "Nintendo Switch"> Switch </option>
                    </select></td>
                    <td><h6>Genre:</h6><select class = "input" name ="genre"> 
                        <option value = ""> None </option>
                        <option value = "Action"> Action </option>
                        <option value = "Adventure"> Adventure </option>
                        <option value = "Puzzle"> Battle Royale </option>
                        <option value = "Fighting"> Fighting</option>
                        <option value = "FPS"> FPS</option>
                        <option value = "Puzzle"> Puzzle </option>
                        <option value = "Role-Play"> Role-Play</option>
                        <option value = "Racing"> Racing</option>
                        <option value = "Puzzle"> Simulation </option>
                    </select></td>
                    <td><h6>Media Type:</h6><select class = "input" name ="media"> 
                        <option value = ""> None </option>
                        <option value = "CD"> CD </option>
                        <option value = "DVD"> DVD </option>
                        <option value = "Cartridge"> Cartridge </option>
                    </select></td>
                    </tr>
                    <tr>
                        <td><h6>Review Link:</h6><input required  class = "input" type="url" name ="source" /></td>
                        <td><h6>Outlet:</h6><input required class = "input" type="text" name="outlet" /></td>
                    </tr>
                    <tr>
                    <td id ="moreThanThree"><input  id ="sub" type="reset" value="Reset" />
                    <input id ="sub" type ="submit" value ="Add" name="add"/></td>
                    </tr>
                    </table>
                    </form>
                </div>
                <h3 id="stocks"> Stock:</h3>
                <input id = "search" type="text" placeholder="Search...." style ="width: 100%; margin-top: 10px;" onkeyup ="filterTable()"/>
                
                <div id ="stocks" style="max-height: 400px; overflow-y: scroll; overflow-x: hidden;"> 
                    <table id ="table6" class ="table table-bordered" >
                    <thead>
                        <tr>
                        <th >Game ID:</th>
                        <th >Game Name:</th>
                        <th >Availability:</th>
                        <th >Release Year:</th>
                        <th >Rating:</th>
                        <th >Platform:</th>
                        <th >Genre:</th>
                        <th >Media Type:</th>
                        <th >Description:</th>
                        <th >Review Source:</th>
                        </tr>
                    </thead>
                    <tbody id="data">
                            <?php
                             $games->getAllGames();
                            for($i =0; $i < sizeOf($games->getGameName()); $i++){
                            ?>
                            <tr>
                                <td> <?php echo $games->getGameID()[$i];?> </td>
                                <td><?php echo $games->getGameName()[$i];?></td>
                                <td><?php if($games->getRented()[$i] == 1) echo"Rented"; else echo "Available" ;?></td>
                                <td><?php echo $games->getReleaseYear()[$i];?></td>
                                <td><?php echo $games->getRating()[$i] ;?></td>
                                <td><?php echo $games->getPlatform()[$i];?></td>
                                <td><?php echo $games->getGenre()[$i];?></td>
                                <td><?php echo $games->getMedia()[$i];?></td>
                                <td> <p style="text-align:left;max-height: 60px; overflow-y: scroll; overflow-x: hidden;"> 
                                    <?php echo $games->getDescription()[$i] ;?> 
                                </p> </td>
                                <td><p style="max-width: 100px; overflow-y: scroll; overflow-y: hidden;">
                                <?php echo $games->getSource()[$i] ;?></p></td>
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
                var tr, tbody, input, filter, tds;
                input = document.getElementById("search");
                filter = input.value.toLowerCase();
                tbody = document.getElementById("data");
                tr = tbody.getElementsByTagName("tr");
                for(i =0; i < tr.length; i++){
                    tds = tr[i].getElementsByTagName("td");
                    var id = tds[0].textContent.toLowerCase();
                    var name = tds[1].textContent.toLowerCase();
                    var avail = tds[2].textContent.toLowerCase();
                    var year = tds[3].textContent.toLowerCase();
                    var plat = tds[4].textContent.toLowerCase();
                    var gen = tds[6].textContent.toLowerCase();
                    var media = tds[7].textContent.toLowerCase();

                    if (id.indexOf(filter) > -1 || name.indexOf(filter) > -1 ||avail.indexOf(filter) > -1||year.indexOf(filter) > -1
                    ||plat.indexOf(filter) > -1||gen.indexOf(filter) > -1||media.indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }    
                }
            }
        </script>
    </body>
</html>