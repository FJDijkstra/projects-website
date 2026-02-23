<?php
require_once __DIR__ . '/../../app/model/Buzz.php';
require_once __DIR__ . '/../../app/model/Admin.php';
require_once __DIR__ . '/../../app/core/Database.php';
require_once __DIR__ . '/../../app/config/Config.php';


use MyApp\Buzz;
use MyApp\Admin;

Admin::keepActive();
$buzzes = Buzz::getBuzzes();
foreach ($buzzes as $buzz) {
    $datetime1 = new DateTime($buzz->timestamp);
    $datetime2 = new DateTime("now");
    $interval = $datetime1->diff($datetime2);
    echo "<li class='m-2 border-bottom row align-items-center";
    echo $buzz->seen ? " text-black-50" : "";
    echo "'>";
    echo "<span class='fw-bold col text-start'>$buzz->team </span>";
    echo "<span class='text-muted small col'>". $interval->format('%i minutes %s seconds ago') ."</span>";
    echo "<span class='m-1 col-1 text-center'><a class='m-1 btn";
    echo $buzz->seen ? " btn-secondary" : " btn-success";
    echo"' href='?setSeen=$buzz->id'><i class='fs-6 fa fa-check'></i></a></span>";
    echo "<span class='m-1 col-1 text-center'><a class='m-1 btn btn-danger' href='?deleteBuzz=$buzz->id'><i class='fs-6 fa fa-trash'></i></a></span>";
    echo "</li>";
}
?>