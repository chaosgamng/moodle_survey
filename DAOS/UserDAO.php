<?php
require_once('conn.php');
class UserDAO{
    private $pdo;

    public function __construct($conn){
        $this->pdo = $conn->get_connection();
    }
    /**
     * This function retrieves all user records.
     * @return array records
     * 
     */
    public function getAllUsers(){
        try{
            $sql = "SELECT * FROM user";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            echo "get all users error: " .$e->getMessage();
            return false;
        }
    }
    /**
     * This function updates a user record.
     * @param int $userId
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @param string $role
     * @param string $department
     * @param string $major
     * 
     * 
     */
    public function updateUser($userId, $firstName, $lastName, $email, $password, $role, $department, $major) {
        try {
            $sql = "UPDATE user SET first_name = ?, last_name = ?, email = ?, password = ?, role = ?, department = ?, major = ? WHERE user_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$firstName, $lastName, $email, $password, $role, $department, $major, $userId]);
        }catch (PDOException $e){
            echo "update user error: " .$e->getMessage();
            return false;
        }
    }
    /**
     * This function retrieves a user record.
     * @param int $userId
     * @return array user record
     */
    public function getUser($userId){
        try{
            $sql = "SELECT * FROM user WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e){
            echo "get user error: " .$e->getMessage();
            
            return false;
        }
    }
    /**
     * This function retrieves all of the user records for a course.
     * @param int $courseId
     * @return array records
     */
    public function getUsersByCourse($courseId){
        try{
            $sql = "SELECT u.* FROM user u INNER JOIN enrollment e ON u.user_id = e.student WHERE e.course_id = :courseId";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
            echo "get user by course error: " .$e->getMessage();
            return false; 
        }
    }
    /**
     * This function inserts a user into the user table.
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @param string $role
     * @param string deparment
     * @param string major
     */
    public function insertUser($firstName, $lastName, $email, $password, $role, $department, $major) {
        try {
            $sql = "INSERT INTO user (first_name, last_name, email, password, role, department, major) VALUES (:fname, :lname, :email, :pass, :role, :dep, :major)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':fname', $firstName);
            $stmt->bindValue(':lname', $lastName);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':pass', $password);
            $stmt->bindValue(':role', $role);
            $stmt->bindValue(':dep', $department);
            $stmt->bindValue(':major', $major);
            $res = $stmt->execute();
            if (!$res) {
                throw new Exception("Failed to insert user.");
            }
            return true;
        } catch (PDOException $e) {
            echo "Insert User Error: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * This function deletes a user.
     * @param int $userId
     * @return boolean
     */
    public function deleteUser($userId) {
        try {
            // $sql = "DELETE FROM enrollment WHERE student = :user_id";
            // $stmt = $this->pdo->prepare($sql);
            // $stmt->bindParam(':user_id', $userId);
            // $stmt->execute();


            // $sql = "DELETE FROM assign WHERE student_id = :user_id";
            // $stmt = $this->pdo->prepare($sql);
            // $stmt->bindParam(':user_id', $userId);
            // $stmt->execute();

            // $sql = "DELETE FROM course WHERE professor_id = :user_id";
            // $stmt = $this->pdo->prepare($sql);
            // $stmt->bindParam(':user_id', $userId);
            // $stmt->execute();


            $sql = "DELETE FROM user WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "delete user error: " .$e->getMessage();
            return false;
        }
    }
}
?>