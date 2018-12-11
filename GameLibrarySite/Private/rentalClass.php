<?php
    require_once("stockClass.php");
    require_once("banClass.php");
    require_once("violationClass.php");
    require_once("memberClass.php");
    class Rental{
        var $rentID = array();
        var $dateRented  = array();
        var $dueDate = array();
        var $dateReturned = array();
        var $extraExtension = array();
        var $gameID = array();
        var $memberID = array();

        private function clearResult(){
            $this->rentID = array();
            $this->dateRented =array();
            $this->dueDate =array();
            $this->dateReturned = array();
            $this->extraExtension = array();
            $this->gameID = array();
            $this->memberID = array();
            $this->extensionGiven = array();
        }

        private function setResult($result){
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $this->rentID[] = $row['RENT_ID'];
                    $this->dateRented[] = $row['DATE_RENTED'];
                    $this->dueDate[] = $row['DUE_DATE'];
                    $this->dateReturned[] = $row['DATE_RETURNED'];
                    $this->extraExtension[] =  $row['EXTRA_EXTENSIONS'];
                    $this->gameID[] = $row['GAME_ID'];
                    $this->memberID[] = $row['MEMBER_ID'];
                }
            }
        }

        private function violationOfRules(){
            global $connection;
            $bans = new Ban();
            $rule = new Rule();
            $member = new Member();
            $rule->getRules();
            if($bans->isMemberIDBanned($_POST['memberID']) || !$member->checkIfMemberExists($_POST['memberID']))
                return true;
            //A connection to DB was formed from IsMemberIDBanned and CheckIfMemberExists therefore no need to form another
            //connection with DB

            $sql = "SELECT COUNT(GAME_ID) as rents FROM Rentals WHERE MEMBER_ID = ".$_POST['memberID']
                    ." AND DATE_RETURNED IS NULL".";";

            $totalRentsByMember = $connection->query($sql);
            if($totalRentsByMember->num_rows > 0){
                $totalRentsByMember = $totalRentsByMember->fetch_assoc()['rents'];
            }
            $maximumRentsAllowed = $rule->getTotalRent();

            if($totalRentsByMember == $maximumRentsAllowed)
                return true;


            return false;
        }

        public function findRentalByGameID($id){
            global $connection;
            $this->clearResult();
            connectToDB();
            $sql = "SELECT * FROM Rentals ";
            $sql .="WHERE GAME_ID = ".$id." AND DATE_RETURNED IS NULL ;";
            $result = $connection->query($sql);
            $this->setResult($result);
            $connection->close();
        }

        public function findRentalByRentID($id){
            global $connection;
            $this->clearResult();
            connectToDB();
            $sql = "SELECT * FROM Rentals ";
            $sql .="WHERE RENT_ID = ".$id." ;";
            $result = $connection->query($sql);
            $this->setResult($result);
            $connection->close();
        }

        public function getAllRentedGames(){
            global $connection;
            $this->clearResult();
            connectToDB();
            $sql = "SELECT * FROM Rentals;";
            $result = $connection->query($sql);
            $this->setResult($result);
            $connection->close();
        }

        public function addRental(){
            $rules = new Rule();
            $rules->getRules();
            global $connection;
            $this->clearResult();
            connectToDB();

            $selectedGameID = array();
            
            $sql = "SELECT * FROM Games WHERE GAME_NAME = ".QUOTE.$_POST['game'].QUOTE." AND PLATFORM =".
                QUOTE.$_POST['platform'].QUOTE." AND MEDIA_TYPE =".QUOTE.$_POST['mediatype'].QUOTE." AND RENTED =0;";
            $result = $connection->query($sql);

            if($result->num_rows == 0)
                return false;
            else{
                while($row = $result->fetch_assoc())
                    $selectedGameID[] = $row['GAME_ID'];
            }

            if($this->violationOfRules()){
                return false;
            }

            if((int)$_POST['extension'] > 0){
                $needToExtend = new DateTime($_POST['expireD']);
                $_POST['expireD'] = addDaysToDate($needToExtend, ((int)$rules->getExtensionPeriod() * 7) * (int) $_POST['extension']);
            }

            $sql = "INSERT INTO Rentals (DATE_RENTED, DUE_DATE, DATE_RETURNED, EXTRA_EXTENSIONS, GAME_ID, MEMBER_ID) ";
            $sql .= "VALUES (".QUOTE.$_POST['rentedD'].QUOTE.",".QUOTE.$_POST['expireD'].QUOTE
                    .",NULL,".$_POST['extension'].",".$selectedGameID[0];
            $sql .= ",".$_POST['memberID'].");";


            $connection->query($sql);

            $sql = "UPDATE Games SET RENTED = 1 WHERE GAME_ID =".$selectedGameID[0].";";
            $connection->query($sql); 
            $connection->close();
            return true;
        }

        public function getAllNotReturnedGames(){
            global $connection;
            $this->clearResult();
            connectToDB();
            $sql = "SELECT * FROM Rentals WHERE DATE_RETURNED IS NULL;";
            $result = $connection->query($sql);
            $this->setResult($result);
            $connection->close();
        }

        public function addExtensions(){
            $rules = new Rule();
            $rules->getRules();
            $this->findRentalByRentID($_POST['rentID']);
            $currentExtension;
            if(sizeOf($this->rentID) > 0){
                $currentExtension = (int)$this->extraExtension[0] + (int)$_POST['extension'];
                if($currentExtension > (int)$rules->getExtensionPerRent())
                    return false;
            }

            global $connection;
            connectToDB();
            //check with rules
            $newDueDate = new DateTime($_POST['dueDate']);
            
            //get extension period from rules
            $extensionlength = ((int)$rules->getExtensionPeriod() * 7) * (int)$_POST['extension'];
            $newDueDate->add(new DateInterval("P".$extensionlength."D"));

            $extended = (int)$_POST['extension'] + (int)$_POST['extensionNo'];
            $newDueDate = $newDueDate->format("Y/m/d");


            $sql ="UPDATE Rentals SET EXTRA_EXTENSIONS =".$extended.", DUE_DATE="."\"".$newDueDate."\" ";
            $sql .="WHERE RENT_ID =".$_POST['rentID'].";";

            if(!$connection->query($sql))
                echo "Something is Wrong!";

            $connection->close();
            return true;
        }

        public function returnGame(){
            global $connection;
            connectToDB();

            //if a game was returned late, add to violation of rules for this player
            $sql = "SELECT DUE_DATE, MEMBER_ID FROM Rentals WHERE GAME_ID=".$_POST['returnID']." AND DATE_RETURNED IS NULL;";
            $result = $connection->query($sql);
            $dueOn = -1;
            $member = -1;
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $dueOn = new DateTime($row['DUE_DATE']);
                    $member = $row['MEMBER_ID'];
                }
                $returnedOn = new DateTime(date("Y/m/d"));
                if($returnedOn > $dueOn){
                    $updateViolation = new Violation();
                    $updateViolation->addViolations($member);
                }
            }

            $sql = "UPDATE Rentals SET DATE_RETURNED=".QUOTE.date("Y/m/d").QUOTE." ";
            $sql .= "WHERE GAME_ID=".$_POST['returnID'].";";
            $connection->query($sql);
            $sql = "UPDATE Games SET RENTED = 0 WHERE GAME_ID =".$_POST['returnID'].";";
            $connection->query($sql); 

            
            $sql = "SELECT FEE FROM Bans WHERE GAME_ID=".$_POST['returnID'].";";
            $result = $connection->query($sql);
            $needToRefund;
            if($result->num_rows > 0)
                $needToRefund = $result->fetch_assoc()['FEE'];

            if(isset($needToRefund) && (int)$needToRefund != 0){
                $sql = "UPDATE Bans SET REFUNDED =".$needToRefund
                .", INFO = \" This member has refunded the expected amount, they are no longer banned.\" 
                , UNBAN_DATE = \"Refunded\" WHERE GAME_ID=".$_POST['returnID'].";";
                $connection->query($sql);
            }

            $connection->close();
            $this->getAllNotReturnedGames();
        }

        public function getRentID(){
            return $this->rentID;
        }
        public function getDateRented(){
            return $this->dateRented;
        }
        public function getDueDate(){
            return $this->dueDate;
        }
        public function getDateReturned(){
            return $this->dateReturned;
        }
        public function getExtensions(){
            return $this->extraExtension;
        }
        public function getGameID(){
            return $this->gameID;
        }
        public function getMemberID(){
            return $this->memberID;
        }
        public function getExtensionsGiven(){
            return $this->extensionGiven;
        }
    }

?>