<?php

class HomeController extends Controller
{
    public $articleModel;
    public $showArticles;

    public function __construct()
    {
        $this->articleModel = new ArticleModel();
        $this->displayAllArticles();
    }

    /**
     * Display all articles
     *
     * @return array
     */
    public function displayAllArticles()
    {
        $query = $this->articleModel->displayAllArticles();
        $this->showArticles = $query->fetchAll();
    }
}
