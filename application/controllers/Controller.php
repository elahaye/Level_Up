<?php

class Controller
{
    /**
     * Display the view of the page asked 
     * 
     * @param string $viewName
     * @return void
     */
    public function createView(string $viewName): void
    {
        // Warning : the directory come from the Router.php 
        include_once('application/views/LayoutView.phtml');
        require_once("application/views/$viewName.phtml");
    }

    /**
     * Path between the JS file and the PHP file to link 
     * 
     * @param $object
     * @return 
     */
    public function returnJson($object)
    {
        echo json_encode($object);
    }
}
