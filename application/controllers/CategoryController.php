<?php

class CategoryController extends Controller
{
    public $showCategories;
    public $categoryModel;
    public $categoryId;
    public $name;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_GET['editId'])) {
            if ($_GET['editId'] != '') {
                $this->editCategory();
            } else {
                $this->addCategory();
            }
        } elseif (isset($_GET['id'])) {
            $this->editForm();
        } else {
            $this->displayCategories();
        }
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

    public function addCategory()
    {
        $this->name = $_POST['nameCategory'];

        $this->categoryModel->addCategory($this->name);

        // Redirect to the articles list page
        Router::redirectTo('listCategories');
        exit();
    }

    /* Display in the edit form the category who's going to be edited
     * 
     * @return void
     */
    public function editForm(): void
    {
        $this->categoryId = $_GET['id'];

        $editCategory = $this->categoryModel->displayOneCategory($this->categoryId);

        $this->name = $editCategory['name'];
    }

    public function editCategory()
    {
        $this->categoryId = $_GET['editId'];
        $this->name = $_POST['nameCategory'];

        $this->categoryModel->editCategory($this->categoryId, $this->name);

        // Redirect to the articles list page
        Router::redirectTo('listCategories');
        exit();
    }

    /**
     * Delete a catégory
     */
    public function deleteCategory()
    {
        $this->categoryId = $_GET['deleteId'];

        $this->categoryModel->deleteCategory($this->categoryId);
        // Redirect to the categories list page
        Router::redirectTo('listCategories');
        exit();
    }
}
