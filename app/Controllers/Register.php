<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class Register extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[5]',
            'email'    => 'required|valid_email|is_unique[users.email]',
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'status'  => 'error',
                'message' => $this->validator->getErrors()
            ], 400);
        }

        $model = new UserModel();
        $model->save([
            'username' => $this->request->getVar('username'),
            'password' => $this->request->getVar('password'), // auto hash (UserModel)
            'email'    => $this->request->getVar('email'),
        ]);

        return $this->respond([
            'status'  => 'success',
            'message' => 'User berhasil dibuat!'
        ], 200);
    }
}
