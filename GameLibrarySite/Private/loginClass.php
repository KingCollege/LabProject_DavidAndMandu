<?php 
    require_once("banClass.php");
    class Login{

        public function tryLogin(){

            global $connection;
            connectToDB();

            $sql = "SELECT MEMBER_ID FROM Members WHERE ";
            $sql .= "MEMBER_EMAIL =".QUOTE.$_POST['email'].QUOTE.";";
            $result = $connection->query($sql);
            if($result->num_rows <= 0)
                return false;
            $banned = new Ban();
            if($banned->isMemberIDBanned($result->fetch_assoc()['MEMBER_ID']))
                return false;

            $sql = "SELECT HASHED_PASS FROM Members WHERE ";
            $sql .= "MEMBER_EMAIL =".QUOTE.$_POST['email'].QUOTE.";";
            $result = $connection->query($sql);

            if($result->num_rows >0){
                if(password_verify($_POST['pwd'], $result->fetch_assoc()['HASHED_PASS']) === FALSE){
                    $connection->close();
                    return false;
                }
                return true;
            }
            else{
                $connection->close();
                return false;
            }
        }

    }
?>