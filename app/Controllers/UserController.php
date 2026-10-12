<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CustomerModel;

class UserController extends BaseController
{
    public function register()
    {
        return view('home');
    }

    public function store()
    {
        // Check the form data first
        $rules = [
            'firstName' => 'required|max_length[100]',
            'lastName'  => 'required|max_length[100]',
            'birthdate' => 'required|valid_date',
            'gender'    => 'required|in_list[female,male,not-say]',
            'email'     => 'required|valid_email|max_length[100]|is_unique[customers.email]',
            'phone'     => 'required|numeric|exact_length[11]',
            'address'   => 'required|max_length[200]',
            'username'  => 'required|min_length[3]|max_length[20]|is_unique[users.username]',
            'password'  => 'required|min_length[8]',
            'confirm'   => 'required|matches[password]',
            'terms'     => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // The form sends female / male / not-say, the database wants these words
        $genderMap = [
            'female'  => 'Female',
            'male'    => 'Male',
            'not-say' => 'Prefer not to say',
        ];

        $userModel     = new UserModel();
        $customerModel = new CustomerModel();
        $db            = \Config\Database::connect();

        // Save to users and customers together. If one fails, none is saved.
        $db->transStart();

        $userModel->insert([
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            // role is not set here, so the database default 'customer' is used
        ]);
        $userId = $userModel->getInsertID();

        $customerModel->insert([
            'user_id'    => $userId,
            'firstname'  => $this->request->getPost('firstName'),
            'middlename' => $this->request->getPost('middleName') ?: null,
            'lastname'   => $this->request->getPost('lastName'),
            'birthdate'  => $this->request->getPost('birthdate'),
            'gender'     => $genderMap[$this->request->getPost('gender')],
            'email'      => $this->request->getPost('email'),
            'phonenum'   => $this->request->getPost('phone'),
            'address'    => $this->request->getPost('address'),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('errors', ['Registration failed. Please try again.']);
        }

        return $this->response->redirect('/loggedin');
    }

    public function loggedin()
    {
        return view('dashboard');
    }
}
