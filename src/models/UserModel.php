<?php

class UserModel
{
    public ?string $email;
    public ?string $hashedPassword;
    public ?string $first_name;
    public ?string $last_name;
    public ?int $user_id;
    public ?string $role;
    public ?string $sign_up_date;
    public ?string $last_online_date;

    public function __construct(
        string $email = null, string $password = null,
        string $first_name = null, string $last_name = null
    )
    {
        $this->email = $email;
        $this->hashedPassword = hash("sha256", $password);
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }
}

?>
