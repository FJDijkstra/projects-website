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
    protected string $error = "";
    public function __construct()
    {
        $this->teams = Team::getTeams();
        $teamname = isset($_GET['teamname']) ? (string) $_GET['teamname'] : "";
        foreach ($this->teams as $team) {
            if ($teamname == $team->name) {
                $this->error = "Deze teamnaam bestaat al, kies een andere naam!";
            }
        }
        if ($this->error == "") {
            Team::createNew((string) $_GET['teamname']);
        }
        $this->teams = Team::getTeams();
    }
    /**
     * Display the index page.
     */
    public function index()
    {
        $data['title'] = "Teams";
        $data['error'] = $this->error;
        $data['teams'] = $this->teams;
        $this->template('header', $data);
        $this->template('navbar', $data);
        $this->view('teams', $data);
        $this->template('footer');
    }
}