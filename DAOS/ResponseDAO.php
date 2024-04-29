<?php
require_once 'conn.php';
class ResponseDAO
{
    private $pdo;

    /**
     * Constructs a new ResponseDAO instance, creating the database connection.
     *
     * @param conn $conn The connection object for the database.
     */
    function __construct($conn)
    {
        $this->pdo = $conn->get_connection();
    }
    // add all code here

    /**
     * Inserts a new response into the database.
     *
     * This method takes a Response object containing the question ID, user ID, and the text of the response,
     * and inserts it into the response table. Returns true if the insertion is successful, false otherwise.
     *
     * @param Response $response The Response object to be inserted into the database.
     * @return bool True if the insertion is successful, false otherwise.
     */
    function insertResponse($question_id, $user_id, $text, $rating = null)
    {
        try {
            // Check if the user has already responded to this question
            $sql = "SELECT * FROM response WHERE question_id = ? AND user_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$question_id, $user_id]);

            if ($stmt->rowCount() > 0) {
                $response_id = $stmt->fetch(PDO::FETCH_ASSOC)['response_id'];
                $this->updateResponse($response_id, $question_id, $user_id, $text, $rating);
                return true;
            }

            $sql = "INSERT INTO response (question_id, user_id, text, rating) VALUES (?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            if ($stmt->execute([$question_id, $user_id, $text, $rating])) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "create response error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Retrieves the response associated with the given response ID.
     * 
     * This method queries the database for the response that matches the specified response ID.
     * Returns the response if it exists, null otherwise.
     *
     * @param int $response_id The ID of the response to retrieve.
     * @return mixed The response if it exists, null otherwise.
     */
    function getResponse($response_id)
    {
        try {
            $sql = "SELECT * FROM response WHERE response_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$response_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row === false) {
                return null;
            } else {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            echo "get response error: " .$e->getMessage();
            return null;
        }
    }

    /**
     * Retrieves all responses from the database.
     *
     * This method queries the database for all responses.
     * Returns an array of all responses if they exist, an empty array otherwise.
     * @return array An array of all responses if they exist, an empty array otherwise.
     */
    function getAllResponses()
    {
        try {
            $sql = "SELECT * FROM response";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                return [];
            } else {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
        } catch (Exception $e) {
            echo "get all response error: " .$e->getMessage();
            return [];
        }
    }

    /**
     * Retrieves all responses associated with a given question ID.
     *
     * Queries the database for responses that match the specified question ID.
     * Returns an array of responses if they exist, an empty array otherwise.
     * 
     * @param int $question_id The ID of the question to retrieve responses for.
     * @return array An array of responses if they exist, an empty array otherwise.
     */
    function getResponsesByQuestion($question_id)
    {
        try {
            $sql = "SELECT * FROM response WHERE question_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$question_id]);
            $responses = [];

            if ($stmt->rowCount() == 0) {
                return [];
            } else {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            echo "get response by question error: " .$e->getMessage();
            return [];
        }
    }


    /**
     * Retrieves all responses associated with a given user ID.
     *
     * Queries the database for responses that match the specified user ID.
     * Returns an array of responses if they exist, an empty array otherwise.
     * 
     * @param int $user_id The ID of the user to retrieve responses for.
     * @return array An array of responses if they exist, an empty array otherwise.
     */
    function getResponsesByUser($user_id)
    {
        try {
            $sql = "SELECT * FROM response WHERE user_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$user_id]);

            if ($stmt->rowCount() == 0) {
                return [];
            } else {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            echo "get response by user error: " .$e->getMessage();
            return [];
        }
    }


    /**
     * Retrieves all responses from a specific user for a specific question.
     *
     * This method queries the response table in the database for entries that match
     * both the specified user ID and question ID. Returns an array of responses if they exist,
     *
     * @param int $user_id The ID of the user whose responses are being retrieved.
     * @param int $question_id The ID of the question for which responses are being retrieved.
     * @return array An array of responses if they exist, an empty array otherwise.
     */
    function getResponseByUserQuestion($user_id, $question_id)
    {
        try {
            $sql = "SELECT * FROM response WHERE user_id = ? AND question_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$user_id, $question_id]);

            if ($stmt->rowCount() == 0) {
                return [];
            } else {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            echo "get response by user question error: " .$e->getMessage();
            return [];
        }
    }


    /**
     * Updates the response in the database with the new values from the given Response object.
     *
     * This method updates the response in the database with the new values from the given Response object. Returns true if the update is successful, false otherwise.
     * 
     * @param Response $response The Response object containing the new values for the response.
     * @return bool 
     */
    function updateResponse($response_id, $question_id, $user_id, $text)
    {
        try {
            $sql = "UPDATE response SET question_id = ?, user_id = ?, text = ? WHERE response_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$question_id, $user_id, $text, $response_id]);
            return $result;
        } catch (Exception $e) {
            echo "update response error: " .$e->getMessage();
            return false;
        }
    }


    /**
     * Deletes a response from the database.
     *
     * This method deletes a response from the database. Returns true if the deletion is successful, false otherwise.
     * 
     * @param int $response_id The ID of the response to delete.
     * @return bool True if the deletion is successful, false otherwise.
     */
    function deleteResponse($response_id)
    {
        try {
            $sql = "DELETE FROM response WHERE response_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$response_id]);
            return $result;
        } catch (Exception $e) {
            echo "delete response error: " .$e->getMessage();
            return false;
        }
    }

    // end of code block
}