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
    protected array $teams;
    public function __construct()
    {
        if(isset($_GET['teamname'])) {
            Team::createNew((string) $_GET['teamname']);
        }
        $this->teams = Team::getTeams();
    }
    /**
     * Display the index page.
     */
    public function index()
    {
        $data['title'] = "Buzzer";
        $data['teams'] = $this->teams;
        $this->template('header', $data);
        $this->template('navbar', $data);
        $this->view('teams', $data);
        $this->template('footer');
    }
}