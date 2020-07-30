<?php

session_start();

// The autoload method is only called if the file called exist applying to the library's files and the controllers' files
spl_autoload_register(function ($className) {
    $className = str_replace("\\", "/", $className);

    if (file_exists("library/$className.php")) {
        require_once("library/$className.php");
    } else if (file_exists("application/controllers/$className.php")) {
        require_once("application/controllers/$className.php");
    } else if (file_exists("application/models/$className.php")) {
        require_once("application/models/$className.php");
    }
});

require_once('library/Router.php');
require_once('library/autoload.php');
