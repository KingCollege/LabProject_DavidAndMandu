<?php 
    class Ban{
        var $reportID;
        var $gameID;
        var $memberID;
        var $fee;
        var $refunded;
        var $info;
        var $unban;

        private function clearResult(){
            $this->reportID = array();
            $this->gameID = array();
            $this->memberID = array();
            $this->fee  = array();
            $this->refunded = array();
            $this->info = array();
        }

        private function setResult($result){
            if($result->num_rows > 0 ){
                while($row = $result->fetch_assoc()){
                    $this->reportID[] = $row['REPORT_ID'];
                    $this->gameID[] = $row['GAME_ID'];
                    $this->memberID[] = $row['MEMBER_ID'];
                    $this->fee[]  = $row['FEE'];
                    $this->refunded[] = $row['REFUNDED'];
                    $this->info[] = $row['INFO'];
                    $this->unban[] = $row['UNBAN_DATE'];
                }
            }
        }

        public function updateBans(){
            $todaysDate = new DateTime(date("Y/m/d"));
            global $connection;
            connectToDB();

            $sql = "SELECT UNBAN_DATE FROM Bans WHERE STR_TO_DATE(UNBAN_DATE, \"%Y-%m-%d\") IS NOT NULL;";
            $banDates = array();
            $result = $connection->query($sql);

            if($result->num_rows > 0){
                while($row = $result->fetch_assoc())
                    $banDates[] = $row['UNBAN_DATE'];
            }

            foreach($banDates as $d){
                $temp = new DateTime($d);
                if($todaysDate >= $temp){
                    $needToRemove = date_format($temp, "Y/m/d");
                    $sql = "DELETE FROM Bans WHERE UNBAN_DATE =".QUOTE.$needToRemove.QUOTE.";";
                    $connection->query($sql);
                }
            }       
            $connection->close();
        }

        public function banMemberIDForViolations($id){
            $rules = new Rule();
            $rules->getRules();
            global $connection;
            connectToDB();

            $sql = "SELECT MEMBER_ID FROM Members ";
            $sql .= "WHERE MEMBER_TYPE = \"".SEC."\";";

            $result = ($connection->query($sql))->fetch_assoc();
            $sID = $result['MEMBER_ID'];

            if($sID == $id)
                return false;
            
            $banDays = ((int)$rules->getBanPeriod() * 4) * 7;
            $banUntil = addDaysToDate(new DateTime(date("Y/m/d")), $banDays);
            $sql = "INSERT INTO Bans (GAME_ID, MEMBER_ID, FEE, REFUNDED, INFO, UNBAN_DATE) ";
            $sql .= "VALUES ("."-1,".$id.",0,"."0,"
                .QUOTE."Banned for multiple violation of rules".QUOTE.",".QUOTE.$banUntil.QUOTE.");";

            $connection->query($sql);
            return true;
        }

        public function isBannable($id, $gameID){
            global $connection;
            connectToDB();
            $this->clearResult();
            $sql = "SELECT MEMBER_ID FROM Members ";
            $sql .= "WHERE MEMBER_TYPE = \"".SEC."\";";

            $result = ($connection->query($sql))->fetch_assoc();
            $sID = $result['MEMBER_ID'];

            if($sID == $id){
                return false;
            }

            $sql = "SELECT GAME_ID FROM Rentals WHERE MEMBER_ID=".$id." AND GAME_ID =".$gameID." AND DATE_RETURNED IS NULL;";

            $result = $connection->query($sql);
            if($result->num_rows >0 )
                return true;


            return false;
        }

        public function isMemberIDBanned($id){
            global $connection;
            connectToDB();
            $this->clearResult();
            $sql = "SELECT MEMBER_ID FROM Bans ";
            $sql .= "WHERE MEMBER_ID =".$id." AND (FEE <> REFUNDED OR FEE = 0);";
            $result = $connection->query($sql);
            if($result->num_rows > 0){
                return true;
            }
            return false;
        }

        public function banMember(){
            $rule = new Rule();
            $rule->getRules();
            global $connection;
            connectToDB();
            $members = new Member();
            if($this->isMemberIDBanned($_POST['memberID']) || !$members->checkIfMemberExists($_POST['memberID']) 
                    || !$this->isBannable($_POST['memberID'], $_POST['gameID'])  ){
                return false;
            }

            $banPeriodDays = ((int)$rule->getBanPeriod() * 4)*7;
            $bannedDate = new DateTime(date("Y/m/d"));
            $unbanDate = addDaysToDate($bannedDate, $banPeriodDays);

            $sql = "INSERT INTO Bans (GAME_ID, MEMBER_ID, FEE, REFUNDED, INFO, UNBAN_DATE) ";
            $sql .= "VALUES (".$_POST['gameID'].","
                .$_POST['memberID'].",".$_POST['fee'].",0,\"".$_POST['info']."\",".QUOTE."Until Refunded".QUOTE.");";


            if(!$connection->query($sql)){
                $connection->close();
                return false;
            }
            
            $connection->close();
            return true;
        }

        public function getAllBanned(){
            global $connection;
            connectToDB();
            $this->clearResult();

            $sql = "SELECT * FROM Bans;";
            $result = $connection->query($sql);
            $this->setResult($result);

            $connection->close();
        }

        public function getReportID(){
            return $this->reportID;
        }
        public function getGameID(){
            return $this->gameID;
        }
        public function getMemberID(){
            return $this->memberID;
        }
        public function getFee(){
            return $this->fee;
        }
        public function getRefunded(){
            return $this->refunded;
        }
        public function getInfo(){
            return $this->info;
        }

        public function getUnbanDate(){
            return $this->unban;
        }
    }
?>