<?php

class ArticleController extends Controller
{
    public $model;
    public $articleId;
    public $title = '';
    public $content = '';
    public $category;
    public $image;
    public $date;
    public $editDate;
    public $author;
    public $showArticles;
    public $showArticleDetails;
    public $showCategories;
    public $showComments;

    public function __construct()
    {
        $this->articleModel = new ArticleModel();
        $this->userModel = new UserModel();
        $this->commentModel = new CommentModel();
        $this->categoryModel = new CategoryModel();

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_GET['editId'])) {
                if ($_GET['editId'] != '') {
                    $this->editArticle();
                } else {
                    $this->addArticle();
                }
            } elseif (isset($_GET['articleId'])) {
                $this->addComment();
            }
        } elseif (isset($_GET['id'])) {
            $this->displayCategories();
            $this->displayOneArticle();
            $this->editForm();
        } else {
            $this->displayCategories();
            $this->displayAllArticles();
        }
    }

    /**
     * Display all articles
     * 
     * @return array
     */
    public function displayAllArticles()
    {
        if ($_SESSION['user']['status'] == 'admin') {
            $query = $this->articleModel->displayAllArticles();
            $this->showArticles = $query->fetchAll();

            // Change the author by the nickname corresponding to the id given
            for ($i = 0; $i < count($this->showArticles); $i++) {
                $model = $this->userModel->displayOneUser($this->showArticles[$i]['author']);
                $this->showArticles[$i]['author'] = $model['nickname'];
            }

            // Change the category by the name corresponding to the id given
            for ($i = 0; $i < count($this->showArticles); $i++) {
                $model = $this->categoryModel->displayOneCategory($this->showArticles[$i]['category']);
                $this->showArticles[$i]['category'] = $model['name'];
            }
        } else if ($_SESSION['user']['status'] == 'author') {
            $query = $this->articleModel->displayArticlesFromAuthor($_SESSION['user']['id']);
            $this->showArticles = $query->fetchAll();
        }
    }
    /**
     * Display one specific article
     * 
     * @return array
     */
    public function displayOneArticle()
    {
        $this->articleId = $_GET['id'];
        $query = $this->articleModel->displayOneArticle($this->articleId);
        $this->showArticleDetails = $query->fetch();

        // Change the author by the nickname corresponding to the id given
        $model = $this->userModel->displayOneUser($this->showArticleDetails['author']);
        $this->showArticleDetails['author'] = $model['nickname'];

        $query = $this->commentModel->displayAllComments($this->articleId);
        $this->showComments = $query->fetchAll();
    }

    /**
     * Display all the categories
     * 
     * @return array
     */
    public function displayCategories()
    {
        $query = $this->categoryModel->displayCategories();
        $this->showCategories = $query->fetchAll();
    }

    /**
     * Add an article in the database
     * 
     * @return void
     */
    public function addArticle(): void
    {
        $this->title = $_POST['titleArticle'];
        $this->content = $_POST['contentArticle'];
        $this->category = $_POST['categoryArticle'];
        $datetime = new DateTime();
        $this->date = $datetime->format('Y-m-d H:i:s');
        $this->author = $_SESSION['user']['id'];

        //$this->image = $_FILES['imageArticle'];
        //$destination = 'application/views/images/'.$this->image;

        var_dump($_FILES['imageArticle']['name']);

        //$this->articleModel->addArticle($this->title, $this->content, $this->category, $this->image, $this->date, $this->author);

        /*// Redirect to the articles list page
        Router::redirectTo('listArticles');
        exit();*/
    }

    /**
     * Display in the edit form the article who's going to be edited
     * 
     * @return void
     */
    public function editForm(): void
    {
        $this->articleId = $_GET['id'];
        $query = $this->articleModel->displayOneArticle($this->articleId);
        $editArticle = $query->fetch();

        $this->title = $editArticle['title'];
        $this->content = $editArticle['content'];
        $this->category = $editArticle['category'];
    }

    /**
     * Edit an article in the database
     * 
     * @return void
     */
    public function editArticle(): void
    {
        $this->articleId = $_GET['editId'];
        $this->title = $_POST['titleArticle'];
        $this->content = $_POST['contentArticle'];
        $this->category = $_POST['categoryArticle'];
        $datetime = new DateTime();
        $this->editDate = $datetime->format('Y-m-d H:i:s');

        $this->articleModel->editArticle($this->articleId, $this->title, $this->content, $this->category, $this->editDate);

        // Redirect to the articles list page
        Router::redirectTo('listArticles');
        exit();
    }

    /**
     * Delete an article from the database
     * 
     * @return void
     */
    public function deleteArticle(): void
    {
        $this->articleId = $_GET['deleteId'];

        $this->articleModel->deleteArticle($this->articleId);
        // Redirect to the articles list page
        Router::redirectTo('listArticles');
        exit();
    }

    /**
     * Add a comment in tha database for the article concerned
     * 
     * @return void
     */
    public function addComment(): void
    {
        $this->articleId = $_GET['articleId'];
        $this->content = $_POST['comment'];
        $this->author = $_POST['nickname'];
        $datetime = new DateTime();
        $this->date = $datetime->format('Y-m-d H:i:s');

        $this->commentModel->addComment($this->content, $this->date, $this->author, $this->articleId);

        // Redirect to the article page
        Router::redirectTo('articleDetails&id=' . $this->articleId);
        exit();
    }
}
