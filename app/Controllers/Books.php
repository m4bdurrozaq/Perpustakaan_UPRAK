<?php
namespace App\Controllers;

use App\Models\BookModel;
use CodeIgniter\API\ResponseTrait;

class Books extends BaseController
{
    use ResponseTrait;

    public function __construct()
    {
        helper(['form', 'url']);
    }
    
    public function index()
    {
        $model = new BookModel();
        $books = $model->getBooks();
        
        return $this->respond([
            'status' => 'success',
            'data' => $books
        ]);
    }
    
    public function show($id = null)
    {
        $model = new BookModel();
        $book = $model->getBook($id);
        
        if ($book) {
            return $this->respond([
                'status' => 'success',
                'data' => $book
            ]);
        }
        
        return $this->respond([
            'status' => 'error',
            'message' => 'Buku tidak ditemukan'
        ], 404);
    }
    
    public function create()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Harus login terlebih dahulu'
            ], 401);
        }

        $model = new BookModel();
        $data = [
            'judul_buku' => $this->request->getVar('judul_buku'),
            'penerbit' => $this->request->getVar('penerbit'),
            'tahun_terbit' => $this->request->getVar('tahun_terbit'),
            'created_by' => $session->get('user_id')
        ];

        if ($model->save($data)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Buku berhasil ditambahkan',
                'data' => $data
            ]);
        }

        return $this->respond([
            'status' => 'error',
            'message' => 'Gagal menambahkan buku'
        ], 400);
    }
    
    public function update($id = null)
    {
        $session = session();

        if (!$session->get('logged_in')) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Harus login terlebih dahulu'
            ], 401);
        }

        $model = new BookModel();
        $data = [
            'judul_buku' => $this->request->getVar('judul_buku'),
            'penerbit' => $this->request->getVar('penerbit'),
            'tahun_terbit' => $this->request->getVar('tahun_terbit')
        ];

        if ($model->update($id, $data)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Buku berhasil diupdate'
            ]);
        }

        return $this->respond([
            'status' => 'error',
            'message' => 'Gagal mengupdate buku'
        ], 400);
    }

    public function delete($id = null)
    {
        $session = session();

        if (!$session->get('logged_in')) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Harus login terlebih dahulu'
            ], 401);
        }

        $model = new BookModel();
        if ($model->delete($id)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Buku berhasil dihapus'
            ]);
        }

        return $this->respond([
            'status' => 'error',
            'message' => 'Gagal menghapus buku'
        ], 400);
    }
}