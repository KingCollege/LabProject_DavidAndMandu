<?php 
    class Register{

        private function hashPassword(){
            return password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        private function phoneNumberCheck(){
            if(!is_numeric($_POST['phone']))
                return false;
            return true;
        }

        private function correctEmailFormat(){
            $given = $_POST['email'];
            if(strpos($given, "@") === FALSE)
                return false;
            return true;
        }

        public function registeration(){
            global $connection;
            connectToDB();

            if(!$this->phoneNumberCheck() || !$this->correctEmailFormat())
                return false;

            $hash = $this->hashPassword();
            
            $sql = "INSERT INTO Members (FIRST_NAME, LAST_NAME,DOB,MEMBER_EMAIL,PHONE,MEMBER_TYPE, HASHED_PASS) VALUES";
            $sql .= "(".QUOTE.$_POST['firstname'].QUOTE.",".QUOTE.$_POST['lastname'].QUOTE.","
            .QUOTE.$_POST['dob'].QUOTE.",".QUOTE.$_POST['email'].QUOTE.","
            .QUOTE.$_POST['phone'].QUOTE.",".QUOTE.$_POST['type'].QUOTE.","
            .QUOTE.$hash.QUOTE.");";

            if($connection->query($sql) === FALSE)
                return false;

            $connection->close();
            return true;
         }
 
    }    
?>