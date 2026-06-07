<?php

use MyApp\Controller;
use MyApp\Counter;

/**
 * HomeController Class
 *
 * Represents the controller for the home-related functionality.
 */
class CountersController extends Controller
{
    public function __construct()
    {
        if(isset($_GET['countername'])) {
            Counter::createNew((string) $_GET['countername']);
            header("location: /counters");
            exit;
        }
        if(isset($_GET['delete'])) {
            Counter::deleteCounter((int) $_GET['delete']);
            header("location: /counters");
            exit;
        }
    }
    /**
     * Display the index page.
     */
    public function index()
    {
        $data['title'] = "Buzzer";
        $data['counters'] = Counter::getCounters();
        $this->template('header', $data);
        $this->template('navbar', $data);
        $this->template('errors', $data);
        $this->view('counters', $data);
        $this->template('footer');
    }
}