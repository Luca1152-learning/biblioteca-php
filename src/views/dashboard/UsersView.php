<?php
include_once __DIR__ . '/../../controllers/UserController.php';

$user_controller = new UserController();

class UsersView
{
    public function render_users_table()
    {
        global $user_controller;
        $users = $user_controller->get_all();
        print_r($users);
    }
}