<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

// Runs before any route that has ['filter' => 'auth'] (see app/Config/Routes.php).
// If the visitor is not logged in, they are sent to the login page instead.
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('isLoggedIn')) {
            return null; // logged in: let the request continue
        }

        // Remember the page they wanted (only for plain page visits) so we can
        // send them back there after they log in.
        if (strtolower($request->getMethod()) === 'get') {
            session()->set('intended', (string) current_url());
        }

        return redirect()->to('/login')->with('error', 'Please log in to do that.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do after the request.
    }
}
