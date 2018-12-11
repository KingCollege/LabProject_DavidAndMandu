<?php 
    session_start(); 
    require_once("../Private/functions.php");
    require_once("../Private/memberClass.php");
    require_once("../Private/banClass.php");
    $pageName = "Members";
    $id = 3;
    $members = new Member();

    function removeMember(){
        global $members;
        if(isset($_POST['remove'])){
            $_POST['remove'] = NULL;
            if(!$members->removeMember())
                return false;
        }
        return true;
    }

    function changeType(){
        global $members;
        global $differentSecretary;
        if(isset($_POST['confirmchange'])){
            $_POST['confirmchange'] = NULL;
            if(!$members->changeMemberType()){
                return false;
            }
            if($differentSecretary){
                $differentSecretary =false;
                redirectTo(LogOut);
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
                <h3  id="members"> Members: </h3>
                <input id = "search" type="text" placeholder="Search...." style ="width: 100%; margin-top: 10px;" onkeyup ="filterTable()"/>
                <div id ="members" style="max-height: 600px; overflow-y: scroll; overflow-x: hidden;"> 
                    <?php 
                        if(!removeMember()){
                            echo "<p style=\"color: red; text-align: center;\">
                            Something went wrong with removing this member. You cannot remove members who have been banned or have rentals.</p>";
                        }
                        if(!changeType()){
                            echo "<p style=\"color: red; text-align: center;\">
                            Something went wrong with changing this member's access right. You cannot change access rights of banned members.</p>";
                        }
                    ?>
                    <table id ="table3" class= "table table-bordered" >
                    <thead>
                        <tr>
                        <th >Member ID:</th>
                        <th >First Name:</th>
                        <th >Last Name:</th>
                        <th >Date Of Birth:</th>
                        <th >Email:</th>
                        <th >Phone:</th>
                        <th >Type:</th>
                        <th >Change Type:</th>
                        <th >Remove</th>
                        </tr>
                    </thead>
                    <tbody id="data">   
                        <?php 
                            $members->getMembers();
                            for($i =0; $i < sizeOf($members->getMemberID()) ; $i++ ){
                        ?>
                        <tr>
                            <td><?php echo $members->getMemberID()[$i] ;?></td>
                            <td><?php echo $members->getFirstName()[$i] ;?></td>
                            <td><?php echo $members->getLastName()[$i] ;?></td>
                            <td><?php echo $members->getDOB()[$i] ;?></td>
                            <td><?php echo $members->getEmail()[$i] ;?></td>
                            <td><?php echo $members->getPhone()[$i] ;?></td>
                            <td><?php echo $members->getMemberType()[$i] ;?></td>
                            <td>
                                <form method ="post">
                                    <select required name="changetype" style="min-width: 100px"
                                    <?php if($members->getMemberType()[$i] == SEC || $_SESSION[LOG] == VLT) echo "disabled";?> >
                                        <option value="">None</option>
                                        <option value="Student">Student</option>
                                        <option value="Volunteer">Volunteer</option>
                                        <option value="Secretary"> Secretary</option>
                                    </select>
                                    <input type="hidden" name="memberID" value=<?php echo "\"".$members->getMemberID()[$i]."\"" ;?>/>
                                    <input type="hidden" name="currentType" value=<?php echo "\"".$members->getMemberType()[$i]."\"" ;?>/>
                                    <input type="submit" value="Change" name="confirmchange" 
                                        class="btn btn-primary" style="border-radius:10px; font-size:12px; margin-left:10px;"
                                        <?php if($members->getMemberType()[$i] == SEC || $_SESSION[LOG] == VLT) echo "disabled";?>  />
                                </form>
                            </td>
                            <td>
                                <form method ="post">
                                    <input type="hidden" name="memberID" value=<?php echo "\"".$members->getMemberID()[$i]."\"" ;?>/>
                                    <input type="submit" value="Remove" name="remove" 
                                        class="btn btn-primary" style="border-radius:10px; font-size:12px; margin-left:10px;"
                                        <?php if($members->getMemberType()[$i] == SEC || $_SESSION[LOG] == VLT) echo "disabled";?>  />
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
                var tr, tbody, input, filter, tds;
                input = document.getElementById("search");
                filter = input.value.toLowerCase();
                tbody = document.getElementById("data");
                tr = tbody.getElementsByTagName("tr");
                for(i =0; i < tr.length; i++){
                    tds = tr[i].getElementsByTagName("td");
                    var id = tds[0].textContent.toLowerCase();
                    var firstname = tds[1].textContent.toLowerCase();
                    var lastname = tds[2].textContent.toLowerCase();
                    var dob = tds[3].textContent.toLowerCase();
                    var email = tds[4].textContent.toLowerCase();
                    var phone = tds[6].textContent.toLowerCase();
                    var type = tds[7].textContent.toLowerCase();

                    if (id.indexOf(filter) > -1 || firstname.indexOf(filter) > -1 ||lastname.indexOf(filter) > -1||dob.indexOf(filter) > -1
                    ||email.indexOf(filter) > -1||phone.indexOf(filter) > -1||type.indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }    
                }
            }
        </script>
    </body>
</html>