<?php

use MyApp\Controller;

/**
 * HomeController Class
 *
 * Represents the controller for the home-related functionality.
 */
class MemoryController extends Controller
{
    /**
     * Display the index page.
     */
    public function index()
    {
        $data['title'] = "Memory";
        $this->template('header', $data);
        $this->template('navbar', $data);
        $this->view('memory', $data);
        $this->template('footer');
    }
}