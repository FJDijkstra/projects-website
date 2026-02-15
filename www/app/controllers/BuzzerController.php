<?php

use MyApp\Controller;
use MyApp\ErrorHandler;
use MyApp\Team;
use MyApp\Buzz;

/**
 * HomeController Class
 *
 * Represents the controller for the home-related functionality.
 */
class BuzzerController extends Controller
{
    private Team $team;
    public function __construct()
    {
        $team = NULL;
        $teamid = isset($_GET['team']) ? (int) $_GET['team'] : 0;
        if ($teamid > 0) {
            $team = Team::getById($teamid);
        }
        if ($team == NULL) {
            ErrorHandler::addError("Je team is verwijderd, kies een ander team");
            header("location: /buzzer/teams");
            exit;
        }
        $this->team = $team;
        if (isset($_GET['buzzed'])) {
            Buzz::createNew($teamid);
            header("location: /buzzer?team=$teamid");
            exit;
        }
    }
    /**
     * Display the index page.
     */
    public function index()
    {
        $data['title'] = "Buzzer";
        $data['teamname'] = ($this->team)->name;
        $data['teamid'] = ($this->team)->id;
        $this->template('header', $data);
        $this->template('navbar', $data);
        $this->template('errors', $data);
        $this->view('buzzer', $data);
        $this->template('footer');
    }
}