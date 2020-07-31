<?php

class CommentModel extends Model
{

    public function displayAllComments()
    {
        $show_comments = $this->pdo->query('SELECT * FROM `comments` ORDER BY date DESC');

        return $show_comments;
    }

    /**
     * Display all the comments of an article 
     * 
     * @param int $article_id
     * 
     * @return array
     */
    public function displayCommentsFromArticle(int $article_id)
    {
        $show_comments = $this->pdo->prepare('SELECT * FROM `comments` WHERE article_id = :article_id ORDER BY date DESC');
        $show_comments->execute(['article_id' => $article_id]);

        return $show_comments;
    }


    /**
     * Add a comment in the database for the article concerned
     * 
     * @param string $content
     * @param string $date
     * @param string $author
     * @param int $article_id
     * 
     * @return void
     */
    public function addComment(string $content, string $date, string $author, int $article_id): void
    {
        $sql = 'INSERT INTO `comments`
                SET content = :content, date = :date, author = :author, article_id = :article_id';
        $add_comment = $this->pdo->prepare($sql);
        $add_comment->execute(compact('content', 'date', 'author', 'article_id'));
    }

    public function deleteComment(int $comment_id)
    {
        $sql = 'DELETE FROM `comments` WHERE id = :id';
        $delete_comment = $this->pdo->prepare($sql);
        $delete_comment->execute(['id' => $comment_id]);
    }
}
