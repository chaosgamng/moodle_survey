<?php
require_once('conn.php');
class CourseDAO
{

    private $pdo;
    // this creates the connection to the database.
    function __construct($conn)
    {
        $this->pdo = $conn->get_connection();
    }

    /**
     * This function gets all the courses from the database. - TB
     * @return array course records
     */
    function getAllCourses()
    {
        try {
            $sql = "SELECT * FROM course";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch (PDOException $e) {
            echo "Get all courses error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * This function gets a course by its ID. - TB
     * @param int $id
     * @return array course record
     */
    function getCourse($course_id)
    {
        try {
            $sql = "SELECT * FROM course WHERE course_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$course_id]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return $res;
        } catch (PDOException $e) {
            echo "Get course error: " . $e->getMessage();
            return false;
        }
    }

    /** 
     * This function gets all the courses by professor_id. - TB
     * @param int $professor_id
     * @return array course records
     */
    function getCoursesByProfessor($professor_id)
    {
        try {
            $sql = "SELECT * FROM course WHERE professor_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$professor_id]);
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //dispaly the results
            foreach ($res as $row) {
                echo $row['course_id'] . " " . $row['name'] . " " . $row['department'] . " " . $row['professor_id'] . "<br>";
            }
            return $res;
        } catch (PDOException $e) {
            echo "Get courses by professor error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * This function adds a course to the database. - TB
     * @param string $course_name
     * @param string $department
     * @param int $professor_id
     * @return boolean
     */
    function insertCourse($course_name, $department, $professor_id)
    {
        try {
            $sql = "INSERT INTO course (name, department, professor_id) VALUES (?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $arr = array($course_name, $department, $professor_id);
            $stmt->execute($arr);
            return true;
        } catch (PDOException $e) {
            echo "Insert course error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * This function deletes a course from the database. - TB
     * @param int $course_id
     * @return boolean
     */
    function deleteCourse($course_id)
    {
        try {
            $sql = "DELETE FROM course WHERE course_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$course_id]);
            return true;
        } catch (PDOException $e) {
            echo "Delete course error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * This function updates a course in the database. - TB
     * @param int $course_id
     * @param string $course_name
     * @param string $department 
     * @param int $professor_id
     * @return boolean
     */
    function updateCourse($course_id, $course_name, $department, $professor_id)
    {
        try {
            $sql = "UPDATE course SET name = ?, department = ?, professor_id = ? WHERE course_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $arr = array($course_name, $department, $professor_id, $course_id);
            $stmt->execute($arr);
            return true;
        } catch (PDOException $e) {
            echo "Update course error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * This function gets all the courses enrolled by a specific user. - TB
     * @param int $user_id
     * @return array list of courses
     */
    function getCoursesByUserId($user_id)
    {
        try {
            $sql = "SELECT c.* FROM course c JOIN enrollment e ON c.course_id = e.course_id WHERE e.student = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$user_id]);
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $courses;
        } catch (PDOException $e) {
            echo "Get courses by user error: " . $e->getMessage();
            return false;
        }
    }
}
