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
     * @param int $category_id
     */
    public function displayOneCategory(string $category_id)
    {
        $query = $this->pdo->prepare('SELECT name FROM `categories` WHERE id = :id');
        $query->execute(['id' => $category_id]);
        $display_category = $query->fetch();

        return $display_category;
    }

    /**
     * Add a category
     *
     * @param string $name
     * @return void
     */
    public function addCategory(string $name)
    {
        $sql = 'INSERT INTO `categories` 
                SET name = :name';
        $add_category = $this->pdo->prepare($sql);
        $add_category->execute(['name' => $name]);
    }

    /**
     * Edit the selected category
     *
     * @param integer $category_id
     * @param string $name
     * @return void
     */
    public function editCategory(int $category_id, string $name)
    {
        $sql = 'UPDATE `categories` SET name = :name WHERE id = :category_id';
        $edit_category = $this->pdo->prepare($sql);
        $edit_category->execute(compact('category_id', 'name'));
    }

    /**
     * Delete the selected category
     *
     * @param integer $category_id
     * @return void
     */
    public function deleteCategory(int $category_id): void
    {
        $sql = 'DELETE FROM `categories` WHERE id = :id';
        $delete_category = $this->pdo->prepare($sql);
        $delete_category->execute(['id' => $category_id]);
    }
}
