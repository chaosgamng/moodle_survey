<?php
require_once('conn.php');
class logs
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
    function getlog($course)
    {
        try {
            $sql = "SELECT * FROM mdl_logstore_standard_log WHERE courseid = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$course]);
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch (PDOException $e) {
            echo "Get all courses error: " . $e->getMessage();
            return false;
        }
    }

}
?>