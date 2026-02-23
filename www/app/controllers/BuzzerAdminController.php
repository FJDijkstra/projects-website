<?php

use MyApp\Controller;
use MyApp\ErrorHandler;
use MyApp\Team;
use MyApp\Buzz;
use MyApp\Admin;

/**
 * HomeController Class
 *
 * Represents the controller for the home-related functionality.
 */
class BuzzerAdminController extends Controller
{

    public function __construct()
    {
        if (isset($_GET['password'])) {
            $password = (string) $_GET['password'];
            if($password == "") {
                ErrorHandler::addError("Vul een wachtwoord in!");
                header("location: /buzzer/teams");
                exit;
            }
            if(Admin::becomeAdmin($password)) {
                $_SESSION['password'] = $password;
            } else {
                ErrorHandler::addError("Verkeerd wachtwoord, iemand anders is al spelleider");
                header("location: /buzzer/teams");
                exit;
            }
        } else {
            if (isset($_SESSION['password'])) {
                if(!Admin::becomeAdmin((string) $_SESSION['password'])) {
                    ErrorHandler::addError("Verkeerd wachtwoord, iemand anders is al spelleider");
                    header("location: /buzzer/teams");
                    exit;
                }
            } else {
                ErrorHandler::addError("Je hebt geen toegang tot deze pagina");
                header("location: /buzzer/teams");
                exit;
            }
        }
        if (isset($_GET['deleteBuzz'])) {
            Buzz::deleteBuzz($_GET['deleteBuzz']);
            header("location: /buzzer/admin");
            exit;
        }
        if (isset($_GET['deleteAllBuzzes'])) {
            if ($_GET['deleteAllBuzzes']) {
                Buzz::deleteAllBuzz();
            }
            header("location: /buzzer/admin");
            exit;
        }
        if (isset($_GET['deleteTeam'])) {
            Team::deleteTeam($_GET['deleteTeam']);
            header("location: /buzzer/admin");
            exit;
        }
        if (isset($_GET['deleteAllTeams'])) {
            if ($_GET['deleteAllTeams']) {
                Team::deleteAllTeams();
            }
            header("location: /buzzer/admin");
            exit;
        }
        if (isset($_GET['addPoint'])) {
            Team::changePoints($_GET['addPoint'], 1);
            header("location: /buzzer/admin");
            exit;
        }
        if (isset($_GET['removePoint'])) {
            Team::changePoints($_GET['removePoint'], -1);
            header("location: /buzzer/admin");
            exit;
        }
        if (isset($_GET['setPoints'])) {
            if (isset($_GET['newPoints'])) {
                Team::setPoints($_GET['setPoints'], $_GET['newPoints']);
            }
            header("location: /buzzer/admin");
            exit;
        }
        if (isset($_GET['clearPoints'])) {
            if ($_GET['clearPoints']) {
                Team::clearPoints();
            }
            header("location: /buzzer/admin");
            exit;
        }
    }
    /**
     * Display the index page.
     */
    public function index()
    {
        $data['title'] = "Buzzer";
        $data['buzzes'] = Buzz::getBuzzes();
        $data['teams'] = Team::getTeams();
        $this->template('header', $data);
        $this->template('navbar', $data);
        $this->template('errors', $data);
        $this->view('buzzerAdmin', $data);
        $this->template('footer');
    }
}