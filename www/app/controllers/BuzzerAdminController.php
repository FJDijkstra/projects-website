<?php

use MyApp\Controller;
use MyApp\ErrorHandler;
use MyApp\Team;
use MyApp\Buzz;
use MyApp\BuzzerSession;

/**
 * HomeController Class
 *
 * Represents the controller for the home-related functionality.
 */
class BuzzerAdminController extends Controller
{
    private $session_id = 0;
    public function __construct()
    {
        if (isset($_GET['session_id'])) {
            $this->session_id = (int) $_GET['session_id'];
        }
        if (isset($_GET['setSeen'])) {
            Buzz::setSeen($_GET['setSeen']);
            header("location: /buzzer/admin?session_id=" . $this->session_id);
            exit;
        }
        if (isset($_GET['deleteBuzz'])) {
            Buzz::deleteBuzz($_GET['deleteBuzz']);
            header("location: /buzzer/admin?session_id=" . $this->session_id);
            exit;
        }
        if (isset($_GET['deleteAllBuzzes'])) {
            if ($_GET['deleteAllBuzzes']) {
                Buzz::deleteAllBuzz();
            }
            header("location: /buzzer/admin?session_id=" . $this->session_id);
            exit;
        }
        if (isset($_GET['deleteTeam'])) {
            Team::deleteTeam($_GET['deleteTeam']);
            header("location: /buzzer/admin?session_id=" . $this->session_id);
            exit;
        }
        if (isset($_GET['deleteAllTeams'])) {
            if ($_GET['deleteAllTeams']) {
                Team::deleteAllTeams();
            }
            header("location: /buzzer/admin?session_id=" . $this->session_id);
            exit;
        }
        if (isset($_GET['addPoint'])) {
            Team::changePoints($_GET['addPoint'], 1);
            header("location: /buzzer/admin?session_id=" . $this->session_id);
            exit;
        }
        if (isset($_GET['removePoint'])) {
            Team::changePoints($_GET['removePoint'], -1);
            header("location: /buzzer/admin?session_id=" . $this->session_id);
            exit;
        }
        if (isset($_GET['setPoints'])) {
            if (isset($_GET['newPoints'])) {
                Team::setPoints($_GET['setPoints'], $_GET['newPoints']);
            }
            header("location: /buzzer/admin?session_id=" . $this->session_id);
            exit;
        }
        if (isset($_GET['clearPoints'])) {
            if ($_GET['clearPoints']) {
                Team::clearPoints();
            }
            header("location: /buzzer/admin?session_id=" . $this->session_id);
            exit;
        }
    }
    /**
     * Display the index page.
     */
    public function index()
    {
        $data['title'] = "Buzzer";
        $data['session_id'] = $this->session_id;
        $data['session'] = BuzzerSession::getById($this->session_id);
        $data['buzzes'] = Buzz::getBuzzesBySession($this->session_id);
        $data['teams'] = Team::getTeamsBySession($this->session_id);
        $this->template('header', $data);
        $this->template('navbar', $data);
        $this->template('errors', $data);
        $this->view('buzzerAdmin', $data);
        $this->template('footer');
    }
}