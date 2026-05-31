<?php
namespace MyApp;
use MyApp\Database;

class Counter {
    public $id;
    public $name;
    public $amount;

    function __construct($id, $name, $amount) {
        $this->id = $id;
        $this->name = $name;
        $this->amount = $amount;
    } 

    public static function getById(int $id) {
        Database::instance()->storeQuery('SELECT * FROM counters WHERE id = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $counter_data = $stmt->get_result()->fetch_assoc();

        if ($counter_data) {
            return new Counter(
                $counter_data['id'],
                $counter_data['name'],
                $counter_data['amount']
            );
        }

        return NULL;

    }

    public static function createNew(string $name) {
        if ($name == "") {
            ErrorHandler::addError("Geen naam ingevuld");
            return NULL;
        }
        if (strlen($name) > 20) {
            ErrorHandler::addError("Leuk geprobeerd, maar helaas");
            return NULL;
        }
        $counters = Counter::getCounters();
        foreach ($counters as $counter) {
            if ($counter->name == $name) {
                ErrorHandler::addError("Deze counter bestaat al");
                return NULL;
            }
        }
        Database::instance()->storeQuery("INSERT INTO `counters` (name, amount) VALUES ('$name', 0)");
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
        if ($stmt->insert_id) {
            return Counter::getById($stmt->insert_id);
        }

        return null;
    }

    public static function getCounters(): array {
        Database::instance()->storeQuery('SELECT * FROM counters');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
        $result = $stmt->get_result();

        $counters = [];
        while ($counter_data = $result->fetch_assoc()) {
            $counters[$counter_data['id']] = new Counter(
                $counter_data['id'],
                $counter_data['name'],
                $counter_data['amount']
            );
        }

        return $counters;
    }

    public static function incrementCounter(int $id, int $increment= 1) {
        Database::instance()->storeQuery('UPDATE counters SET amount = amount + ? WHERE id = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param(
            'ii',
            $increment,
            $id
        );
        $stmt->execute();

        return Counter::getById($id);
    }

    public static function decrementCounter(int $id, int $decrement= 1) {
        Database::instance()->storeQuery('UPDATE counters SET amount = amount - ? WHERE id = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param(
            'ii',
            $decrement,
            $id
        );
        $stmt->execute();

        return Counter::getById($id);
    }

    public static function deleteCounter(int $id) {
        Buzz::deleteBuzzesFromTeam($id);
        Database::instance()->storeQuery('DELETE FROM counters WHERE id = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    public static function deleteAllCounters() {
        Database::instance()->storeQuery('DELETE FROM counters');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
    }

    public static function clearAmounts() {
        Database::instance()->storeQuery('UPDATE counters SET amount = 0');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
    }

}
?>