<?php

use MyApp\Controller;

/**
 * HomeController Class
 *
 * Represents the controller for the home-related functionality.
 */
class MazeController extends Controller
{
    /**
     * Display the index page.
     */
    public function index()
    {
        $data['title'] = "Maze";
        $data['vs'] = isset($_GET['vs']) ? $_GET['vs'] : 0;
        $this->template('header', $data);
        $this->template('navbar', $data);
        $this->template('errors', $data);
        $this->view('maze', $data);
        $this->template('footer');
    }
}