<?php

use MyApp\Controller;
use MyApp\Team;

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
        var_dump($_GET);
        $teamid = isset($_GET['team']) ? (int) $_GET['team'] : 0;
        if ($teamid > 0) {
            $this->team = Team::getById($teamid);
        }
    }
    /**
     * Display the index page.
     */
    public function index()
    {
        $data['title'] = "Buzzer";
        $data['teamname'] = ($this->team)->name;
        $this->template('header', $data);
        $this->template('navbar', $data);
        $this->view('buzzer', $data);
        $this->template('footer');
    }
}