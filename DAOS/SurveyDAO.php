<?php
require_once('conn.php');
class SurveyDAO {
    private $pdo; // PDO connection

    public function __construct($conn){
        $this->pdo = $conn->get_connection();
    }
    /**
     * This function creates a survey.
     * @param string $title
     * @param string $description
     * @param int $creator_id
     * @param int $course_id
     * @param string $status
     */
    public function createSurvey($title, $description, $creator_id, $course_id, $status) {
        try {
            $sql = "INSERT INTO survey (title, description, creator_id, course_id, status) VALUES ( ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([ $title, $description, $creator_id, $course_id, $status]);
        } catch(Exception $e){
            echo "create survey error: " .$e->getMessage();
            
        }
    }
    /**
     * This function retrieves a survey.
     * @param $survey_id
     * @return array survey record
     * 
     */
    public function getSurvey($survey_id) {
        try{
            $sql = "SELECT * FROM survey WHERE survey_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$survey_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(Exception $e){
            echo "get survey error: " .$e->getMessage();
        }
    }
    /**
     * This function updates a survey.
     * @param int $survey_id
     * @param string $title
     * @param string $description
     * @param string $status
     * 
     */
    public function updateSurvey($survey_id, $title, $description, $status) {
        try{
            $sql = "UPDATE survey SET title = :title, description = :desc, status = :stat WHERE survey_id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':desc', $description);
            $stmt->bindParam(':stat', $status);
            $stmt->bindParam(':id', $survey_id);

            $stmt->execute();
        } catch(Exception $e){
            echo "update survey error: " .$e->getMessage();
        }
    }
    /**
     * This function deletes a survey.
     * @param $survey_id
     * 
     */
    public function deleteSurvey($survey_id) {
        try{
            $sql = "DELETE FROM survey WHERE survey_id = :survey";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':survey', $survey_id);
            $stmt->execute();
        } catch(Exception $e){
            echo "delete survey error: " .$e->getMessage();
        }
    }
    /**
     * This function retrieves all surveys.
     * @return array survey records
     */
    public function getAllSurveys() {
        try{
            $sql = "SELECT * FROM survey";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e){
            echo "get all surveys error: " .$e->getMessage();
        }
    }
    /**
     * This function retrieves all of the surveys made by one creator.
     * @param int $creator_id
     * @return array survey records
     */
    public function getSurveysByCreator($creator_id) {
        try{
            $sql = "SELECT * FROM survey WHERE creator_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$creator_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e){
            echo "get survey by creator error: " .$e->getMessage();
        }
    }
    /**
     * This function retrieves all of the surveys assigned to a course.
     * @param int $course_id
     * @return array survey records
     */
    public function getSurveysByCourse($course_id) {
        try{
            $sql = "SELECT * FROM survey WHERE course_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$course_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e){
            echo "get survey by course error: " .$e->getMessage();
        }
    }
}
?>
