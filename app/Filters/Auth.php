<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Allow OPTIONS requests (CORS preflight)
        if ($request->getMethod() === 'OPTIONS') {
            return $request;
        }

        // Get current URI
        $uri = $request->getUri();
        $path = $uri->getPath();

        // Allow access to login page and auth endpoints (except check and logout)
        if ($path === '/' || $path === '/index.html' || $path === '/auth/login') {
            return $request;
        }

        // Check if user is logged in
        $session = session();
        if (!$session->get('logged_in')) {
            // For API requests, return 401 JSON
            $response = service('response');
            $response->setStatusCode(401);
            $response->setJSON([
                'status' => 'error',
                'message' => 'Harus login terlebih dahulu'
            ]);
            return $response;
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}