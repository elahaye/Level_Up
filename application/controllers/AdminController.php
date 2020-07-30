<?php

class AdminController extends Controller
{
    public $articleModel;
    public $showArticles;

    public function __construct()
    {
        $this->articleModel = new ArticleModel();
        $this->userModel = new UserModel();

        $this->displayArticles();
    }


    /**
     * Display 5 last published articles
     */
    public function displayArticles()
    {
        if ($_SESSION['user']['status'] == 'admin')
        {
            $query = $this->articleModel->displayAllArticles();
            $this->showArticles = $query->fetchAll();

            // Change the author by the nickname corresponding to the id given
            for($i = 0; $i < count($this->showArticles); $i++)
            {
                $model = $this->userModel->displayOneUser($this->showArticles[$i]['author']);
                $this->showArticles[$i]['author'] = $model['nickname'];
            }
        }
        else if ($_SESSION['user']['status'] == 'author')
        {
            $query = $this->articleModel->displayArticlesFromAuthor($_SESSION['user']['id']);
            $this->showArticles = $query->fetchAll();
        }
    }
}