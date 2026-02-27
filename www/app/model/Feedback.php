<?php
namespace MyApp;
use MyApp\Database;

class Feedback {
    public $id;
    public $feedback;
    public $timestamp;

    function __construct($id, $feedback, $timestamp) {
        $this->id = $id;
        $this->feedback = $feedback;
        $this->timestamp = $timestamp;
    } 

    public static function getById(int $id) {
        Database::instance()->storeQuery('SELECT * FROM feedback WHERE id = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $feedback_data = $stmt->get_result()->fetch_assoc();

        if ($feedback_data) {
            return new Team(
                $feedback_data['id'],
                $feedback_data['feedback'],
                $feedback_data['timestamp']
            );
        }

        return NULL;

    }

    public static function createNew(string $feedback) {
        if ($feedback == "") {
            ErrorHandler::addError("Geen teamnaam ingevuld");
            return NULL;
        }
        if (strlen($feedback) > 200) {
            ErrorHandler::addError("Feedback is te lang");
            return NULL;
        }
        Database::instance()->storeQuery("INSERT INTO `feedback` (feedback) VALUES ('$feedback')");
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
        if ($stmt->insert_id) {
            return Team::getById($stmt->insert_id);
        }

        return null;
    }

    public static function getFeedback(): array {
        Database::instance()->storeQuery('SELECT * FROM feedback');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
        $result = $stmt->get_result();

        $feedback = [];
        while ($feedback_data = $result->fetch_assoc()) {
            $feedback[$feedback_data['id']] = new Feedback(
                $feedback_data['id'],
                $feedback_data['feedback'],
                $feedback_data['timestamp']
            );
        }

        return $feedback;

    }

}
?>