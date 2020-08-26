<?php

// The router file get information from the url and redirect the user on the concerned page

class Router
{

    public static $validRoutes = array();
    public static $url;

    /**
     * Redirect the user on $url
     * 
     * @param string $route
     * @param function $function
     * @return void
     */
    public static function set(string $route, $function)
    {
        // Get the parameter of the url on the adress and put it on an array 
        self::$validRoutes[] = $route;

        if ($_GET['url'] === $route) {
            // Magic method - use an object as a function
            $function->__invoke();
        }
    }

    /**
     * Redirect the user on $url
     * 
     * @param string $url
     * @return void
     */
    public static function redirectTo(string $url): void
    {
        header('Location:' . $url);
        exit();
    }

    /**
     * Redirect the pahe to the homme page if the url given is not found
     *
     * @return void
     */
    public static function NotFoundPage()
    {
        if (!in_array($_GET['url'], self::$validRoutes)) {
            self::redirectTo('home');
        }
    }
}

// Home Page
Router::set('home', function () {
    $controller = new ArticleController();
    $controller->createView('HomeView');
});

// Register Page
Router::set('register', function () {
    $controller = new RegisterController();
    $controller->createView('RegisterView');
});
Router::set('emailExists', function () {
    $controller = new RegisterController();
    $controller->emailExists();
    $controller->returnJson($controller->emailKey);
});
Router::set('nicknameExists', function () {
    $controller = new RegisterController();
    $controller->nicknameExists();
    $controller->returnJson($controller->nicknameKey);
});

// Connexion Page
Router::set('connexion', function () {
    $controller = new ConnexionController();
    $controller->createView('ConnexionView');
});

// Logout Page
Router::set('logout', function () {
    $controller = new LogoutController();
});

// Calendar Page
Router::set('calendar', function () {
    $controller = new CalendarController();
    $controller->createView('CalendarView');
});
Router::set('changeDate', function () {
    $controller = new CalendarController();
    $controller->changeDate();
    $controller->returnJson($controller->showTasks);
});
Router::set('deleteTask', function () {
    $controller = new CalendarController();
    $controller->deleteTask();
});
Router::set('editTask', function () {
    $controller = new CalendarController();
    $controller->getInfoTask();
    $controller->returnJson($controller->editTask);
});
Router::set('changeStatus', function () {
    $controller = new CalendarController();
    $controller->changeStatus();
});

// Articles User Page 
Router::set('articles', function () {
    $controller = new ArticleController();
    $controller->createView('ArticlesUserView');
});
Router::set('articleDetails', function () {
    $controller = new ArticleController();
    $controller->createView('ArticleDetailsView');
});
Router::set('addComment', function () {
    $controller = new ArticleController();
    $controller->addComment();
});

// Contact Page
Router::set('contact', function () {
    $controller = new ArticleController();
    $controller->createView('ContactView');
});

// Personnal Informations Page
Router::set('userDetails', function () {
    $controller = new UsersController();
    $controller->createView('UserDetailsView');
});
Router::set('editUser', function () {
    $controller = new UsersController();
    $controller->createView('UpdateUserView');
});
Router::set('editPassword', function () {
    $controller = new UsersController();
    $controller->createView('UpdatePasswordView');
});


// Admin Page
Router::set('admin', function () {
    $controller = new AdminController();
    $controller->createView('AdminView');
});

// Users admin Page
Router::set('listUsers', function () {
    $controller = new UsersController();
    $controller->createView('UsersView');
});
Router::set('editStatusUser', function () {
    $controller = new UsersController();
    $controller->editStatusUser();
});
Router::set('deleteUser', function () {
    $controller = new UsersController();
    $controller->deleteUser();
});

// Articles admin page
Router::set('listArticles', function () {
    $controller = new ArticleController();
    $controller->createView('ArticlesAdminView');
});
Router::set('upsertArticle', function () {
    $controller = new ArticleController();
    $controller->createView('UpsertArticleView');
});
Router::set('deleteArticle', function () {
    $controller = new ArticleController();
    $controller->deleteArticle();
});

// Categories admin page
Router::set('listCategories', function () {
    $controller = new CategoryController();
    $controller->createView('CategoriesView');
});
Router::set('upsertCategory', function () {
    $controller = new CategoryController();
    $controller->createView('UpsertCategoryView');
});
Router::set('deleteCategory', function () {
    $controller = new CategoryController();
    $controller->deleteCategory();
});

// Comments admin page
Router::set('listComments', function () {
    $controller = new CommentController();
    $controller->createView('CommentsAdminView');
});
Router::set('detailsComment', function () {
    $controller = new CommentController();
    $controller->createView('CommentDetailsAdminView');
});
Router::set('deleteComment', function () {
    $controller = new CommentController();
    $controller->deleteComment();
});

// Not Found Page / 404 Error
Router::NotFoundPage();
