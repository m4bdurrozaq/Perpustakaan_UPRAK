<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class Cors implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Handle preflight requests
        if ($request->getMethod() === 'options') {
            $response = service('response');
            $origin = $request->getHeaderLine('Origin');
            // Allow localhost and local network IPs for development
            if ($this->isAllowedOrigin($origin)) {
                $response->setHeader('Access-Control-Allow-Origin', $origin);
            }
            $response->setHeader('Access-Control-Allow-Headers', 'X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization');
            $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE, PATCH');
            $response->setHeader('Access-Control-Allow-Credentials', 'true');
            $response->setStatusCode(200);
            return $response;
        }

        return $request;
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $origin = $request->getHeaderLine('Origin');

        // Allow all localhost origins for development
        if ($this->isAllowedOrigin($origin)) {
            $response->setHeader('Access-Control-Allow-Origin', $origin);
        } else {
            // Allow all origins for development (remove in production)
            $response->setHeader('Access-Control-Allow-Origin', '*');
        }

        $response->setHeader('Access-Control-Allow-Headers', 'X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization');
        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE, PATCH');
        $response->setHeader('Access-Control-Allow-Credentials', 'true');

        return $response;
    }

    private function isAllowedOrigin(string $origin): bool
    {
        // Allow all localhost origins for development
        if (strpos($origin, 'localhost') !== false || strpos($origin, '127.0.0.1') !== false) {
            return true;
        }

        // Allow any port on localhost (including 3000 for npx serve)
        if (preg_match('/^http:\/\/(localhost|127\.0\.0\.1)(:\d+)?$/', $origin)) {
            return true;
        }

        return false;
    }
}