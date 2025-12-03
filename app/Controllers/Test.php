<?php namespace App\Controllers;

class Test extends BaseController
{
    public function index()
    {
        try {
            $db = db_connect();
            $db->query('SELECT 1');
            
            return $this->responseJSON([
                'status' => true,
                'message' => 'Database connected successfully'
            ]);
        } catch (\Exception $e) {
            return $this->responseJSON([
                'status' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function users()
    {
        try {
            // Cek apakah model UserModel ada
            if (!class_exists('\App\Models\UserModel')) {
                return $this->responseJSON([
                    'status' => false,
                    'message' => 'UserModel tidak ditemukan'
                ], 404);
            }
            
            $userModel = new \App\Models\UserModel();
            $users = $userModel->findAll();
            
            // Hapus password
            $usersData = array_map(function($user) {
                if (isset($user['password'])) {
                    unset($user['password']);
                }
                return $user;
            }, $users);

            return $this->responseJSON([
                'status' => true,
                'data' => $usersData,
                'count' => count($usersData)
            ]);

        } catch (\Exception $e) {
            return $this->responseJSON([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function books()
    {
        try {
            // Option 1: Menggunakan model buku jika ada
            if (class_exists('\App\Models\BookModel')) {
                $bookModel = new \App\Models\BookModel();
                $books = $bookModel->findAll();
                
                return $this->responseJSON([
                    'status' => true,
                    'message' => 'Books retrieved successfully',
                    'data' => $books,
                    'count' => count($books)
                ]);
            }
            
            // Option 2: Menggunakan database query langsung
            $db = db_connect();
            $tables = $db->listTables();
            
            // Cek apakah tabel 'books' ada
            if (in_array('books', $tables)) {
                $books = $db->table('books')->get()->getResultArray();
                
                return $this->responseJSON([
                    'status' => true,
                    'message' => 'Books retrieved successfully',
                    'data' => $books,
                    'count' => count($books)
                ]);
            }
            
            // Option 3: Data dummy jika tabel tidak ada
            return $this->responseJSON([
                'status' => true,
                'message' => 'Test books API berhasil!',
                'note' => 'Tabel books tidak ditemukan, menampilkan data dummy',
                'data' => [
                    [
                        'id' => 1,
                        'title' => 'Test Book 1',
                        'author' => 'Author 1',
                        'year' => 2024
                    ],
                    [
                        'id' => 2,
                        'title' => 'Test Book 2',
                        'author' => 'Author 2',
                        'year' => 2024
                    ]
                ],
                'timestamp' => date('Y-m-d H:i:s'),
                'endpoint' => '/api/test/books'
            ]);
            
        } catch (\Exception $e) {
            return $this->responseJSON([
                'status' => false,
                'message' => 'Error retrieving books: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // HAPUS METHOD INI (karena sudah ada di BaseController)
    // private function responseJSON($data, $statusCode = 200)
    // {
    //     $response = service('response');
    //     $response->setStatusCode($statusCode);
    //     $response->setHeader('Content-Type', 'application/json');
    //     return $response->setJSON($data);
    // }
}