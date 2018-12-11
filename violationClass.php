<?php 
    require_once("ruleClass.php");
    class Violation{
        var $violationID;
        var $memberID;
        var $numberOfViolation;
        var $initialViolationDate;

        public function clearViolations(){
            $rules = new Rule;
            $rules->getRules();
            global $connection;
            connectToDB();
            $today = new DateTime(date("Y/m/d"));
            $sql = "SELECT INI_VIOLATION_DATE FROM Violations;";
            $violationPeriodDays = ((int)$rules->getViolationPeriod() * 4) * 7;
            $result = $connection->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $temp = new DateTime($row['INI_VIOLATION_DATE']);
                    $clearViolationsDate = addDaysToDate(new DateTime($row['INI_VIOLATION_DATE']), $violationPeriodDays);
                    if($today >= $clearViolationsDate){
                        $sql ="DELETE FROM Violations WHERE INI_VIOLATION_DATE =".QUOTE.date_format($temp, "Y/m/d").QUOTE.";";
                        $connection->query($sql);
                    }
                }
            }//

        }

        private function autoBanMemberExceedViolations(){
            //if member is banned, remove them from violations as they have been punished already
            $rules = new Rule();
            $rules->getRules();
            global $connection;
            connectToDB();
            $sql = "SELECT MEMBER_ID,NUMBER_OF_VIOLATION FROM Violations;";
            $result = $connection->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $numberOfViolation = (int)$row['NUMBER_OF_VIOLATION'];
                    $numberOfViolationAllowed = (int)$rules->getNumberOfViolation();
                    if($numberOfViolation >= $numberOfViolationAllowed){
                        $ban = new Ban();
                        $ban->banMemberIDForViolations($row['MEMBER_ID']);
                        $sql ="DELETE FROM Violations WHERE MEMBER_ID =".$row['MEMBER_ID'] .";";
                        $connection->query($sql);
                    }
                }   
            }//
        }

        public function addViolations($memberID){
            $todaysDate = new DateTime(date("Y/m/d"));
            global $connection;
            connectToDB();
            $sql = "SELECT * FROM Violations WHERE MEMBER_ID=".$memberID.";";
            $result = $connection->query($sql);
            if($result->num_rows > 0){
                $sql = "UPDATE Violations SET NUMBER_OF_VIOLATION = NUMBER_OF_VIOLATION + 1;";
                $connection->query($sql);
            }//else add a new entry to database
            else{
                $sql = "INSERT INTO Violations (MEMBER_ID, NUMBER_OF_VIOLATION, INI_VIOLATION_DATE)";
                $sql .= " VALUES (".$memberID.",1,".QUOTE.date_format($todaysDate, "Y/m/d").QUOTE.");";
                $connection->query($sql);
            }
            $this->autoBanMemberExceedViolations();
        }//

    }
?>