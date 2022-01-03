<?php

class UserModel
{
    public ?string $email;
    public ?string $hashedPassword;
    public ?string $first_name;
    public ?string $last_name;
    public ?string $role;

    public function __construct(
        string $email = null, string $password = null,
        string $first_name = null, string $last_name = null,
        string $role = null
    )
    {
        $this->email = $email;
        $this->hashedPassword = hash("sha256", $password);
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }
}

?>
