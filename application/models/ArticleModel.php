<?php

class ArticleModel extends Model
{
    /**
     * Add an article in the database
     * 
     * @param string $title
     * @param string $content
     * @param string $category
     * @param string $image
     * @param string $date
     * @param int $author
     * 
     * @return void
     */
    public function addArticle(string $title, string $content, string $category, string $image, string $date, int $author)
    {
        $sql = 'INSERT INTO `articles` 
                SET title = :title, content = :content, category = :category, image = :image, date = :date, author = :author';
        $add_article = $this->pdo->prepare($sql);
        $add_article->execute(compact('title', 'content', 'category', 'image', 'date', 'author'));
    }

    /**
     * Display all the articles
     * 
     * @return array
     */
    public function displayAllArticles()
    {
        $show_articles = $this->pdo->query('SELECT * FROM `articles` ORDER BY date DESC');

        return $show_articles;
    }

    /**
     * Display the articles corresponding to one author only
     * 
     * @param int $author
     * 
     * @return array
     */
    public function displayArticlesFromAuthor(int $author)
    {
        $sql = 'SELECT * FROM `articles` WHERE author = :author ORDER BY date DESC';
        $show_articles = $this->pdo->prepare($sql);
        $show_articles->execute(['author' => $author]);

        return $show_articles;
    }
    /**
     * Display the article from the id
     * 
     * @param int $article_id
     * 
     * @return array
     */
    public function displayOneArticle(int $article_id)
    {
        $sql = 'SELECT * FROM `articles` WHERE id = :id';
        $show_article = $this->pdo->prepare($sql);
        $show_article->execute(['id' => $article_id]);

        return $show_article;
    }

    /**
     * Edit an article in the database
     * 
     * @param int $article_id
     * @param string $title
     * @param string $content
     * @param string $category
     * @param string $editDate
     * 
     * @return void
     */
    public function editArticle(int $article_id, string $title, string $content, string $category, string $editDate): void
    {
        $sql = 'UPDATE `articles` SET title = :title, content = :content, category = :category, editDate = :editDate WHERE id = :article_id';
        $edit_article = $this->pdo->prepare($sql);
        $edit_article->execute(compact('article_id', 'title', 'content', 'category', 'editDate'));
    }

    /**
     * Delete an article from the database
     * 
     * @param int $article_id
     * 
     * @return void
     */
    public function deleteArticle(int $article_id): void
    {
        $sql = 'DELETE FROM `articles` WHERE id = :id';
        $delete_article = $this->pdo->prepare($sql);
        $delete_article->execute(['id' => $article_id]);
    }
}
