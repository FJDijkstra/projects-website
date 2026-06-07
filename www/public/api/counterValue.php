<?php
require_once __DIR__ . '/../../app/model/Counter.php';
require_once __DIR__ . '/../../app/core/Database.php';
require_once __DIR__ . '/../../app/config/Config.php';


use MyApp\Counter;


$counterid = isset($_GET['counter']) ? (int) $_GET['counter'] : 0;
if ($counterid > 0) { 
    $counter = Counter::getById($counterid);
    if ($counter) {
        echo $counter->value;
    } else {
        echo "Counter not found";
    }
} else {
    echo "Invalid counter ID";
}
?>