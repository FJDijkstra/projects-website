<?php

use MyApp\Controller;

/**
 * HomeController Class
 *
 * Represents the controller for the home-related functionality.
 */
class SizeController extends Controller
{
    /**
     * Display the index page.
     */
    public function index()
    {
        $data['title'] = "Size";
        $this->template('header', $data);
        $this->template('navbar', $data);
        $this->template('errors', $data);
        $this->view('size', $data);
        $this->template('footer');
    }
}