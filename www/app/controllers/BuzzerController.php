<?php

use MyApp\Controller;
use MyApp\Team;
use MyApp\Buzz;

/**
 * HomeController Class
 *
 * Represents the controller for the home-related functionality.
 */
class BuzzerController extends Controller
{
    protected Team $team;
    public function __construct()
    {
        $teamid = isset($_GET['team']) ? (int) $_GET['team'] : 0;
        if ($teamid > 0) {
            $this->team = Team::getById($teamid);
        }
        if (isset($_GET['buzzed'])) {
            Buzz::createNew($teamid);
            header("location: /buzzer?team=$teamid");
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
        $this->view('buzzer', $data);
        $this->template('footer');
    }
}