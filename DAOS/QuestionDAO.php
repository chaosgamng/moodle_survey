<?php
    require_once('conn.php');
    class QuestionDAO {
        private $pdo;
        // this creates the connection to the database
        public function __construct($conn){
            $this->pdo = $conn->get_connection();
        }
    
        // add all code here
        /**
         * This function inserts a question into a survey.
         * @param string $text
         * @param int $survey_id
         */
        // create function - CT 
        function insertQuestion($text, $survey_id){
            try{
                $sql = "INSERT INTO question (text, survey_id) VALUES (?, ?)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([ $text, $survey_id]);
            }catch(Exception $e){
                echo "insert question error: " .$e->getMessage();
                
            }
        }
        /**
         * This function retrieves a question.
         * @param int $question_id
         * @return array question record
         */
        // read function - CT
        function selectQuestion($question_id=null){
            try{
                if($question_id){
                    $sql = "SELECT * FROM question WHERE question_id = ?";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$question_id]);
                    return $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $sql = "SELECT * FROM question";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute();
                    return $stmt->fetchall(PDO::FETCH_ASSOC);
                }
            }catch(Exception $e){
                echo "select question error: " .$e->getMessage();
            }
        }

        function selectSurveyQuestion($survey){
            try{
                if($survey){
                    $sql = "SELECT * FROM question WHERE survey_id = ?";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$survey]);
                    return $stmt->fetchall(PDO::FETCH_ASSOC);
                }
            }catch(Exception $e){
                echo "select question error: " .$e->getMessage();
            }
        }
        // update function - MM
        /**
         * This function updates a question.
         * @param string $text
         * @param int $question_id
         * 
         */
        function updateQuestion($text, $question_id){
            try{
                $sql = "UPDATE question SET text = :text WHERE question_id = :question_id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':text',$text);
                $stmt->bindValue(':question_id',$question_id);
                $stmt->execute();
                return true;

            } catch(Exception $e){
                echo "update question error: " .$e->getMessage();
                return false;
            }


        }

        // delete function - MG
        /**
         * This function deletes a question.
         * @param int $question_id
         */
        function deleteQuestion($question_id){
        try{
            // Query SQL DELETE Statement:
            $sql = "DELETE FROM  question  WHERE question_id = :question_id"; 
            // Sets up the DELETE statement to be "prepared" (Pun-unintended, lmao).
            $stmt = $this->pdo->prepare($sql); 
            $stmt->bindValue(':question_id', $question_id);
            // The prepared DELETE statement is executed once ready.
            $stmt->execute(); 
            return true; 
        }catch(Exception $e){
            // If something goes wrong with deleting question, then give the error message.
            echo "delete question error: " .$e->getMessage();
            return false;
        }
    }

        // end of code block
    }
?>