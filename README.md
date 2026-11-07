"# blood_donation_management_system" 
# Online Blood Donation Management System

A web-based mini project developed as part of my
First Year, Second Semester academic curriculum
using PHP, MySQL, HTML, CSS, and JavaScript.

## Features

**Users / Donors**
- Register and log in as a donor
- Update profile
- Find donors by blood type and location
- Request blood and view requests
- View upcoming blood donation camps

**Admin Panel**
- Separate admin login
- Dashboard with totals and donors by blood type
- Manage donors (search, edit, delete)
- Manage blood requests (approve, reject, delete)
- Manage donation camps (add, edit, delete)

## Technologies
- PHP
- MySQL / MariaDB
- Bootstrap 5
- XAMPP (Apache + MySQL)

## How to Run
1. Install XAMPP and start Apache and MySQL.
2. Copy this project folder into `C:\xampp\htdocs\`.
3. Open phpMyAdmin, go to the Import tab and import `blood_donation_full.sql`.
4. Check the database settings in `db_connect.php`.
5. Open `http://localhost/blood_donation/` in your browser.

## Admin Setup
1. Open `http://localhost/blood_donation/admin/setup.php` and create an admin account.
2. Delete `admin/setup.php` afterwards.
3. Log in at `http://localhost/blood_donation/admin/login.php`.

## Database Tables
- `donors`: registered donors
- `requests`: blood requests
- `camps`: donation camps
- `donations`: links donors to requests
- `admins`: admin accounts

## Author
Amanudheen CK, First Semester Mini Project