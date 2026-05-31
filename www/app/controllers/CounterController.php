<?php

use MyApp\Controller;
use MyApp\ErrorHandler;
use MyApp\Counter;

/**
 * CounterController Class
 *
 * Represents the controller for the counter-related functionality.
 */
class CounterController extends Controller
{
    private Counter $counter;
    public function __construct()
    {
        $counter = NULL;
        $counterid = isset($_GET['counter']) ? (int) $_GET['counter'] : 0;
        if ($counterid > 0) {
            $counter = Counter::getById($counterid);
        }
        if ($counter == NULL) {
            ErrorHandler::addError("Deze counter bestaat niet");
            header("location: /counters");
            exit;
        }
        $this->counter = $counter;
        if (isset($_GET['increment'])) {
            Counter::incrementCounter($counterid, $_GET['increment']);
            header("location: /counter?counter=$counterid");
            exit;
        }
        if (isset($_GET['decrement'])) {
            Counter::decrementCounter($counterid, $_GET['decrement']);
            header("location: /counter?counter=$counterid");
            exit;
        }
    }
    /**
     * Display the index page.
     */
    public function index()
    {
        $data['title'] = "Counter";
        $data['countername'] = ($this->counter)->name;
        $data['counterid'] = ($this->counter)->id;
        $this->template('header', $data);
        $this->template('navbar', $data);
        $this->template('errors', $data);
        $this->view('counter', $data);
        $this->template('footer');
    }
}