<?php session_start();
    require_once("../Private/functions.php");
    require_once("../Private/stockClass.php");
	$gameImage = $_POST['img'] ?? '';
	$gameDescription = $_POST['des'] ??'';
	$gameName = $_POST['name'] ??'';
	$rating = $_POST['rate'] ??'';
	$source = $_POST['source'] ??'';
	$outlet = $_POST['outlet'] ??'';
	checkIfLoggedIn();
	checkIfAdmin();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Item Page</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
	<body id = "itemPage">
		<div style="margin-left: 70px;">
        <a href="listOfitems.php" class ="btn btn-primary" 
            style="margin-top: 20px; color:black; background-color: lightgrey; border:none; font-size: 18px;">Back</a>	
            <a href="index.php" class ="btn btn-primary" 
            style="margin-top: 20px; color:red; background-color: lightgrey; border:none; font-size: 18px;">Logout</a>		
		</div>
		<div>
			<h1 style="text-align: center;font-family: Impact, Charcoal, sans-serif; font-size: 30px;" ><?php echo $gameName;?></h1>
		</div>
		<div id = "description">
			<img class="center-block" src= <?php echo "'".$gameImage."'";?> width="340" height="420" style="margin-bottom:30px; margin-top: 30px;">
			<p
				readonly style="width: 90%; font-size: 18px; 
				height: 100px; text-align: left; margin-left:70px;" disabled> 
			<?php echo $gameDescription;?>  
			</p> 
		</div>
		<div style="width: 90%; margin-left: 5%;">
			<h2 style="text-align: center;font-family: Impact, Charcoal, sans-serif; font-size: 30px;" >Reviews</h2>
			<table class="table table-bordered table text-center" >
				<thead>
					<tr>
						<th class="text-center">Outlet</th>
						<th class="text-center">Score</th>
						<th class="text-center">Source</th>
					</tr>
				</thead>
			<tbody>
				<tr>
					<td><?php echo $outlet;?></td>
					<td> <?php echo $rating;?></td>
					<td> <a href=<?php echo "'".$source."'";?>>  <?php echo $source;?>
					</a></td>
				</tr>
			</tbody>
            </table>
        </div>
    </body>
    <footer style="background-color: lightgrey; min-height: 45px; margin-top: 70px; padding-bottom: 10px;">
        <p style="padding-top: 40px; padding-bottom: 40px; padding-left: 12px; font-size: 15px"> <strong> &copy Copyright David Mahgerefteh and Mandu Shi </strong> </p>
        <img src="resources/joystick.png" width="100" height="100" style="margin-left: 90%; margin-top: -9%" />
    </footer>
</html>