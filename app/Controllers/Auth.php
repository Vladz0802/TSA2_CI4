<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{
    // Shows the login form (/login). Someone already logged in goes to the task list.
    public function login(): string|RedirectResponse
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/tasks');
        }

        return view('login', ['title' => 'Login']);
    }

    // Checks the submitted username and password.
    public function attempt(): RedirectResponse
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/login')
                ->with('errors', $this->validator->getErrors())
                ->with('old_username', (string) $this->request->getPost('username'));
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $user = (new UserModel())->findByUsername($username);

        // password_verify() compares the typed password with the stored hash.
        // One message for both "no such user" and "wrong password", so a
        // visitor cannot find out which usernames exist.
        if ($user === null || empty($user['password']) || ! password_verify($password, $user['password'])) {
            return redirect()->to('/login')
                ->with('error', 'Invalid username or password.')
                ->with('old_username', $username);
        }

        // New session id after login (protects against session fixation).
        session()->regenerate();
        session()->set([
            'isLoggedIn' => true,
            'userId'     => $user['id'],
            'username'   => $user['username'],
            'fullName'   => $user['full_name'],
        ]);

        // Go back to the page they were trying to open, or the task list.
        $to = session()->get('intended') ?: site_url('tasks');
        session()->remove('intended');

        return redirect()->to($to)->with('success', 'Welcome back, ' . $user['full_name'] . '!');
    }

    // Logs out (POST only, with a CSRF token, so other sites cannot trigger it).
    public function logout(): RedirectResponse
    {
        session()->remove(['isLoggedIn', 'userId', 'username', 'fullName', 'intended']);
        session()->regenerate(true); // fresh session id, old session data destroyed

        return redirect()->to('/')->with('success', 'You have been logged out.');
    }
}
