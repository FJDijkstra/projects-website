<?php
namespace MyApp;
use MyApp\Database;

class Buzz {
    public $id;

    public $team;

    public $seen;

    public $timestamp;

    function __construct($id, $team, $seen, $timestamp) {
        $this->id = $id;
        $this->team = $team;
        $this->seen = $seen;
        $this->timestamp = $timestamp;
    } 

    public static function getById(int $id) {
        Database::instance()->storeQuery('SELECT buzz.id, teams.name, buzz.seen, buzz.timestamp FROM buzz LEFT JOIN teams ON buzz.team=teams.id WHERE buzz.id = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $buzz_data = $stmt->get_result()->fetch_assoc();

        if ($buzz_data) {
            return new Buzz(
                $buzz_data['id'],
                $buzz_data['name'],
                $buzz_data['seen'],
                $buzz_data['timestamp']
            );
        }

        return NULL;

    }

    public static function createNew(int $team) {
        Database::instance()->storeQuery("INSERT INTO `buzz` (team) VALUES ($team)");
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
        if ($stmt->insert_id) {
            return Team::getById($stmt->insert_id);
        }

        return null;
    }

    public static function getBuzzes(): array {
        Database::instance()->storeQuery('SELECT buzz.id, teams.name, buzz.seen, buzz.timestamp FROM buzz LEFT JOIN teams ON buzz.team=teams.id ORDER BY timestamp ASC');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
        $result = $stmt->get_result();

        $buzzes = [];
        while ($buzz_data = $result->fetch_assoc()) {
            $buzzes[$buzz_data['id']] = new Buzz(
                $buzz_data['id'],
                $buzz_data['name'],
                $buzz_data['seen'],
                $buzz_data['timestamp']
            );
        }

        return $buzzes;

    }

    public static function getBuzzesBySession(int $session_id) {
        Database::instance()->storeQuery('SELECT buzz.id, teams.name, buzz.seen, buzz.timestamp FROM buzz LEFT JOIN teams ON buzz.team=teams.id WHERE teams.buzzer_session = ? ORDER BY timestamp ASC');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param('i', $session_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $buzzes = [];
        while ($buzz_data = $result->fetch_assoc()) {
            $buzzes[$buzz_data['id']] = new Buzz(
                $buzz_data['id'],
                $buzz_data['name'],
                $buzz_data['seen'],
                $buzz_data['timestamp']
            );
        }

        return $buzzes;

    }

    public static function setSeen(int $id) {
        Database::instance()->storeQuery('UPDATE buzz SET seen = 1 WHERE id = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    public static function deleteBuzz(int $id) {
        Database::instance()->storeQuery('DELETE FROM buzz WHERE id = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    public static function deleteBuzzesFromTeam(int $teamId) {
        Database::instance()->storeQuery('DELETE FROM buzz WHERE team = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param('i', $teamId);
        $stmt->execute();
    }

    public static function deleteAllBuzz() {
        Database::instance()->storeQuery('DELETE FROM buzz');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
    }


}
?>