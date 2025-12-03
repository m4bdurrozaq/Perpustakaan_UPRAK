<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class Auth extends BaseController
{
    use ResponseTrait;
    
    public function login()
    {
        $model = new UserModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $model->getUser($username);

        if ($user && password_verify($password, $user['password'])) {

            session()->set([
                'logged_in' => true,
                'user_id'   => $user['id'],
                'username'  => $user['username']
            ]);

            return $this->respond([
                'status' => 'success',
                'message' => 'Login berhasil',
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username']
                ]
            ]);
        }

        return $this->respond([
            'status' => 'error',
            'message' => 'Username atau password salah'
        ], 401);
    }

    public function registerForm()
{
    return view('auth/register');
}

  public function register()
{
    $model = new UserModel();

    $data = [
        'username' => $this->request->getVar('username'),
        'email'    => $this->request->getVar('email'),
        'password' => $this->request->getVar('password'),
    ];

    if ($model->insert($data)) {
        return $this->respond([
            'status' => 'success',
            'message' => 'Register berhasil!'
        ], 200);
    }

    return $this->respond([
        'status' => 'error',
        'message' => 'Gagal register'
    ], 400);
}


    
    public function logout()
    {
        session()->destroy();
        
        return $this->respond([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ]);
    }
    
    public function checkAuth()
    {
        if (session()->get('logged_in')) {
            return $this->respond([
                'status' => 'success',
                'user' => [
                    'id' => session()->get('user_id'),
                    'username' => session()->get('username')
                ]
            ]);
        }

        return $this->respond([
            'status' => 'error',
            'message' => 'Not authenticated'
        ], 401);
    }
}
