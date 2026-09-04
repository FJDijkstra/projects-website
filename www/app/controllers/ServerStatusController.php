<?php

use MyApp\Controller;

/**
 * ServerStatusController Class
 *
 * Represents the controller for the server status-related functionality.
 */
class ServerStatusController extends Controller
{
    /**
     * Display the index page.
     */
    public function index()
    {
        $data['title'] = "Server Status";
        $this->template('header', $data);
        $this->template('navbar', $data);
        $this->template('errors', $data);
        $this->view('server_status', $data);
        $this->template('footer');
    }
}