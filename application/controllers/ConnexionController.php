<?php

class ConnexionController extends Controller
{
    /* Initialise the form with blank */
    public $model;
    public $errors = [];
    public $mail = '';
    public $password = '';

    public function __construct()
    {
        $this->model = new UserModel();

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->getUserInformations();
        }
    }

    /**
     * Check if the informations entered by the user are correct
     * 
     * @return void
     */
    public function getUserInformations(): void
    {
        $this->mail = $_POST['mail'];
        $this->password = $_POST['password'];

        if (!empty($this->password) && !empty($this->mail)) {
            $model = $this->model->loginUser($this->mail);
            $connexion_user = $model->fetch();

            if (!empty($connexion_user)) {
                if (password_verify($this->password, $connexion_user['password'])) {
                    $_SESSION['user'] = $connexion_user;

                    // Redirect to the index page
                    Router::redirectTo('home');
                    exit();
                } else {
                    array_push($this->errors, "Votre mot de passe n'est pas bon");
                }
            } else {
                array_push($this->errors, "Ce mail n'est pas enregistré dans notre base de données");
            }
        } else {
            array_push($this->errors, "Vous n'avez pas bien rempli les bons champs, veuillez recommencer");
        }
    }
}
