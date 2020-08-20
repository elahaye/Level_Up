<?php

class UsersController extends Controller
{
    public $model;
    public $displayAllUsers;
    public $showUser;
    public $birthday;
    public $errors = [];

    public function __construct()
    {
        $this->model = new UserModel();

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['key'])) {
            $this->emailExists();
            $this->nicknameExists();
        } else if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_GET['updateUser'])) {
            $this->updateUser();
        } else if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_GET['updatePassword'])) {
            $this->updatePassword();
        } else {
            $this->displayAllUsers();
            $this->displayDateofBirth();
        }
    }

    /**
     * Display all the users
     *
     * @return void
     */
    public function displayAllUsers()
    {
        $model = $this->model->displayAllUsers();
        $this->displayAllUsers = $model->fetchAll();

        for ($i = 0; $i < count($this->displayAllUsers); $i++) {
            switch ($this->displayAllUsers[$i]['status']) {
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

    /**
     * Change the format of the date to fit in the edit form 
     *
     * @return void
     */
    public function displayDateofBirth()
    {
        $this->birthday = explode('-', $_SESSION['user']['dateOfBirth']);
    }

    /**
     * Edit the status of a user (basic, author, admin)
     *
     * @return void
     */
    public function editStatusUser()
    {
        $user_id = $_GET['id'];
        $status = $_POST['statusUser'];
        $this->model->changeStatus($user_id, $status);

        // Redirect to the users page
        Router::redirectTo('listUsers');
        exit();
    }

    /**
     * Delete a user
     *
     * @return void
     */
    public function deleteUser()
    {
        $user_id = $_GET['id'];
        $this->model->deleteUser($user_id);

        // Redirect to the users page
        Router::redirectTo('listUsers');
        exit();
    }

    /**
     * Check if the nickname is already in the database
     *
     * @return void
     */
    public function nicknameExists()
    {
        $key = $_POST['key'];
        $this->nicknameKey = $this->model->nicknameExists($key);
    }

    /**
     * Check if the mail is already in the database
     *
     * @return void
     */
    public function emailExists()
    {
        $key = $_POST['key'];
        $this->emailKey = $this->model->emailExists($key);
    }

    /**
     * Edit the selected user
     *
     * @return void
     */
    public function updateUser()
    {
        $this->id = $_SESSION['user']['id'];
        $this->firstname = $_POST['firstname'];
        $this->lastname = $_POST['lastname'];
        $this->nickname = $_POST['nickname'];
        $this->phone = str_replace(' ', '', $_POST['phone']);
        $this->birthDay = $_POST['birthDay'];
        $this->birthMonth = $_POST['birthMonth'];
        $this->birthYear = $_POST['birthYear'];
        $dateOfBirth = $this->birthYear . "-" . $this->birthMonth . "-" . $this->birthDay;
        $this->address = $_POST['address'];
        $this->postCode = $_POST['postCode'];
        $this->city = $_POST['city'];
        $this->mail = $_POST['mail'];

        if (empty($this->firstname) || empty($this->lastname) || empty($this->nickname) || empty($this->phone) || empty($this->address) || empty($this->postCode) || empty($this->city) || empty($this->mail)) {
            array_push($this->errors, "Vous n'avez pas bien rempli les champs demandés, veuillez recommencer.");
        } else {
            if ($this->model->nicknameExists($this->nickname) == true && $this->nickname !== $_SESSION['user']['nickname']) {
                array_push($this->errors, "Ce pseudonyme est déjà utilisé, veuillez en saisir un nouveau.");
            }
            if (strlen($this->phone) !== 10) {
                array_push($this->errors, "Vous n'avez pas bien rempli le champ 'Téléphone', celui-ci doit contenir 10 caractères pour être valide.");
            }
            if (strlen($this->postCode) !== 5) {
                array_push($this->errors, "Vous n'avez pas bien rempli le champ 'Adresse', celui-ci doit contenir 5 caractères pour être valide.");
            }

            if (strlen($this->phone) == 10 && strlen($this->postCode) == 5) {
                // check if the email and the nickname are already taken
                if ($this->model->emailExists($this->mail) == true && $this->mail !== $_SESSION['user']['mail']) {
                    var_dump($this->mail);
                    var_dump($_SESSION['user']['mail']);
                    array_push($this->errors, "Ce mail est déjà utilisé, veuillez en saisir un nouveau ou vous connecter directement.");
                } else {
                    $this->model->editUser($this->id, $this->firstname, $this->lastname, $this->nickname, $dateOfBirth, $this->phone, $this->address, $this->postCode, $this->city, $this->mail);

                    $model = $this->model->loginUser($this->mail);
                    $connexion_user = $model->fetch();

                    if (!empty($connexion_user)) {
                        $_SESSION['user'] = $connexion_user;

                        // Redirect to the profil page
                        Router::redirectTo('userDetails');
                        exit();
                    }
                }
            }
        }
    }

    /**
     * Edit the password of the selected user
     *
     * @return void
     */
    public function updatePassword()
    {
        $this->id = $_SESSION['user']['id'];
        $this->oldPassword = $_POST['oldPassword'];
        $this->newPassword = $_POST['newPassword'];
        $this->confirmationPassword = $_POST['confirmationPassword'];
        $hash = $_SESSION['user']['password'];

        if (password_verify($this->oldPassword, $hash)) {
            if ($this->newPassword === $this->confirmationPassword) {
                $this->password = password_hash($this->newPassword, PASSWORD_BCRYPT);
                $this->model->editPassword($this->id, $this->password);

                $model = $this->model->loginUser($_SESSION['user']['mail']);
                $connexion_user = $model->fetch();

                if (!empty($connexion_user)) {
                    $_SESSION['user'] = $connexion_user;

                    // Redirect to the connexion page
                    Router::redirectTo('logout');
                    exit();
                }
            } else {
                array_push($this->errors, 'Votre nouveau mot de passe et la confirmation ne correspondent pas.');
            }
        } else {
            array_push($this->errors, 'Le mot de passe actuel est invalide !');
        }
    }
}
