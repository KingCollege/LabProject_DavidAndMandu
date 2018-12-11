<?php session_start();
    require_once("../Private/functions.php");
    require_once("../Private/stockClass.php");
    require_once("../Private/rentalClass.php");
    $games = new Stock();
    $rented = new Rental();
    checkIfLoggedIn();
    checkIfAdmin();
?>
<!DOCTYPE html>
<html> 
	<head>
		<title> User Side </title>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/design.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
	<body id = "listOfItems">
        <div id="wrapperSmall">
        <div class ="accessibleFeature">
            <div id = "logout">
                
                <a href="<?php echo LogOut;?>" class ="btn btn-primary" 
                style="margin-top: 20px; color:red; background-color: lightgrey; border:none; font-size: 18px;">Logout</a>			
                
            </div>
            <div >
                <h2 style="text-align: center;font-family: Impact, Charcoal, sans-serif; font-size: 30px;">Games in Stock</h2>
                <p style="text-align: center; font-size: 14px">
                    Below is a list of games that you are able to currently rent out.</p>            
                <input class="form-control" id="myInput" type="text" placeholder="Search by Title, Genre, Platform, Release Year or Media Type..." onkeyup ="filterTable()">
                <div style="max-height: 200px; overflow-y: scroll; overflow-x: hidden;">
                    <table class="table table-bordered table"  style = "text-align: center"id = "table1">
                    <thead>
                        <tr>
                        <th style = "text-align: center">ID</th>
                            <th style = "text-align: center">Title</th>
                            <th style = "text-align: center">Genre</th>
                            <th style = "text-align: center">Platform</th>
                            <th style = "text-align: center">Release Year</th>
                            <th style = "text-align: center">Media Type</th>
                            <th style = "text-align: center">Currently Available</th>
                            <th style = "text-align: center">Preview</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                    <?php $games->getAllAvailableGames();
                            for($i =0; $i < sizeOf($games->getGameName()); $i++){
                        ?>
                            <tr>
                                <td> <?php echo $games->getGameID()[$i];?> </td>
                                <form action ="itemPage.php" method="post">
                                    <input type="hidden" name="name" value= <?php echo  "\"".$games->getGameName()[$i]."\"";?> />
                                    <input type="hidden" name="img" value= <?php echo  "\"".$games->getImage()[$i]."\"";?> />
                                    <input type="hidden" name="des" value= <?php echo  "\"".$games->getDescription()[$i]."\"";?> />
                                    <input type="hidden" name="source" value= <?php echo  "\"".$games->getSource()[$i]."\"";?> />
                                    <input type="hidden" name="rate" value= <?php echo  "\"".$games->getRating()[$i]."\"";?> />
                                    <input type="hidden" name="outlet" value= <?php echo  "\"".$games->getOutlet()[$i]."\"";?> />
                                <td>
                                    <h6 style="display: none"> <?php echo $games->getGameName()[$i];?> </h6> 
                                    <input type="submit" value= <?php echo "\"".$games->getGameName()[$i]."\"" ;?> id="reviewInput"/>
                                    </td>
                                </form>
                                <td><?php echo $games->getGenre()[$i];?></td>
                                <td><?php echo $games->getPlatform()[$i];?></td>
                                <td><?php echo $games->getReleaseYear()[$i];?></td>
                                <td><?php echo $games->getMedia()[$i];?></td>
                                <td><?php if($games->getRented()[$i] == 0) echo "Available"; else echo "SOMETHING IS WRONG";?></td>
                                <td>
                                    <img src= <?php echo "\"".$games->getImage()[$i]."\"";?> width="85" height="105"/>
                                </td>
                            </tr>
                        <?php }?>
                    </tbody>
                    </table>
                </div>
            </div>
            
            <div>
                <h2 style="text-align: center;font-family: Impact, Charcoal, sans-serif; font-size: 30px;" >Games Not in Stock</h2>
                <p style="text-align: center; font-size: 14px">
                    Below is a list of games currently rented out by other members.</p>       
                <input class="form-control" id="myInput2" type="text" placeholder="Search by Title, Genre, Platform, Release Year or Media Type..." onkeyup ="filterTable2()">
                <div style="max-height: 200px; overflow-y: scroll; overflow-x: hidden;">
                    <table class="table table-bordered table" style = "text-align: center" id = "table2">
                        <thead >
                            <tr>
                            <th style = "text-align: center">ID</th>
                                <th style = "text-align: center">Title</th>
                                <th style = "text-align: center">Genre</th>						
                                <th style = "text-align: center">Platform</th>
                                <th style = "text-align: center">Release Year</th>
                                <th style = "text-align: center">Media Type</th>
                                <th style = "text-align: center">Due Date</th>
                                <th style = "text-align: center">Preview</th>
                            </tr>
                        </thead>
                        <tbody id="myTable2">
                        <?php $games->getAllUnAvailableGames();
                            for($i =0; $i < sizeOf($games->getGameName()); $i++){
                        ?>
                            <tr>
                                <td> <?php echo $games->getGameID()[$i];?> </td>
                                <form action ="itemPage.php" method="post">
                                <input type="hidden" name="name" value= <?php echo  "\"".$games->getGameName()[$i]."\"";?> />
                                    <input type="hidden" name="img" value= <?php echo  "\"".$games->getImage()[$i]."\"";?> />
                                    <input type="hidden" name="des" value= <?php echo  "\"".$games->getDescription()[$i]."\"";?> />
                                    <input type="hidden" name="source" value= <?php echo "\"".$games->getSource()[$i]."\"";?> />
                                    <input type="hidden" name="rate" value= <?php echo  "\"".$games->getRating()[$i]."\"";?> />
                                    <input type="hidden" name="outlet" value= <?php echo  "\"".$games->getOutlet()[$i]."\"";?> />
                                <td> 
                                    <h6 style="display: none"> <?php echo $games->getGameName()[$i];?> </h6>
                                    <input type="submit" value= <?php echo "\"".$games->getGameName()[$i]."\"" ;?> id="reviewInput"/>
                                </td>
                                </form>
                                <td><?php echo $games->getGenre()[$i];?></td>
                                <td><?php echo $games->getPlatform()[$i];?></td>
                                <td><?php echo $games->getReleaseYear()[$i];?></td>
                                <td><?php echo $games->getMedia()[$i];?></td>
                                <?php $rented->findRentalByGameID($games->getGameID()[$i]) ?>
                                <td><?php if( !$rented->getDueDate() ) echo "NULL"; else echo $rented->getDueDate()[0] ;?></td>
                                <td>
                                    <img src= <?php echo "\"".$games->getImage()[$i]."\"";?> width="85" height="105"/>
                                </td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php require_once("../Private/footer.php");?>
    </div>
    <script>
            function filterTable(){
                var tr, tbody, input, filter, tds;
                input = document.getElementById("myInput");
                filter = input.value.toLowerCase();
                tbody = document.getElementById("myTable");
                tr = tbody.getElementsByTagName("tr");
                for(i =0; i < tr.length; i++){
                    tds = tr[i].getElementsByTagName("td");
                    var id = tds[0].textContent.toLowerCase();
                    var title = tds[1].textContent.toLowerCase();
                    var genre = tds[2].textContent.toLowerCase();
                    var platform = tds[3].textContent.toLowerCase();
                    var release = tds[4].textContent.toLowerCase();
                    var media = tds[5].textContent.toLowerCase();

                    if (id.indexOf(filter) > -1 || title.indexOf(filter) > -1 ||genre.indexOf(filter) > -1||platform.indexOf(filter) > -1
                    ||release.indexOf(filter) > -1||media.indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }    
                }
            }

            function filterTable2(){
                var tr, tbody, input, filter, tds;
                input = document.getElementById("myInput2");
                filter = input.value.toLowerCase();
                tbody = document.getElementById("myTable2");
                tr = tbody.getElementsByTagName("tr");
                for(i =0; i < tr.length; i++){
                    tds = tr[i].getElementsByTagName("td");
                    var id = tds[0].textContent.toLowerCase();
                    var title = tds[1].textContent.toLowerCase();
                    var genre = tds[2].textContent.toLowerCase();
                    var platform = tds[3].textContent.toLowerCase();
                    var release = tds[4].textContent.toLowerCase();
                    var media = tds[5].textContent.toLowerCase();

                    if (id.indexOf(filter) > -1 || title.indexOf(filter) > -1 ||genre.indexOf(filter) > -1||platform.indexOf(filter) > -1
                    ||release.indexOf(filter) > -1||media.indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }    
                }
            }
        </script>
	</body>
</html>