<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication_model extends CI_Model
{
    protected $table = 'users';

    public function __construct()
    {
        parent::__construct();
    }

    public function login($email, $password)
    {
        $user = $this->db
            ->where('email', $email)
            ->limit(1)
            ->get($this->table)
            ->row();

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user->password)) {
            return false;
        }

        $this->db
            ->where('id', $user->id)
            ->update($this->table, [
                'last_login_at' => date('Y-m-d H:i:s')
            ]);

        return $user;
    }
}
