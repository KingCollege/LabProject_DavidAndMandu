<?php
    class Stock{
        var $gameID = array();
        var $gameName  = array();
        var $rented  = array();
        var $releaseYear = array();
        var $rating = array();
        var $platform = array();
        var $genre = array();
        var $media = array();
        var $description = array();
        var $source = array();
        var $image = array();
        var $outlet = array();

        private function clearResult(){
            $this->gameID = array();
            $this->gameName =array();
            $this->rented =array();
            $this->releaseYear = array();
            $this->rating = array();
            $this->platform = array();
            $this->genre = array();
            $this->media = array();
            $this->description = array();
            $this->source = array();
            $this->image = array();
            $this->outlet = array();
        }

        private function setResult($result){
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $this->gameID[] = $row['GAME_ID'];
                    $this->gameName[] = $row['GAME_NAME'];
                    $this->rented[] = $row['RENTED'];
                    $this->releaseYear[] = $row['RELEASE_YEAR'];
                    $this->rating[] = $row['RATING'];
                    $this->platform[] = $row['PLATFORM'];
                    $this->genre[] = $row['GENRE'];
                    $this->media[] = $row['MEDIA_TYPE'];
                    $this->description[] = $row['GAME_DESCRIPTION'];
                    $this->source[] = $row['REVIEW_SOURCE'];
                    $this->image[] = $row['IMAGE_LINK'];
                    $this->outlet[] = $row['OUTLET'];
                }
            }
        }

        public function getAllAvailableGames(){
            global $connection;
            $this->clearResult();
            connectToDB();
            $sql = "SELECT * FROM Games";
            $sql .= " WHERE RENTED = 0 AND GAME_ID <> -1;";
            $result = $connection->query($sql);
            $this->setResult($result);
            $connection->close();
        }


        public function getAllUnAvailableGames(){
            global $connection;
            $this->clearResult();
            connectToDB();
            $sql = "SELECT * FROM Games";
            $sql .= " WHERE RENTED <> 0;";
            $result = $connection->query($sql);
            $this->setResult($result);
            $connection->close();
        }

        public function getAllAvailableUniqueGames(){
            global $connection;
            $this->clearResult();
            connectToDB();
            $sql = "SELECT DISTINCT GAME_NAME FROM Games WHERE RENTED = 0 AND GAME_ID <> -1";
            $sql .= " ORDER BY GAME_NAME;";
            $result = $connection->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $this->gameName[] = $row['GAME_NAME'];
                }
            }
            $connection->close();
        }

        public function getAllPlatform(){
            global $connection;
            connectTODB();

            $sql = "SELECT DISTINCT PLATFORM FROM Games WHERE PLATFORM IS NOT NULL;";
            $result = $connection->query($sql);
            $platforms = array();
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $platforms[] = $row['PLATFORM'];
                }
            }
            $connection->close();
            return $platforms;
        }

        public function getAllMediaTypes(){
            global $connection;
            connectTODB();

            $sql = "SELECT DISTINCT MEDIA_TYPE FROM Games WHERE MEDIA_TYPE IS NOT NULL;";
            $result = $connection->query($sql);
            $medias = array();
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $medias[] = $row['MEDIA_TYPE'];
                }
            }
            $connection->close();
            return $medias;
        }

        public function getAllGames(){
            global $connection;
            $this->clearResult();
            connectToDB();
            $sql = "SELECT * FROM Games WHERE GAME_ID <> -1;";
            $result = $connection->query($sql);
            $this->setResult($result);
            $connection->close();
        }

        public function addGame(){
            global $connection;
            $this->clearResult();
            connectToDB();
            $this->gameName = $_POST['gameName'] ?? NULL;
            $this->rented = 0;
            $this->releaseYear =$_POST['release']  ?? NULL;
            $this->rating = $_POST['rating'] ?? NULL;
            $this->platform = $_POST['platform'] ?? NULL;
            $this->genre = $_POST['genre'] ?? NULL;
            $this->media = $_POST['media'] ?? NULL;
            $this->description = $_POST['des'] ?? NULL;
            $this->source = $_POST['source'] ?? NULL;
            $this->image = $_POST['img'] ?? NULL;
            $this->outlet = $_POST['outlet'] ?? NULL;
            $sql = "INSERT INTO Games (GAME_NAME, RENTED, RELEASE_YEAR, RATING, PLATFORM, GENRE, MEDIA_TYPE, GAME_DESCRIPTION, REVIEW_SOURCE, IMAGE_LINK, OUTLET)";
            $sql .=" VALUES (\"".$this->gameName."\",".$this->rented.",".$this->releaseYear.",".$this->rating.",\"".$this->platform."\",\"".$this->genre."\",\"";
            $sql .= $this->media."\",\"".$this->description."\",\"".$this->source."\",\"".$this->image."\",\"".$this->outlet."\");";

            if($connection->query($sql) === TRUE){
            }
            else{
                echo "PROBLEM WITH QUERY";
            }
            $connection->close();
        }

        public function getGameID(){
            return $this->gameID;
        }
        public function getGameName(){
            return $this->gameName;
        }
        public function getRented(){
            return $this->rented;
        }
        public function getReleaseYear(){
            return $this->releaseYear;
        }
        public function getRating(){
            return $this->rating;
        }
        public function getPlatform(){
            return $this->platform;
        }
        public function getGenre(){
            return $this->genre;
        }
        public function getMedia(){
            return $this->media;
        }
        public function getDescription(){
            return $this->description;
        }
        public function getSource(){
            return $this->source;
        }
        public function getImage(){
            return $this->image;
        }

        public function getOutlet(){
            return $this->outlet;
        }
    }
?>