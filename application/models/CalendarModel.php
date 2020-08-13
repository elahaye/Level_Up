<?php

class CalendarModel extends Model
{

    /**
     * Display a task from his id
     * 
     * @param int $task_id
     * 
     * @return array
     */
    public function showTask(int $task_id)
    {
        $sql = 'SELECT * FROM `tasks` WHERE id = :id';
        $show_task = $this->pdo->prepare($sql);
        $show_task->execute(['id' => $task_id]);

        return $show_task;
    }

    /**
     * Display all the tasks from a date
     * 
     * @param int $user_id 
     * @param string $date
     * @param string status
     * 
     * @return array
     */
    public function showTasksOfDay(int $user_id, string $date, string $status)
    {
        $sql = 'SELECT * FROM `tasks` WHERE user_id = :user_id AND date = :date AND status = :status';
        $show_tasks = $this->pdo->prepare($sql);
        $show_tasks->execute(compact('user_id', 'date', 'status'));

        return $show_tasks;
    }

    /**
     * Display all the important futur tasks
     * 
     * @param int $user_id
     * 
     * @return array
     */
    public function showImportantFuturTasks(int $user_id)
    {
        $sql = 'SELECT * FROM `tasks` WHERE user_id = :user_id AND status = "waiting" AND (priority = "3" OR priority = "4")';
        $show_important_task = $this->pdo->prepare($sql);
        $show_important_task->execute(['user_id' => $user_id]);

        return $show_important_task;
    }

    /**
     * Add a new task in the database for the concerned user
     * 
     * @param string $title 
     * @param string $content
     * @param int $priority
     * @param string $date
     * @param int $user_id
     * 
     * @return void
     */
    public function addNewTask(string $title, string $content, int $priority, string $date, int $user_id): void
    {
        $sql = 'INSERT INTO `tasks`
                SET title = :title, content = :content, priority = :priority, status = "waiting", date = :date, user_id = :user_id';
        $add_task = $this->pdo->prepare($sql);
        $add_task->execute(compact('title', 'content', 'priority', 'date', 'user_id'));
    }

    /**
     * Delete the selected task from the database
     * 
     * @param int $task_id
     * 
     * @return void
     */
    public function deleteTask(int $task_id): void
    {
        $delete_task = $this->pdo->prepare('DELETE FROM `tasks` WHERE id = :id');
        $delete_task->execute(['id' => $task_id]);
    }

    /**
     * Edit the selected task from the database
     * 
     * @param int $task_id
     * @param string $title
     * @param string $content
     * @param int $priority
     * @param string $date
     * 
     * @return void
     */
    public function editTask(int $task_id, string $title, string $content, int $priority, string $date): void
    {
        $sql = 'UPDATE `tasks` SET title = :title, content = :content, priority = :priority, date = :date WHERE id = :task_id';
        $edit_task = $this->pdo->prepare($sql);
        $edit_task->execute(compact('title', 'content', 'priority', 'date', 'task_id'));
    }

    /**
     * Change the current status off the selected task
     * 
     * @param string $status
     * @param int $task_id
     * 
     * @return void
     */
    public function changeStatusTask(string $status, int $task_id): void
    {
        $status_change = $this->pdo->prepare('UPDATE `tasks` SET status = :status WHERE id = :task_id');
        $status_change->execute(compact('status', 'task_id'));
    }

    /**
     * Change the status of tasks who were not completed before today date in failed
     * 
     * @param string $date
     * @param int $user_id
     * 
     * @return array;
     */
    public function tasksNotCompleted(string $date, int $user_id)
    {
        $sql = 'SELECT * FROM `tasks` 
                WHERE status = "waiting" AND date < DATE :date AND user_id = :user_id';
        $status_failed = $this->pdo->prepare($sql);
        $status_failed->execute(compact('date', 'user_id'));

        return $status_failed;
    }
}
