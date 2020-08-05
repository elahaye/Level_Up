<?php

class UserModel extends Model
{
    /**
     * Display all the users
     * 
     * @return array
     */
    public function displayAllUsers()
    {
        $display_users = $this->pdo->query('SELECT id, nickname, mail, status FROM `users`');

        return $display_users;
    }

    /**
     * Display one user by his id
     * 
     * @param int $user_id
     * 
     * @return string
     */
    public function displayOneUser(string $user_id)
    {
        $query = $this->pdo->prepare('SELECT nickname FROM `users` WHERE id = :id');
        $query->execute(['id' => $user_id]);
        $display_user = $query->fetch();

        return $display_user;
    }

    /**
     * Insert a new user in tha database
     * 
     * @param string $firstname
     * @param string $lastname
     * @param string $nickname
     * @param string $dateOfBirth
     * @param int $phone
     * @param string $address
     * @param int $postCode
     * @param string $city
     * @param string $mail
     * @param string $password
     * @return void
     */
    public function addNewUser(string $firstname, string $lastname, string $nickname, string $dateOfBirth, int $phone, string $address, int $postcode, string $city, string $mail, string $password): void
    {
        $sql = 'INSERT INTO `users` 
                SET firstname = :firstname, lastname = :lastname, nickname = :nickname, dateOfBirth = :dateOfBirth, phone = :phone, address = :address, postcode = :postcode, city = :city, mail = :mail, password = :password, budget = "0", status = "user"';
        $add_user = $this->pdo->prepare($sql);
        $add_user->execute(compact('firstname', 'lastname', 'nickname', 'dateOfBirth', 'phone', 'address', 'postcode', 'city', 'mail', 'password'));
    }

    public function editUser(string $id, string $firstname, string $lastname, string $nickname, string $dateOfBirth, int $phone, string $address, int $postcode, string $city, string $mail)
    {
        $sql = 'UPDATE `users` 
        SET firstname = :firstname, lastname = :lastname, nickname = :nickname, dateOfBirth = :dateOfBirth, phone = :phone, address = :address, postcode = :postcode, city = :city, mail = :mail 
        WHERE id = :id';
        $update_user = $this->pdo->prepare($sql);
        $update_user->execute(compact('firstname', 'lastname', 'nickname', 'dateOfBirth', 'phone', 'address', 'postcode', 'city', 'mail', 'id'));
    }

    public function editPassword(string $id, string $password)
    {
        $sql = 'UPDATE `users`
        SET password = :password
        WHERE id = :id';
        $update_password = $this->pdo->prepare($sql);
        $update_password->execute(compact('password', 'id'));
    }

    /**
     * Delete a user
     * 
     * @param int $user_id
     * 
     * @return void
     */
    public function deleteUser($user_id): void
    {
        $sql = 'DELETE FROM `users` WHERE id = :id';
        $delete_user = $this->pdo->prepare($sql);
        $delete_user->execute(['id' => $user_id]);
    }

    /**
     * Return all the information of an user from his email (unique for each user)
     * 
     * @param string $mail
     * 
     * @return array
     */
    public function loginUser($mail)
    {
        $connexion_user = $this->pdo->prepare('SELECT * FROM `users` WHERE mail = :mail');
        $connexion_user->execute(['mail' => $mail]);

        return $connexion_user;
    }

    /**
     * Check if the mail exist in the database or not 
     * 
     * @param string $mail
     * @return bool
     */
    public function emailExists($mail)
    {
        $email_exists = $this->pdo->prepare('SELECT * FROM `users` WHERE mail = ?');
        $email_exists->execute([$mail]);
        $user = $email_exists->fetch();

        if (empty($user)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check if the nickname exist in the database or not 
     * 
     * @param string $nickname
     * @return bool
     */
    public function nicknameExists($nickname)
    {
        $nickname_exists = $this->pdo->prepare('SELECT * FROM `users` WHERE nickname = ?');
        $nickname_exists->execute([$nickname]);
        $user = $nickname_exists->fetch();

        if (empty($user)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Add or remove money from the budget of the user
     * 
     * @param float $budget
     * @param int $user_id
     * 
     * @return void  
     */
    public function changeBudget(float $budget, int $user_id): void
    {
        $sql = 'UPDATE `users` SET budget = :budget WHERE id = :id';
        $budget_change = $this->pdo->prepare($sql);
        $budget_change->execute(['budget' => $budget, 'id' => $user_id]);
    }

    /**
     * Change the status of the user
     * 
     * @param int $user_id
     * @param string $status
     * 
     * @return void
     */
    public function changeStatus(int $user_id, string $status): void
    {
        $sql = 'UPDATE `users` SET status = :status WHERE id = :id';
        $status_change = $this->pdo->prepare($sql);
        $status_change->execute(['id' => $user_id, 'status' => $status]);
    }
}
