<?php

use MyApp\Controller;
use MyApp\Team;
use MyApp\BuzzerSession;
use MyApp\ErrorHandler;

/**
 * TeamsController Class
 *
 * Represents the controller for the teams-related functionality.
 */
class TeamsController extends Controller
{
    private $session_id = 0;
    public function __construct()
    {
        $sessions = BuzzerSession::getBuzzerSessions();
        $this->session_id = $sessions[count($sessions) - 1]->id;
        if(isset($_GET['session'])) {
            $this->session_id = (int) $_GET['session'];
        }
    
        if(isset($_GET['teamname'])) {
            Team::createNew((string) $_GET['teamname'], $this->session_id);
            header("location: /buzzer/teams?session=" . $this->session_id);
            exit;
        }
        if(isset($_GET['password'])) {
            if ($_GET['password'] == BuzzerSession::checkPassword($this->session_id, (string) $_GET['password'])) {
                header("location: /buzzer/admin?session_id=" . $this->session_id);
                exit;
            } else {
                ErrorHandler::addError("Verkeerd wachtwoord, probeer opnieuw");
                header("location: /buzzer/teams?session=" . $this->session_id);
                exit;
            }
        }
    }
    /**
     * Display the index page.
     */
    public function index()
    {
        $data['title'] = "Buzzer";
        $data['session_id'] = $this->session_id;
        $data['sessions'] = BuzzerSession::getBuzzerSessions();
        $data['teams'] = Team::getTeamsBySession($this->session_id);
        $data['session_id'] = $this->session_id;
        $this->template('header', $data);
        $this->template('navbar', $data);
        $this->template('errors', $data);
        $this->view('teams', $data);
        $this->template('footer');
    }
}