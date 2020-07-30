<?php

class ContactController extends Controller 
{
    public $articles;

    public function __construct() 
    {
        $this->initController();
    }

    private function initController() 
    {
        $this->getArticles();
    }

    public function getArticles()
    {
        $model = new ContactModel();

        $this->articles = $model->getArticles();
    }
}