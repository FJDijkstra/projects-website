<?php

use MyApp\Controller;
use MyApp\Feedback;
use MyApp\Team;
use MyApp\Buzz;

/**
 * FeedbackController Class
 *
 * Represents the controller for the feedback-related functionality.
 */
class FeedbackController extends Controller
{
    public function __construct()
    {
        if (isset($_POST['feedback'])) {
            Feedback::createNew($_POST['feedback']);
            header("location: /feedback");
            exit;
        }
    }
    /**
     * Display the index page.
     */
    public function index()
    {
        $data['title'] = "Feedback";
        $data['feedback'] = Feedback::getFeedback();
        $this->template('header', $data);
        $this->template('navbar', $data);
        $this->template('errors', $data);
        $this->view('feedback', $data);
        $this->template('footer');
    }
}