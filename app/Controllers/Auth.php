<?php

namespace App\Controllers;

/**
 * JSON API used by the Sun Son Solar front end (public/index.html).
 * Every reply is { ok: true, ... } or { ok: false, errors: { field: "message" } }.
 * Customers live in `customers`; admins/technicians live in `employees`.
 */
class Auth extends BaseController
{
    private const DEPARTMENTS = ['Administration', 'IT', 'Dispatch', 'Accounting', 'HR', 'Marketing', 'Sales', 'Customer Service'];
    private const GENDERS     = ['female' => 'Female', 'male' => 'Male', 'not-say' => 'Prefer not to say'];
    private const MSG         = [
        'firstName'  => 'Enter your first name (letters only).',
        'middleName' => 'Use letters only.',
        'lastName'   => 'Enter your last name (letters only).',
        'birthdate'  => 'Enter a valid birthdate.',
        'gender'     => 'Choose one.',
        'email'      => 'Enter a valid email.',
        'phone'      => 'Enter an 11-digit mobile number starting with 09.',
        'address'    => 'Enter your address (at least 5 characters).',
        'username'   => 'Use 4 to 30 letters, numbers, _ or -.',
        'password'   => 'At least 8 characters, with letters and numbers.',
        'confirm'    => 'Passwords don’t match.',
        'department' => 'Choose a department.',
        'role'       => 'Choose admin or employee.',
    ];

    /* ===================== public endpoints ===================== */

    /** POST /api/register  -> always creates a CUSTOMER (employees are created by admins) */
    public function register()
    {
        $d      = $this->body();
        $keys   = ['firstName', 'middleName', 'lastName', 'birthdate', 'gender', 'email', 'phone', 'address', 'username', 'password', 'confirm'];
        $errors = $this->check($d, $keys) + $this->uniqueErrors($d);

        if (($d['terms'] ?? null) !== true) {
            $errors['terms'] = 'Please accept the terms to continue.';
        }
        if ($errors) {
            return $this->fail($errors);
        }

        db_connect()->table('customers')->insert($this->row($d));

        return $this->json(['ok' => true, 'firstName' => trim($d['firstName'])], 201);
    }

    /** POST /api/login  { loginId (username or email), loginPassword } */
    public function login()
    {
        $d  = $this->body();
        $id = trim((string) ($d['loginId'] ?? ''));
        $pw = (string) ($d['loginPassword'] ?? '');

        $errors = [];
        if ($id === '') { $errors['loginId'] = 'Enter your username or email.'; }
        if ($pw === '') { $errors['loginPassword'] = 'Enter your password.'; }
        if ($errors) {
            return $this->fail($errors);
        }

        foreach (['employees', 'customers'] as $table) {
            $row = db_connect()->table($table)
                ->groupStart()->where('username', $id)->orWhere('email', $id)->groupEnd()
                ->get()->getRowArray();

            if ($row && password_verify($pw, $row['password'])) {
                if (! $row['is_active']) {
                    return $this->fail(['_form' => 'This account is deactivated. Please contact an administrator.'], 403);
                }
                session()->regenerate();
                session()->set('auth', ['table' => $table, 'id' => (int) $row['id']]);

                return $this->json($this->userOut($row, $table));
            }
        }

        return $this->fail(['_form' => 'Incorrect username/email or password.'], 401);
    }

    /** POST /api/logout */
    public function logout()
    {
        session()->destroy();

        return $this->json(['ok' => true]);
    }

    /** GET /api/me  -> who is logged in (used on every page load) */
    public function me()
    {
        $cur = $this->current();

        return $this->json($cur ? $this->userOut($cur['row'], $cur['table']) : ['ok' => false]);
    }

    /* ===================== logged-in endpoints ===================== */

    /** POST /api/profile */
    public function profile()
    {
        $cur = $this->current();
        if (! $cur) {
            return $this->fail(['_form' => 'Please log in again.'], 401);
        }

        $d      = $this->body();
        $errors = $this->check($d, ['firstName', 'middleName', 'lastName', 'email', 'phone', 'address']);
        if (! isset($errors['email']) && $this->taken('email', strtolower(trim((string) $d['email'])), $cur['table'], (int) $cur['row']['id'])) {
            $errors['email'] = 'That email is already registered.';
        }
        if ($errors) {
            return $this->fail($errors);
        }

        db_connect()->table($cur['table'])->where('id', $cur['row']['id'])->update([
            'first_name'  => trim($d['firstName']),
            'middle_name' => trim((string) ($d['middleName'] ?? '')),
            'last_name'   => trim($d['lastName']),
            'email'       => strtolower(trim($d['email'])),
            'phone'       => trim($d['phone']),
            'address'     => trim($d['address']),
        ]);

        return $this->json(['ok' => true]);
    }

