<?php
    class Member{
        var $id;
        var $firstName;
        var $lastName;
        var $dob;
        var $email;
        var $phone;
        var $type;
        private function clearResult(){
            $this->id= array();
            $this->firstName= array();
            $this->lastName= array();
            $this->dob= array();
            $this->email= array();
            $this->phone= array();
            $this->type= array();
        }

        private function setResult($result){
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $this->id[]= $row['MEMBER_ID'];
                    $this->firstName[]=  $row['FIRST_NAME'];
                    $this->lastName[]=  $row['LAST_NAME'];
                    $this->dob[] =  $row['DOB'];
                    $this->email[]=  $row['MEMBER_EMAIL'];
                    $this->phone[]=  $row['PHONE'];
                    $this->type[]=  $row['MEMBER_TYPE'];
                }
            }
        }


        public function getMemberTypeByEmail(){
            global $connection;
            connectToDB();

            $sql = "SELECT MEMBER_TYPE FROM Members WHERE MEMBER_EMAIL =".
                QUOTE.$_POST['email'].QUOTE.";";
            $result = $connection->query($sql);
            if($result->num_rows >0){
                return $result->fetch_assoc()['MEMBER_TYPE'];
            }
            return "SOMETHING IS WRONG";
        }

        public function checkIfMemberExists($id){
            global $connection;
            connectToDB();

            $sql ="SELECT MEMBER_ID FROM Members WHERE MEMBER_ID =".$id.";";
            $result= $connection->query($sql);
            if($result->num_rows >0){
                return true;
            }
            return false;
        }

        public function getMembers(){
            global $connection;
            connectToDB();
            $this->clearResult();

            $sql = "SELECT * FROM Members;";
            $result = $connection->query($sql);

            $this->setResult($result);

            $connection->close();
        }

        public function changeMemberType(){
            global $connection;
            global $differentSecretary;
            connectToDB();
            $ban = new Ban();
            if($ban->isMemberIDBanned($_POST['memberID']))
                return false;
            $this->clearResult();

            if($_POST['changetype'] == SEC){
                $oldSecretaryID = "SELECT MEMBER_ID FROM Members WHERE MEMBER_TYPE=".QUOTE.SEC.QUOTE.";";
                $oldSecretaryID = $connection->query($oldSecretaryID);
                $oldSecretaryID = $oldSecretaryID->fetch_assoc()['MEMBER_ID'];
                $sql ="UPDATE Members SET MEMBER_TYPE=".QUOTE."Volunteer".QUOTE."WHERE MEMBER_ID=".$oldSecretaryID.";";
                $connection->query($sql);
                $differentSecretary = true;
            }

            $sql = "UPDATE Members SET MEMBER_TYPE=".QUOTE.$_POST['changetype'].QUOTE." WHERE MEMBER_ID =".$_POST['memberID'].";";
            $connection->query($sql);

            $connection->close();
            $this->getMembers();
            return true;
        }

        private function checkIfMemberHasRentals(){
            global $connection;
            connectToDB();
            $sql = "SELECT * FROM Rentals WHERE MEMBER_ID =".$_POST['memberID'].";";
            $result = $connection->query($sql);
            if($result->num_rows >0)
                return true;
            return false;
        }

        public function removeMember(){
            global $connection;
            connectToDB();
            $ban = new Ban();
            if($ban->isMemberIDBanned($_POST['memberID']) || $this->checkIfMemberHasRentals()){
                return false;
            }

            $sql = "DELETE FROM Members WHERE MEMBER_ID=".$_POST['memberID'].";";
            $connection->query($sql);
            $connection->close();
            $this->getMembers();
            return true;
        }

        public function getMemberID(){
            return $this->id;
        }
        public function getFirstName(){
            return $this->firstName;
        }
        public function getLastName(){
            return $this->lastName;
        }
        public function getDOB(){
            return $this->dob;
        }
        public function getEmail(){
            return $this->email;
        }
        public function getPhone(){
            return $this->phone;
        }
        public function getMemberType(){
            return $this->type;
        }
    }
?>