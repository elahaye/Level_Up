<?php

class RegisterController extends Controller
{
    /* Initialise the form with blank */
    public $model;
    public $errors = [];
    public $selected = '';
    public $firstname = '';
    public $lastname = '';
    public $nickname = '';
    public $phone = '';
    public $birthDay = '';
    public $birthMonth = '';
    public $birthYear = '';
    public $address = '';
    public $postCode = '';
    public $city = '';
    public $mail = '';
    public $password = '';

    public $key;
    public $emailKey;
    public $nicknameKey;

    public function __construct()
    {
        $this->model = new UserModel();

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['key'])) {
            $this->emailExists();
            $this->nicknameExists();
        } else if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->checkingForm();
        }
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
     * Check all elements in the form and answer by the appropriate response
     * 
     * @return void
     */
    public function checkingForm(): void
    {
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
        $this->password = $_POST['password'];

        if (empty($this->firstname) || empty($this->lastname) || empty($this->nickname) || empty($this->phone) || empty($this->address) || empty($this->postCode) || empty($this->city) || empty($this->mail) || empty($this->password)) {
            array_push($this->errors, "Vous n'avez pas bien rempli les champs demandés, veuillez recommencer.");
        } else {
            if ($this->model->nicknameExists($this->nickname) == true) {
                array_push($this->errors, "Ce pseudonyme est déjà utilisé, veuillez en saisir un nouveau.");
            }
            if (strlen($this->phone) !== 10) {
                array_push($this->errors, "Vous n'avez pas bien rempli le champ 'Téléphone', celui-ci doit contenir 10 caractères pour être valide.");
            }
            if (strlen($this->postCode) !== 5) {
                array_push($this->errors, "Vous n'avez pas bien rempli le champ 'Adresse', celui-ci doit contenir 5 caractères pour être valide.");
            }
            if (strlen($this->password) < 8) {
                array_push($this->errors, "Le mot de passe doit au moins contenir 8 caractères pour être valide.");
            }

            if (strlen($this->phone) == 10 && strlen($this->postCode) == 5 && strlen($this->password) >= 8) {
                // check if the email and the nickname are already taken 
                if ($this->model->emailExists($this->mail) == true) {
                    array_push($this->errors, "Ce mail est déjà utilisé, veuillez en saisir un nouveau ou vous connecter directement.");
                } else {
                    $this->password = password_hash($this->password, PASSWORD_BCRYPT);
                    $this->model->addNewUser($this->firstname, $this->lastname, $this->nickname, $dateOfBirth, $this->phone, $this->address, $this->postCode, $this->city, $this->mail, $this->password);

                    // Redirect to the connexion page
                    Router::redirectTo('connexion');
                    exit();
                }
            }
        }
    }
}
