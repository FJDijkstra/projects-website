<?php

use MyApp\Controller;
use MyApp\Team;

/**
 * HomeController Class
 *
 * Represents the controller for the home-related functionality.
 */
class TeamsController extends Controller
{
    public function __construct()
    {
        if(isset($_GET['teamname'])) {
            Team::createNew((string) $_GET['teamname']);
            header("location: /buzzer/teams");
            exit;
        }
    }
    /**
     * Display the index page.
     */
    public function index()
    {
        $data['title'] = "Buzzer";
        $data['teams'] = Team::getTeams();
        $this->template('header', $data);
        $this->template('navbar', $data);
        $this->template('errors', $data);
        $this->view('teams', $data);
        $this->template('footer');
    }
}