<?php

class UsersController extends Controller 
{
    public $model;
    public $displayAllUsers;

    public function __construct()
    {
        $this->model = new UserModel();
        $this->displayAllUsers();
    }

    public function displayAllUsers() 
    {
        $model = $this->model->displayAllUsers();
        $this->displayAllUsers = $model->fetchAll();

        for ($i = 0; $i < count($this->displayAllUsers); $i++)
        {
            switch ($this->displayAllUsers[$i]['status']) 
            {
                case 'user':
                    $this->displayAllUsers[$i]['status'] = 'utilisateur de base';
                    break;
                case 'author':
                    $this->displayAllUsers[$i]['status'] = 'auteur';
                    break;
                case 'admin':
                    $this->displayAllUsers[$i]['status'] = 'administrateur';
                    break;
            }
        }
    }

    public function editStatusUser()
    {
        $user_id = $_GET['id'];
        $status = $_POST['statusUser'];
        $this->model->changeStatus($user_id, $status);

        // Redirect to the users page
        Router::redirectTo('listUsers');
        exit();
    }

    public function deleteUser()
    {
        $user_id = $_GET['id'];
        $this->model->deleteUser($user_id);

        // Redirect to the users page
        Router::redirectTo('listUsers');
        exit();
    }
}