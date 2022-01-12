<?php
include_once __DIR__ . "/../../controllers/UserController.php";
require_once __DIR__ . '/../../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

class SecurityHelper
{
    public static function update_session()
    {
        // Make sure the session is up to date (in case of updates/deletions from the admin dashboard)
        if (!self::is_logged_in()) {
            session_destroy(); // Clean the session anyways
            return;
        }

        $user_controller = new UserController();
        $current_user = $user_controller->get_by_id($_SESSION["user_id"]);
        if (!$current_user) {
            // The user was deleted
            session_destroy();
            return;
        }

        // Update session
        $_SESSION["user_id"] = $current_user->user_id;
        $_SESSION["user_email"] = $current_user->email;
        $_SESSION["user_role"] = $current_user->role;
        $_SESSION["user_first_name"] = $current_user->first_name;
        $_SESSION["user_last_name"] = $current_user->last_name;
        $_SESSION["user_sign_up_date"] = $current_user->sign_up_date;
        $_SESSION["user_verified_email"] = $current_user->verified_email;
        $_SESSION["user_verify_email_url"] = $current_user->verify_email_url;
        $_SESSION["borrows"] = $current_user->borrows;
    }

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

    public static function assert_is_admin()
    {
        if (!self::is_admin()) self::redirect_to_403();
    }

    public static function assert_is_logged_in()
    {
        if (!self::is_logged_in()) self::redirect_to_403();
    }

    public static function assert_is_librarian()
    {
        if (!self::is_librarian()) self::redirect_to_403();
    }

    public static function send_mail_verification_email($first_name, $email_address, $code)
    {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465;
        $mail->IsHTML(true);
        $mail->Username = "biblioteca.php.2022@gmail.com";
        $mail->Password = "Jjnss2X&YadrEkD^JVRW";
        $mail->SetFrom("biblioteca.php.2022@gmail.com");
        $mail->Subject = "Biblioteca Lib - Verifica-ti adresa de mail";
        $mail->Body = nl2br(
            "Salut {$first_name},\n\nÎți poți verifica adresa de email apăsând " .
            "<a href='https://biblioteca-php.herokuapp.com/verifica-mail.php?cod={$code}'>aici</a>.\n\nMulțumim,\nEchipa Biblioteca Lib"
        );
        $mail->AddAddress($email_address);
        $mail->Send();
    }

    public static function is_mail_verified()
    {
        return self::is_logged_in() && $_SESSION["user_verified_email"] == true;
    }
}

?>