    /** POST /api/password  { currentPassword, password, confirm } */
    public function password()
    {
        $cur = $this->current();
        if (! $cur) {
            return $this->fail(['_form' => 'Please log in again.'], 401);
        }

        $d      = $this->body();
        $errors = $this->check($d, ['password', 'confirm']);
        if (! password_verify((string) ($d['currentPassword'] ?? ''), $cur['row']['password'])) {
            $errors['currentPassword'] = 'Your current password is incorrect.';
        }
        if ($errors) {
            return $this->fail($errors);
        }

        db_connect()->table($cur['table'])->where('id', $cur['row']['id'])
            ->update(['password' => password_hash((string) $d['password'], PASSWORD_DEFAULT)]);

        return $this->json(['ok' => true]);
    }

    /** POST /api/admin/accounts  (admins only) -> creates an employee/admin in `employees` */
    public function adminAccount()
    {
        $cur = $this->current();
        if (! $cur || $cur['table'] !== 'employees' || $cur['row']['role'] !== 'admin') {
            return $this->fail(['_form' => 'Only administrators can create accounts.'], 403);
        }

        $d    = $this->body();
        $role = $d['role'] ?? '';
        if (! in_array($role, ['admin', 'employee'], true)) {
            return $this->fail(['role' => self::MSG['role']]);
        }
        $keys = ['firstName', 'middleName', 'lastName', 'birthdate', 'gender', 'email', 'phone', 'address', 'username', 'password', 'confirm'];
        if ($role === 'employee') {
            $keys[] = 'department';
        }

        $errors = $this->check($d, $keys) + $this->uniqueErrors($d);
        if ($errors) {
            return $this->fail($errors);
        }

        $row               = $this->row($d);
        $row['role']       = $role;
        $row['department'] = $role === 'employee' ? $d['department'] : 'Administration';
        db_connect()->table('employees')->insert($row);

        return $this->json(['ok' => true], 201);
    }

    /** POST /api/checkins  (technicians) { id, at, lat, lng, accuracy, location } */
    public function checkins()
    {
        $cur = $this->current();
        if (! $cur || $cur['table'] !== 'employees') {
            return $this->fail(['_form' => 'Please log in as an employee.'], 401);
        }

        $d   = $this->body();
        $ref = substr(trim((string) ($d['id'] ?? '')), 0, 30);
        $db  = db_connect();

        // The phone may retry after going offline: the same check-in is never saved twice.
        if ($ref !== '' && $db->table('checkins')->where('employee_id', $cur['row']['id'])->where('client_ref', $ref)->countAllResults() > 0) {
            return $this->json(['ok' => true]);
        }

        $num = static fn ($k) => isset($d[$k]) && is_numeric($d[$k]) ? (float) $d[$k] : null;
        $lat = $num('lat');
        $lng = $num('lng');
        if (($lat !== null && abs($lat) > 90) || ($lng !== null && abs($lng) > 180)) {
            return $this->fail(['_form' => 'Invalid coordinates.']);
        }

        $db->table('checkins')->insert([
            'employee_id'   => (int) $cur['row']['id'],
            'name'          => trim($cur['row']['first_name'] . ' ' . $cur['row']['last_name']),
            'username'      => $cur['row']['username'],
            'latitude'      => $lat,
            'longitude'     => $lng,
            'accuracy'      => $num('accuracy'),
            'location_note' => substr((string) ($d['location'] ?? ''), 0, 50),
            'client_ref'    => $ref !== '' ? $ref : null,
            'checked_in_at' => date('Y-m-d H:i:s', strtotime((string) ($d['at'] ?? '')) ?: time()),
        ]);

        return $this->json(['ok' => true], 201);
    }

    /* ===================== helpers ===================== */

    private function json(array $data, int $status = 200)
    {
        return $this->response->setStatusCode($status)->setJSON($data);
    }

    private function fail(array $errors, int $status = 400)
    {
        return $this->json(['ok' => false, 'errors' => $errors], $status);
    }

    /** Accept JSON or normal form posts. */
    private function body(): array
    {
        $isJson = str_contains($this->request->getHeaderLine('Content-Type'), 'json');

        return ($isJson ? $this->request->getJSON(true) : $this->request->getPost()) ?? [];
    }

