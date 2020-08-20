<?php

class CalendarController extends Controller
{
    public $calendarModel;
    public $userModel;
    public $title = "";
    public $content = "";
    public $priority;
    public $date;
    public $newDate;
    public $editTask;
    public $showTasks;
    public $waitingTasks;
    public $doneTasks;
    public $failedTasks;
    public $budget;


    public function __construct()
    {
        $this->calendarModel = new CalendarModel();
        $this->userModel = new UserModel();

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['newDate'])) // click on a calender date
        {
            $this->changeDate();
        } else if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['taskId'])) // get informations of the wanna be edit task
        {
            $this->getInfoTask();
        } else if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['moneyDown'])) {
            $this->moneyDownFromBudget();
        } else if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_GET['taskId'])) {
            $this->editTask();
        } else if ($_SERVER['REQUEST_METHOD'] == "POST") // complete the form 
        {
            $this->addNewTask();
        } else {
            $datetime = new DateTime();
            $this->date = $datetime->format('Y-m-d');
            $this->showTasksOfDay($this->date, "waiting");
            $this->waitingTasks = $this->showTasks;
            $this->showTasksOfDay($this->date, "done");
            $this->doneTasks = $this->showTasks;
            $this->showTasksOfDay($this->date, "failed");
            $this->failedTasks = $this->showTasks;
            $this->showImportantFuturTasks();
            $this->importantTasks = $this->showTasks;
            $this->changeNotCompletedTasks();
            $this->displayBudget();
        }
    }

    /**
     * Receive the date clicked by the user and change $this->date (connected to AJAX)
     * 
     * @return string
     */
    public function changeDate()
    {
        $this->date = $_POST['newDate'];
        $this->showTasksOfDay($this->date, "waiting");
        $this->waitingTasks = $this->showTasks;
        $this->showTasksOfDay($this->date, "done");
        $this->doneTasks = $this->showTasks;
        $this->showTasksOfDay($this->date, "failed");
        $this->failedTasks = $this->showTasks;
        $this->showTasks = [
            "waitingTasks" => $this->waitingTasks,
            "doneTasks" => $this->doneTasks,
            "failedTasks" => $this->failedTasks
        ];
    }

    /**
     * Display all the tasks from a date
     * 
     * @param string $date
     * @param string $status
     * @return array
     */
    public function showTasksOfDay(string $date, string $status)
    {
        $query = $this->calendarModel->showTasksOfDay($_SESSION['user']['id'], $date, $status);
        $this->showTasks = $query->fetchAll();
    }

    /**
     * Display all the important futur tasks
     * 
     * @return array
     */
    public function showImportantFuturTasks()
    {
        $query = $this->calendarModel->showImportantFuturTasks($_SESSION['user']['id']);
        $this->showTasks = $query->fetchAll();
    }

    /**
     * Add a new task in the database for the concerned user
     *  
     * @return void
     */
    public function addNewTask(): void
    {
        $this->title = $_POST['title'];
        $this->content = $_POST['content'];
        $this->priority = $_POST['priority'];
        $this->date = $_POST['date'];

        $this->calendarModel->addNewTask($this->title, $this->content, $this->priority, $this->date, $_SESSION['user']['id']);

        // Redirect to the calendar page
        Router::redirectTo('calendar');
        exit();
    }

    /**
     * Delete the selected task
     * 
     * @return void
     */
    public function deleteTask(): void
    {
        $task_id = $_GET['id'];
        $this->calendarModel->deleteTask($task_id);

        // Redirect to the calendar page
        Router::redirectTo('calendar');
        exit();
    }

    /**
     * Get the informations of the selected task (connected to AJAX)
     * 
     * @return string
     */
    public function getInfoTask()
    {
        $task_id = $_POST['taskId'];

        $query = $this->calendarModel->showTask($task_id);
        $this->editTask = $query->fetch();
    }
    /**
     * Edit the selected task
     * 
     * @return void
     */
    public function editTask(): void
    {
        $this->task_id = $_GET['taskId'];
        $this->title = $_POST['title'];
        $this->content = $_POST['content'];
        $this->priority = $_POST['priority'];
        $this->date = $_POST['date'];

        $this->calendarModel->editTask($this->task_id, $this->title, $this->content, $this->priority, $this->date);

        // Redirect to the calendar page
        Router::redirectTo('calendar');
        exit();
    }

    /**
     * Display the budget of the user
     * 
     * @return array
     */
    public function displayBudget()
    {
        $query = $this->userModel->loginUser($_SESSION['user']['mail']);
        $result = $query->fetch();
        $this->budget = $result['budget'];

        return $this->budget;
    }

    /**
     * Change the status of the selected task
     * 
     * @return void
     */
    public function changeStatus(): void
    {
        $status = $_GET['status'];
        $task_id = $_GET['id'];
        $priority = $_GET['priority'];

        $this->changeStatusTaskAndBudget($status, $task_id, $priority);

        $model = $this->userModel->loginUser($_SESSION['user']['mail']);
        $connexion_user = $model->fetch();
        $_SESSION['user'] = $connexion_user;

        // Redirect to the calendar page
        Router::redirectTo('calendar');
        exit();
    }

    /**
     * Change the status of a task and remove or add money in the budget in result
     *
     * @param string $status
     * @param int $task_id
     * @param int $priority
     * @return void
     */
    public function changeStatusTaskAndBudget(string $status, int $task_id, int $priority)
    {
        $this->calendarModel->changeStatusTask($status, $task_id);

        $this->budget = number_format($this->budget, 2);


        switch ($status) {
            case 'done':
                switch ($priority) {
                    case 1:
                        $this->budget += 0.5;
                        break;
                    case 2:
                        $this->budget += 1;
                        break;
                    case 3:
                        $this->budget += 3;
                        break;
                    case 4:
                        $this->budget += 5;
                        break;
                }
                break;
            case 'failed':
                switch ($priority) {
                    case 1:
                        $this->budget -= 0.50;
                        break;
                    case 2:
                        $this->budget -= 1;
                        break;
                    case 3:
                        $this->budget -= 3;
                        break;
                    case 4:
                        $this->budget -= 5;
                        break;
                }
                break;
        }

        if ($this->budget < 0) {
            $this->budget = 0;
        }

        $this->userModel->changeBudget($this->budget, $_SESSION['user']['id']);
    }

    /**
     * Change the status of a task in "failed" if it's not completed by the end of its date and money are remove from the budget in result
     *
     * @return void
     */
    public function changeNotCompletedTasks()
    {
        $query = $this->calendarModel->tasksNotCompleted($this->date, $_SESSION['user']['id']);
        $notCompletedTasks = $query->fetchAll();

        for ($i = 0; $i < count($notCompletedTasks); $i++) {
            $this->changeStatusTaskAndBudget('failed', $notCompletedTasks[$i]['id'], $notCompletedTasks[$i]['priority']);
        }
    }

    /**
     * Remove the amount selected by the user from the budget
     *
     * @return void
     */
    public function moneyDownFromBudget()
    {
        $moneyDown = $_POST['moneyDown'];
        $this->budget = number_format($_SESSION['user']['budget'], 2);

        $this->budget -= $moneyDown;

        if ($this->budget < 0) {
            $this->budget = 0;
        }
        $this->userModel->changeBudget($this->budget, $_SESSION['user']['id']);

        $model = $this->userModel->loginUser($_SESSION['user']['mail']);
        $connexion_user = $model->fetch();
        $_SESSION['user'] = $connexion_user;

        // Redirect to the calendar page
        Router::redirectTo('calendar');
        exit();
    }
}
