<?php

use MyApp\Controller;
use MyApp\Team;
use MyApp\Buzz;

/**
 * HomeController Class
 *
 * Represents the controller for the home-related functionality.
 */
class BuzzerAdminController extends Controller
{
    public function __construct()
    {
        if (isset($_GET['delete'])) {
            Buzz::deleteBuzz($_GET['delete']);
        }
        if (isset($_GET['deleteAll'])) {
            if ($_GET['deleteAll']) {
                Buzz::deleteAllBuzz();
            }
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
        $this->view('buzzerAdmin', $data);
        $this->template('footer');
    }
}