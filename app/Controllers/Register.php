<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * Customer self-registration. Employees and admins do NOT sign up here;
 * their accounts are created by HR / an administrator (client requirement).
 */
class Register extends BaseController
{
    private function rules(): array
    {
        return [
            'first_name'  => ['label' => 'First name',  'rules' => 'required|max_length[60]'],
            'middle_name' => ['label' => 'Middle name', 'rules' => 'required|max_length[60]'],
            'last_name'   => ['label' => 'Last name',   'rules' => 'required|max_length[60]'],
            'birthdate'   => ['label' => 'Birthdate',   'rules' => 'required|valid_date[Y-m-d]|not_future'],
            'gender'      => ['label' => 'Gender',      'rules' => 'required|in_list[Female,Male,Prefer not to say]'],
            'email'       => [
                'label'  => 'Email', 'rules' => 'required|valid_email|max_length[120]|is_unique[customers.email]',
                'errors' => ['is_unique' => 'That email is already registered.'],
            ],
            'phone'       => [
                'label'  => 'Phone number', 'rules' => 'required|regex_match[/^(09\d{9}|\+639\d{9})$/]',
                'errors' => ['regex_match' => 'Use a Philippine mobile number like 09123456789.'],
            ],
            'address'     => ['label' => 'Address', 'rules' => 'required|max_length[255]'],
            'username'    => [
                'label'  => 'Username', 'rules' => 'required|alpha_numeric|min_length[4]|max_length[50]|is_unique[customers.username]',
                'errors' => ['is_unique' => 'That username is taken.'],
            ],
            'password'    => ['label' => 'Password', 'rules' => 'required|min_length[8]|max_length[72]'],
            'confirm'     => [
                'label'  => 'Confirm password', 'rules' => 'required|matches[password]',
                'errors' => ['matches' => 'Passwords do not match.'],
            ],
            'terms'       => [
                'label'  => 'Privacy consent', 'rules' => 'required',
                'errors' => ['required' => 'Please agree to the privacy notice.'],
            ],
        ];
    }

    public function index(): string
    {
        return view('register', ['genders' => ['Female', 'Male', 'Prefer not to say']]);
    }

    public function store(): RedirectResponse
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $post = $this->request->getPost();

        (new CustomerModel())->insert([
            'first_name'    => trim($post['first_name']),
            'middle_name'   => trim($post['middle_name']),
            'last_name'     => trim($post['last_name']),
            'birthdate'     => $post['birthdate'],
            'gender'        => $post['gender'],
            'email'         => strtolower(trim($post['email'])),
            'phone'         => trim($post['phone']),
            'address'       => trim($post['address']),
            'username'      => trim($post['username']),
            'password_hash' => password_hash($post['password'], PASSWORD_DEFAULT),
        ]);

        return redirect()->to('register/success')->with('registered_name', trim($post['first_name']));
    }

    public function success(): string|RedirectResponse
    {
        $name = session()->getFlashdata('registered_name');
        if ($name === null) {
            return redirect()->to('register');
        }

        return view('success', ['name' => $name]);
    }
}
