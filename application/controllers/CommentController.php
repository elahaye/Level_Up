<?php

class CommentController extends Controller
{
    public $articleModel;
    public $commentModel;
    public $showOwnArticles;
    public $article;
    public $comment;
    public $allComments;

    public function __construct()
    {
        $this->articleModel = new ArticleModel();
        $this->commentModel = new CommentModel();

        if (isset($_GET['id'])) {
            $this->displayCommentsFromArticle();
        } else if (isset($_GET['deleteId'])) {
            $this->deleteComment();
        } else {
            $this->displayArticlesAndNumberOfComments();
        }
    }

    public function displayArticlesAndNumberOfComments()
    {
        $query = $this->articleModel->displayArticlesFromAuthor($_SESSION['user']['id']);
        $this->showOwnArticles = $query->fetchAll();

        for ($i = 0; $i < count($this->showOwnArticles); $i++) {
            $query = $this->commentModel->displayCommentsFromArticle($this->showOwnArticles[$i]['id']);
            $numberOfComments = count($query->fetchAll());
            $this->showOwnArticles[$i]['comments'] = $numberOfComments;
        }
    }

    public function displayCommentsFromArticle()
    {
        $this->article = $_GET['id'];

        $query = $this->articleModel->displayOneArticle($this->article);
        $this->article = $query->fetch();

        $query = $this->commentModel->displayCommentsFromArticle($this->article['id']);
        $this->allComments = $query->fetchAll();
    }

    public function deleteComment()
    {
        $this->comment = $_GET['deleteId'];

        $this->commentModel->deleteComment($this->comment);

        // Redirect to the comments list page
        Router::redirectTo('listComments');
        exit();
    }
}
