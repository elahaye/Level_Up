<?php

class AdminController extends Controller
{
    public $articleModel;
    public $commentModel;
    public $userModel;
    public $showArticles = [];
    public $showAllComments = [];
    public $showCommentsFromAuthor = [];


    public function __construct()
    {
        $this->articleModel = new ArticleModel();
        $this->commentModel = new CommentModel();
        $this->userModel = new UserModel();

        $this->displayArticles();
        $this->displayComments();
    }


    /**
     * Display 10 last published articles
     */
    public function displayArticles()
    {
        if ($_SESSION['user']['status'] == 'admin') {
            $query = $this->articleModel->displayAllArticles();
            $this->showArticles = $query->fetchAll();

            // Change the author by the nickname corresponding to the id given
            for ($i = 0; $i < count($this->showArticles); $i++) {
                $model = $this->userModel->displayOneUser($this->showArticles[$i]['author']);
                $this->showArticles[$i]['author'] = $model['nickname'];
            }
        } else if ($_SESSION['user']['status'] == 'author') {
            $query = $this->articleModel->displayArticlesFromAuthor($_SESSION['user']['id']);
            $this->showArticles = $query->fetchAll();
        }
    }

    /**
     * Display 10 last published comments
     */
    public function displayComments()
    {
        if ($_SESSION['user']['status'] == 'admin') {
            $query = $this->commentModel->displayAllComments();
            $this->showAllComments = $query->fetchAll();

            for ($i = 0; $i < count($this->showAllComments); $i++) {
                $model = $this->articleModel->displayOneArticle((int)$this->showAllComments[$i]['article_id']);
                $model = $model->fetch();
                $this->showAllComments[$i]['article_id'] = $model['title'];
            }
        } else if ($_SESSION['user']['status'] == 'author') {
            $query = $this->commentModel->displayAllComments();
            $this->showAllComments = $query->fetchAll();

            for ($i = 0; $i < count($this->showAllComments); $i++) {
                $model = $this->articleModel->displayOneArticle((int)$this->showAllComments[$i]['article_id']);
                $model = $model->fetch();
                $this->showAllComments[$i]['article_id'] = $model['title'];
                $this->showAllComments[$i]['user'] = $model['author'];

                if ($this->showAllComments[$i]['user'] == $_SESSION['user']['id']) {
                    $this->showCommentsFromAuthor[] = $this->showAllComments[$i];
                }
            }
        }
    }
}
