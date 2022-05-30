<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if ($session->has('isLoggedIn')) {
            if ($session->agen_host !== $_SERVER['REMOTE_ADDR'] || $session->browser !== $_SERVER['HTTP_USER_AGENT']) {
                $session->destroy();
                return redirect()->to('/auth/login');
            }
        } else {
            return redirect()->to('/auth/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
