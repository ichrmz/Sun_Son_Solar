# Sun Son Solar

**Team Name:** deVcore

## Description

Sun Son Solar is a family-run solar company in Pasig City that sells solar panels, inverters, batteries, racking and mounting, and wires, and offers consultation, designing, permitting, installation, maintenance, repair, and monitoring. This project is a web system for Sun Son Solar that reaches both customers and employees better. Tagline: **Power Your Future with Solar.**

This repository holds the CodeIgniter 4 (MVC) application: the public homepage, the customer registration page with input validation, and the database (migrations and seeded initial users) based on the client interviews.

## Features so far

- Homepage with the company description, services, and solar benefits
- Customer registration page (all fields required, with validation messages)
- Separate user tables: `customers`, `employees` (with `department`), and `admins`, each with its own primary key `id`
- Passwords stored as hashes, never in plain text
- Initial users seeded: Kat Sinagaraw (customer) and Sol Solis (admin)
- Only customers can sign up. Employee and admin accounts are created by HR or an administrator.

## Tech stack

CodeIgniter 4 (PHP 8.2+), MySQL (XAMPP), MVC pattern

## MVC layout

| Part | Files |
|---|---|
| Models | `app/Models/CustomerModel.php`, `EmployeeModel.php`, `AdminModel.php` |
| Views | `app/Views/home.php`, `register.php`, `success.php`, `layouts/main.php` |
| Controllers | `app/Controllers/Home.php`, `Register.php` |
| Routes | `app/Config/Routes.php` |
| Migrations | `app/Database/Migrations/` |
| Seeder | `app/Database/Seeds/InitialUsersSeeder.php` |

## Setup (Windows + XAMPP)

1. Start **Apache** and **MySQL** in the XAMPP control panel.
2. Install dependencies: `composer install`
3. Copy the environment file: `copy env .env` (edit the database username/password if yours differ)
4. Create the database: `php spark db:create sun_son_solar`
5. Create the tables: `php spark migrate`
6. Add the initial users: `php spark db:seed InitialUsersSeeder`
7. Run the app: `php spark serve` and open http://localhost:8080

## Database

Database name: `sun_son_solar`. Tables: `customers`, `employees`, `admins`, and `migrations`.

## Notes for the client (to confirm)

- Sol's email was cut off in the interview (`sol.solis`); `sol.solis@sunsonsolar.com` is used for now.
- The interviews list Sol as both "Sol Solis" and "Sol Sun Solis"; "Sun" is used as the middle name for now.
- Kat's gender and both initial users' address were not stated; placeholders are in the seeder (marked `ASSUMPTION`).
- Sol's starting password is a default and must be changed after handover.
