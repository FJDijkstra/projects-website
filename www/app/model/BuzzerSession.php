<?php
namespace MyApp;
use MyApp\Database;

class BuzzerSession {
    public $id;

    public $name;

    public $password;

    function __construct($id, $name, $password) {
        $this->id = $id;
        $this->name = $name;
        $this->password = $password;
    } 

    public static function getById(int $id) {
        Database::instance()->storeQuery('SELECT * FROM `buzzer_sessions` WHERE id = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $session_data = $stmt->get_result()->fetch_assoc();

        if ($session_data) {
            return new BuzzerSession(
                $session_data['id'],
                $session_data['name'],
                $session_data['password']
            );
        }

        return NULL;

    }

    public static function getBuzzerSessions() {
        Database::instance()->storeQuery('SELECT * FROM `buzzer_sessions`');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
        $result = $stmt->get_result();

        $sessions = [];
        while ($session_data = $result->fetch_assoc()) {
            $sessions[] = new BuzzerSession(
                $session_data['id'],
                $session_data['name'],
                $session_data['password']
            );
        }

        return $sessions;
    }

    public static function createNew(string $name, string $password) {
        Database::instance()->storeQuery("INSERT INTO `buzzer_sessions` (name, password) VALUES ('$name', '$password')");
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
        if ($stmt->insert_id) {
            return BuzzerSession::getById($stmt->insert_id);
        }

        return null;
    }

    public static function deleteBuzzerSession(int $id) {
        Database::instance()->storeQuery('DELETE FROM buzzer_sessions WHERE id = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    public static function deleteAllBuzzerSessions() {
        Database::instance()->storeQuery('DELETE FROM buzzer_sessions');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
    }

    public static function checkPassword(int $id, string $password) {
        $session = BuzzerSession::getById($id);
        if ($session) {
            return $session->password === $password;
        }
    }

}
?>