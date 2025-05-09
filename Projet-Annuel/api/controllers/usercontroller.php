<?php
class UserController
{
    private $db;
    private $user;

    public function __construct($db)
    {
        $this->db = $db;
        $this->user = new User($db);
    }

    public function register($data)
    {

        if (
            empty($data->first_name) ||
            empty($data->last_name) ||
            empty($data->email) ||
            empty($data->password) ||
            empty($data->role)
        ) {
            return [
                "success" => false,
                "message" => "Missing required fields"
            ];
        }


        $this->user->first_name = $data->first_name;
        $this->user->last_name = $data->last_name;
        $this->user->email = $data->email;
        $this->user->password = $data->password;
        $this->user->role = $data->role;
        $this->user->company_id = $data->company_id ?? null;


        if ($this->user->create()) {
            return [
                "success" => true,
                "message" => "User registered successfully"
            ];
        } else {
            return [
                "success" => false,
                "message" => "Unable to register user"
            ];
        }
    }

    public function login($data)
    {

        if (
            empty($data->email) ||
            empty($data->password)
        ) {
            return [
                "success" => false,
                "message" => "Missing email or password"
            ];
        }


        $this->user->email = $data->email;


        if ($this->user->auth()) {

            if (password_verify($data->password, $this->user->password)) {

                $token = bin2hex(random_bytes(16));

                return [
                    "success" => true,
                    "message" => "Login successful",
                    "token" => $token,
                    "user" => [
                        "id" => $this->user->id,
                        "first_name" => $this->user->first_name,
                        "last_name" => $this->user->last_name,
                        "role" => $this->user->role,
                        "company_id" => $this->user->company_id
                    ]
                ];
            } else {
                return [
                    "success" => false,
                    "message" => "Invalid password"
                ];
            }
        } else {
            return [
                "success" => false,
                "message" => "User not found"
            ];
        }
    }
}