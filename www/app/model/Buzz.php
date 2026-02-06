<?php
namespace MyApp;
use MyApp\Database;

class Buzz {
    public $id;
    public $team;

    public $timestamp;

    function __construct($id, $team, $timestamp) {
        $this->id = $id;
        $this->team = $team;
        $this->timestamp = $timestamp;
    } 

    public static function getById(int $id) {
        Database::instance()->storeQuery('SELECT buzz.id, teams.name, buzz.timestamp FROM buzz LEFT JOIN teams ON buzz.team=teams.id WHERE buzz.id = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $team_data = $stmt->get_result()->fetch_assoc();

        if ($team_data) {
            return new Buzz(
                $team_data['id'],
                $team_data['name'],
                $team_data['timestamp']
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
        Database::instance()->storeQuery('SELECT buzz.id, teams.name, buzz.timestamp FROM buzz LEFT JOIN teams ON buzz.team=teams.id ORDER BY timestamp DESC');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
        $result = $stmt->get_result();

        $buzzes = [];
        while ($buzz_data = $result->fetch_assoc()) {
            $buzzes[$buzz_data['id']] = new Buzz(
                $buzz_data['id'],
                $buzz_data['name'],
                $buzz_data['timestamp']
            );
        }

        return $buzzes;

    }

    public static function deleteBuzz(int $id) {
        Database::instance()->storeQuery('DELETE FROM buzz WHERE id = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    public static function deleteAllBuzz() {
        Database::instance()->storeQuery('DELETE FROM buzz');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
    }


}
?>