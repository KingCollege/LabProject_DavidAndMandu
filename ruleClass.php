<?php 
    class Rule{
        var $ruleID;
        var $rentPeriod;
        var $extensionPerRent;
        var $extensionPeriod;
        var $violationPeriod;
        var $rentNumber;
        var $banPeriod;
        var $numberOfViolation;
        
        private function clearResult(){
            $this->ruleID = NULL;
            $this->rentPeriod= NULL;
            $this->extensionPerRent= NULL;
            $this->extensionPeriod= NULL;
            $this->violationPeriod= NULL;
            $this->rentNumber= NULL;
            $this->banPeriod= NULL;
            $this->numberOfViolation= NULL;
        }

        public function changeRule(){
            global $connection;
            connectToDB();

            $sql = "UPDATE Rules SET RENT_PERIOD=".$_POST['rentP'].", EXTENSIONS_PER_RENT=".$_POST['extensionNo'].", NUMBER_VIOLATION_PERIOD=";
            $sql .= $_POST['violP'].", TOTAL_RENTS= ".$_POST['rentNo'].", BAN_PERIOD= ".$_POST['banP'].", NUMBER_VIOLATION=".$_POST['violNo'].";";

            $connection->query($sql);

            $connection->close();
        }

        public function getRules(){
            global $connection;
            connectToDB();

            $sql="SELECT * FROM Rules;";

            $result = $connection->query($sql);

            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $this->ruleID = $row['RULE'];
                    $this->rentPeriod= $row['RENT_PERIOD'];
                    $this->extensionPerRent= $row['EXTENSIONS_PER_RENT'];
                    $this->extensionPeriod= $row['EXTENSIONS_PERIOD'];
                    $this->violationPeriod= $row['NUMBER_VIOLATION_PERIOD'];
                    $this->rentNumber= $row['TOTAL_RENTS'];
                    $this->banPeriod= $row['BAN_PERIOD'];
                    $this->numberOfViolation= $row['NUMBER_VIOLATION'];
                }
            }   
            $connection->close();
        }

        public function getRuleID(){
            return $this->ruleID;
        }
        public function getRentPeriod(){
            return $this->rentPeriod;
        }
        public function getExtensionPerRent(){
            return $this->extensionPerRent;
        }
        public function getExtensionPeriod(){
            return $this->extensionPeriod;
        }
        public function getTotalRent(){
            return $this->rentNumber;
        }
        public function getBanPeriod(){
            return $this->banPeriod;
        }
        public function getNumberOfViolation(){
            return $this->numberOfViolation;
        }
        public function getViolationPeriod(){
            return $this->violationPeriod;
        }
    }
?>