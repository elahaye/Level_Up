<?php

class CategoryController extends Controller
{
    public $showCategories;
    public $categoryModel;
    public $categoryId;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();

        $this->displayCategories();
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

    public function deleteCategory()
    {
        $this->categoryId = $_GET['deleteId'];

        $this->categoryModel->deleteCategory($this->categoryId);
        // Redirect to the categories list page
        Router::redirectTo('listCategories');
        exit();
    }
}
