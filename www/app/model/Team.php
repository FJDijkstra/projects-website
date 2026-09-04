<?php
namespace MyApp;
use MyApp\Database;

class Team {
    public $id;
    public $name;
    public $points;

    public $buzzer_session;

    function __construct($id, $name, $points, $buzzer_session) {
        $this->id = $id;
        $this->name = $name;
        $this->points = $points;
        $this->buzzer_session = $buzzer_session;
    }

    public static function getById(int $id) {
        Database::instance()->storeQuery('SELECT * FROM teams WHERE id = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $team_data = $stmt->get_result()->fetch_assoc();

        if ($team_data) {
            return new Team(
                $team_data['id'],
                $team_data['name'],
                $team_data['points'],
                $team_data['buzzer_session']
            );
        }

        return NULL;

    }

    public static function createNew(string $name, int $buzzer_session) {
        if ($name == "") {
            ErrorHandler::addError("Geen teamnaam ingevuld");
            return NULL;
        }
        if (strlen($name) > 20) {
            ErrorHandler::addError("Leuk geprobeerd, maar helaas");
            return NULL;
        }
        $teams = Team::getTeams();
        foreach ($teams as $team) {
            if ($team->name == $name) {
                ErrorHandler::addError("Dit team bestaat al");
                return NULL;
            }
        }
        Database::instance()->storeQuery("INSERT INTO `teams` (name, points, buzzer_session) VALUES ('$name', 0, $buzzer_session)");
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
        if ($stmt->insert_id) {
            return Team::getById($stmt->insert_id);
        }

        return null;
    }

    public static function getTeams(): array {
        Database::instance()->storeQuery('SELECT * FROM teams');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
        $result = $stmt->get_result();

        $teams = [];
        while ($team_data = $result->fetch_assoc()) {
            $teams[$team_data['id']] = new Team(
                $team_data['id'],
                $team_data['name'],
                $team_data['points'],
                $team_data['buzzer_session']
            );
        }

        return $teams;

    }

    public static function getTeamsBySession(int $session_id): array {
        Database::instance()->storeQuery('SELECT * FROM teams WHERE buzzer_session = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param('i', $session_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $teams = [];
        while ($team_data = $result->fetch_assoc()) {
            $teams[$team_data['id']] = new Team(
                $team_data['id'],
                $team_data['name'],
                $team_data['points'],
                $team_data['buzzer_session']
            );
        }

        return $teams;

    }

    public static function setPoints(int $id, int $points) {
        Database::instance()->storeQuery('UPDATE teams SET points = ? WHERE id = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param(
            'ii',
            $points,
            $id
        );
        $stmt->execute();

        return Team::getById($id);
    }

    public static function changePoints(int $id, int $change) {
        $team = Team::getById($id);
        Team::setPoints($id, $team->points + $change);
    }

    public static function deleteTeam(int $id) {
        Buzz::deleteBuzzesFromTeam($id);
        Database::instance()->storeQuery('DELETE FROM teams WHERE id = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    public static function deleteAllTeams() {
        Buzz::deleteAllBuzz();
        Database::instance()->storeQuery('DELETE FROM teams');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
    }

    public static function deleteTeamsBySession(int $session_id) {
        $teams = Team::getTeamsBySession($session_id);
        foreach ($teams as $team) {
            Buzz::deleteBuzzesFromTeam($team->id);
        }
        Database::instance()->storeQuery('DELETE FROM teams WHERE buzzer_session = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param('i', $session_id);
        $stmt->execute();
    }

    public static function clearPoints() {
        Database::instance()->storeQuery('UPDATE teams SET points = 0');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
    }


}
?>