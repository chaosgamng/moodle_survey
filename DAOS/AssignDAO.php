<?php
    require_once('conn.php');
    class AssignDAO {
        private $pdo;
        // this creates the connection to the database.
        public function __construct($conn){
            $this->pdo = $conn->get_connection();
        }
        /**
         * This function assigns a student to a survey by using the ID of the survey and the ID of the student.
         * @param int $survey_id
         * @param int $user_id
         * 
         */
        function insertAssign($survey_id, $user_id){
            try{
                $sql = "INSERT INTO mdl_assign (survey_id, student_id) VALUES (:survey, :user)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':survey', $survey_id);
                $stmt->bindParam(':user', $user_id);
                $stmt->execute();
            }catch(Exception $e){
                echo "insert assign error" .$e->getMessage();;
            }
        }
        /**
         * This function retrieves all of the records from the assign table with the user ID.
         * @param $user_id
         * @return array user records
         * 
         */
        // read function - CT
        function selectAssign($user_id){
            try{
                $sql = "SELECT * FROM mdl_assign WHERE student_id = :user";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':user', $user_id);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }catch(Exception $e){
                echo "select assign error" .$e->getMessage();
            }
        }
        /**
         * This function removes a student from a survey.
         * @param int $survey_id
         * @param int $user_id
         * @return boolean
         * 
         */
        // delete function - MG
        function deleteAssign($survey_id, $user_id){
        try{
            // Query SQL DELETE Statement:
            $sql = "DELETE FROM  mdl_assign  WHERE student_id = :user AND survey_id = :survey"; 
            // Sets up the DELETE statement to be "prepared" (Pun-unintended, lmao).
            $stmt = $this->pdo->prepare($sql); 
            $stmt->bindValue(':user', $user_id);
            $stmt->bindValue(':survey',$survey_id);
            // The prepared DELETE statement is executed once ready.
            $stmt->execute(); 
            return true; 
        }catch(Exception $e){
            // If something goes wrong with deleting question, then give the error message.
            echo "delete assign error" .$e->getMessage();
            return false;
        }
    }
    
    }
?>
