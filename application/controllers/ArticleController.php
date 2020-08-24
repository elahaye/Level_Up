<?php

class ArticleController extends Controller
{
    public $articleId;
    public $title = '';
    public $content = '';
    public $category;
    public $image;
    public $date;
    public $editDate;
    public $author;
    public $showArticles;
    public $showOwnArticles;
    public $showArticleDetails;
    public $showCategories;
    public $showComments;
    public $articleModel;
    public $userModel;
    public $commentModel;

    public function __construct()
    {
        $this->articleModel = new ArticleModel();
        $this->userModel = new UserModel();
        $this->categoryModel = new CategoryModel();
        $this->commentModel = new CommentModel();

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
        $query = $this->articleModel->displayAllArticles();
        $this->showArticles = $query->fetchAll();

        // Change the author by the nickname corresponding to the id given
        for ($i = 0; $i < count($this->showArticles); $i++) {
            $model = $this->userModel->displayOneUser($this->showArticles[$i]['author']);
            $this->showArticles[$i]['author'] = $model['nickname'];
        }

        // Change the category by the name corresponding to the id given
        for ($i = 0; $i < count($this->showArticles); $i++) {
            if ($this->showArticles[$i]['category'] === null) {
                $this->showArticles[$i]['category'] = "";
            } else {
                $model = $this->categoryModel->displayOneCategory($this->showArticles[$i]['category']);
                $this->showArticles[$i]['category'] = $model['name'];
            }
        }
        if (isset($_SESSION['user']) && $_SESSION['user']['status'] === 'author') {
            $query = $this->articleModel->displayArticlesFromAuthor($_SESSION['user']['id']);
            $this->showOwnArticles = $query->fetchAll();

            // Change the category by the name corresponding to the id given
            for ($i = 0; $i < count($this->showOwnArticles); $i++) {
                $model = $this->categoryModel->displayOneCategory($this->showOwnArticles[$i]['category']);
                if ($model === null) {
                    $this->showOwnArticles[$i]['category'] = "";
                } else {
                    $this->showOwnArticles[$i]['category'] = $model['name'];
                }
            }
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

        // Change the category by the name corresponding to the id given
        if ($this->showArticleDetails['category'] !== null) {
            $model = $this->categoryModel->displayOneCategory((int)$this->showArticleDetails['category']);
            $this->showArticleDetails['category'] = $model['name'];
        }

        $query = $this->commentModel->displayCommentsFromArticle($this->articleId);
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

        $this->image = $_FILES['imageArticle'];
        $tmp_file = $this->image['tmp_name'];
        $type_file = $this->image['type'];

        $temp = explode(".", $this->image['name']);
        $name_file = round(microtime(true)) . '.' . end($temp);

        $destination = 'application/views/images/';

        if (!is_uploaded_file($tmp_file) === true) {
            exit("Le fichier est introuvable");
        };
        if (!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp')) {
            exit("Le fichier n'est pas une image");
        }
        if (preg_match('#[\x00-\x1F\x7F-\x9F/\\\\]#', $name_file)) {
            exit("Nom de fichier non valide");
        } else if (!move_uploaded_file($tmp_file, $destination . $name_file)) {
            exit("Impossible de copier le fichier dans $destination");
        } else {
            move_uploaded_file($tmp_file, $destination . $name_file);
        }

        $this->articleModel->addArticle($this->title, $this->content, $this->category, $name_file, $this->date, $this->author);

        // Redirect to the articles list page
        Router::redirectTo('listArticles');
        exit();
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

        if ($this->image['tmp_name'] !== null) {
            $this->image = $_FILES['imageArticle'];
            $tmp_file = $this->image['tmp_name'];
            $type_file = $this->image['type'];

            $temp = explode(".", $this->image['name']);
            $name_file = round(microtime(true)) . '.' . end($temp);

            $destination = 'application/views/images/';

            if (!is_uploaded_file($tmp_file) === true) {
                exit("Le fichier est introuvable");
            };
            if (!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp')) {
                exit("Le fichier n'est pas une image");
            }
            if (preg_match('#[\x00-\x1F\x7F-\x9F/\\\\]#', $name_file)) {
                exit("Nom de fichier non valide");
            } else if (!move_uploaded_file($tmp_file, $destination . $name_file)) {
                exit("Impossible de copier le fichier dans $destination");
            } else {
                move_uploaded_file($tmp_file, $destination . $name_file);
            }
            $this->articleModel->editImageOfArticle($name_file, $this->articleId);
        }

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