    private function rules(): array
    {
        $name = "required|min_length[2]|max_length[50]|regex_match[/^[\p{L} .'-]+$/u]";

        return [
            'firstName'  => $name,
            'lastName'   => $name,
            'middleName' => "permit_empty|max_length[50]|regex_match[/^[\p{L} .'-]+$/u]",
            'birthdate'  => 'required|valid_date[Y-m-d]',
            'gender'     => 'required|in_list[female,male,not-say]',
            'email'      => 'required|valid_email|max_length[100]',
            'phone'      => 'required|regex_match[/^09\d{9}$/]',
            'address'    => 'required|min_length[5]|max_length[255]',
            'username'   => 'required|alpha_dash|min_length[4]|max_length[30]',
            'password'   => 'required|min_length[8]|regex_match[/^(?=.*[A-Za-z])(?=.*\d).+$/]',
            'confirm'    => 'required|matches[password]',
            'department' => 'required|in_list[' . implode(',', self::DEPARTMENTS) . ']',
        ];
    }

    /** Validate only the given fields; returns [field => friendly message]. */
    private function check(array $data, array $keys): array
    {
        $validation = service('validation');
        $validation->setRules(array_intersect_key($this->rules(), array_flip($keys)));
        $validation->run($data);

        $errors = [];
        foreach ($validation->getErrors() as $field => $msg) {
            $errors[$field] = self::MSG[$field] ?? $msg;
        }

        if (in_array('birthdate', $keys, true) && ! isset($errors['birthdate']) && strtotime($data['birthdate']) > time()) {
            $errors['birthdate'] = 'Birthdate can’t be in the future.';
        }

        return $errors;
    }

    /** Username and email must be unique across BOTH tables (login looks in both). */
    private function uniqueErrors(array $d): array
    {
        $errors = [];
        if (preg_match('/^[A-Za-z0-9_-]{4,30}$/', (string) ($d['username'] ?? '')) && $this->taken('username', $d['username'])) {
            $errors['username'] = 'That username is already taken.';
        }
        if (filter_var($d['email'] ?? '', FILTER_VALIDATE_EMAIL) && $this->taken('email', strtolower(trim($d['email'])))) {
            $errors['email'] = 'That email is already registered.';
        }

        return $errors;
    }

    private function taken(string $col, string $val, ?string $exceptTable = null, ?int $exceptId = null): bool
    {
        foreach (['customers', 'employees'] as $table) {
            $q = db_connect()->table($table)->where("LOWER({$col}) =", strtolower($val));   // case-insensitive
            if ($table === $exceptTable && $exceptId) {
                $q->where('id !=', $exceptId);
            }
            if ($q->countAllResults() > 0) {
                return true;
            }
        }

        return false;
    }

    /** Request data -> database row (password hashed, gender stored as readable text). */
    private function row(array $d): array
    {
        return [
            'first_name'  => trim($d['firstName']),
            'middle_name' => trim((string) ($d['middleName'] ?? '')),
            'last_name'   => trim($d['lastName']),
            'birthdate'   => $d['birthdate'],
            'gender'      => self::GENDERS[$d['gender']],
            'email'       => strtolower(trim($d['email'])),
            'phone'       => trim($d['phone']),
            'address'     => trim($d['address']),
            'username'    => trim($d['username']),
            'password'    => password_hash((string) $d['password'], PASSWORD_DEFAULT),
            'created_at'  => date('Y-m-d H:i:s'),
        ];
    }

    /** The logged-in user (fresh from the DB), or null. */
    private function current(): ?array
    {
        $auth = session()->get('auth');
        if (! $auth) {
            return null;
        }
        $row = db_connect()->table($auth['table'])->where('id', $auth['id'])->get()->getRowArray();

        return ($row && $row['is_active']) ? ['table' => $auth['table'], 'row' => $row] : null;
    }

    /** User object in the exact shape the front end reads. */
    private function userOut(array $r, string $table): array
    {
        return [
            'ok'         => true,
            'id'         => (int) $r['id'],
            'username'   => $r['username'],
            'firstName'  => $r['first_name'],
            'middleName' => $r['middle_name'],
            'lastName'   => $r['last_name'],
            'email'      => $r['email'],
            'phone'      => $r['phone'],
            'address'    => $r['address'],
            'birthdate'  => $r['birthdate'],
            'gender'     => array_search($r['gender'], self::GENDERS, true) ?: 'not-say',
            'role'       => $table === 'customers' ? 'customer' : $r['role'],
            'department' => $r['department'] ?? '',
        ];
    }
}
