<?php

class SecurityHelper
{
    public static function is_librarian()
    {
        return self::is_logged_in() && $_SESSION["user_role"] == "bibliotecar";
    }

    public static function is_logged_in()
    {
        return isset($_SESSION) && isset($_SESSION["user_id"]);
    }

    public static function is_admin()
    {
        return self::is_logged_in() && $_SESSION["user_role"] == "administrator";
    }

    public static function redirect_to_403()
    {
        header('Location: /eroare-403.php');
        exit();
    }

    public static function redirect_to_404()
    {
        header('Location: /eroare-404.php');
        exit();
    }
}

?>
