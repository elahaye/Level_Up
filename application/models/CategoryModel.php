<?php

class CategoryModel extends Model
{

    /**
     * Display all the categories
     * 
     * @return array
     */
    public function displayCategories()
    {
        $show_categories = $this->pdo->query('SELECT * FROM `categories`');

        return $show_categories;
    }

    /**
     * Display one category by his id
     * 
     * @param int $category_id
     * 
     * @return string
     */
    public function displayOneCategory(string $category_id)
    {
        $query = $this->pdo->prepare('SELECT name FROM `categories` WHERE id = :id');
        $query->execute(['id' => $category_id]);
        $display_category = $query->fetch();

        return $display_category;
    }

    public function deleteCategory(int $category_id): void
    {
        $sql = 'DELETE FROM `categories` WHERE id = :id';
        $delete_category = $this->pdo->prepare($sql);
        $delete_category->execute(['id' => $category_id]);
    }
}
