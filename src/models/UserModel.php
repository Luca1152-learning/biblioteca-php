<?php

class UserModel
{
    public ?int $user_id;
    public ?string $email;
    public ?string $hashedPassword;
    public ?string $first_name;
    public ?string $last_name;
    public ?string $sign_up_date;
    public ?bool $verified_email;
    public ?string $verify_email_url;
    public $role;
    public $borrows;

    public function __construct(
        string $email = null, string $password = null,
        string $first_name = null, string $last_name = null
    )
    {
        $this->email = $email;
        $this->hashedPassword = ($password != null) ? hash("sha256", $password) : null;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }
}

?>
