Hospital Staff Recruitment System

The Hospital Staff Recruitment System is a web-based recruitment platform designed to help hospitals manage the process of advertising job opportunities, receiving applications, and managing prospective hospital staff.

The system provides a centralized platform where applicants can view available positions and submit their applications, while hospital administrators can manage job vacancies and recruitment information.

Features
Applicant Features

User registration and login

View available hospital job vacancies

View detailed job descriptions and requirements

Submit job applications online

Manage applicant profile information

Track submitted applications

Receive recruitment-related notifications

Administrator Features

Secure administrator login

Manage hospital job vacancies

Add, edit, and remove job opportunities

View submitted applications

Manage applicant information

Review candidate applications

Manage recruitment processes

Send email notifications to applicants

Technologies Used

HTML5 – Website structure

CSS3 – Styling and responsive design

JavaScript – Client-side functionality

PHP – Server-side/backend development

MySQL – Database management

PHPMailer – Email communication

XAMPP – Local development environment

System Architecture
Applicant
    │
    ▼
Web Interface
    │
    ▼
PHP Application
    │
    ├──────────────► MySQL Database
    │
    └──────────────► PHPMailer / Email
    │
    ▼
Administrator

Requirements

To run this project locally, you need:

PHP 8.x or compatible version

MySQL

Apache

XAMPP

Composer

Modern web browser

Installation
1. Clone the repository
git clone https://github.com/Hillary-kithinji/hospital-recruitment-System-.git

2. Move the project to XAMPP

Place the project inside:

C:\xampp\htdocs\


The project directory should be:

C:\xampp\htdocs\hospital-recruitment

3. Start XAMPP

Open the XAMPP Control Panel and start:

Apache

MySQL

4. Create the database

Open phpMyAdmin:

http://localhost/phpmyadmin


Create a database for the recruitment system and import the provided SQL database file, if available.

5. Configure the database

Update the database connection settings in the appropriate configuration file.

Example:

$host = "localhost";
$username = "root";
$password = "";
$database = "hospital_recruitment";

6. Install dependencies

If the project uses Composer, open the terminal in the project directory and run:

composer install

7. Configure email

If PHPMailer is used, configure the SMTP settings for your email provider.

Do not upload SMTP passwords, API keys, database credentials, or other sensitive information to GitHub.

Use environment variables or a configuration file excluded through .gitignore.

8. Run the system

Open your browser and visit:

http://localhost/hospital-recruitment/

Project Structure
hospital-recruitment/
│
├── css/                    # Stylesheets
├── js/                     # JavaScript files
├── images/                 # Images and assets
├── php/                    # PHP backend functionality
├── uploads/                # Uploaded files/documents
├── vendor/                 # Composer dependencies
├── index.php               # Main entry point
├── composer.json           # PHP dependencies
├── composer.lock           # Dependency versions
└── README.md               # Project documentation


The exact folder structure may vary depending on the version of the project.

Security

The system should be configured carefully before production deployment.

Sensitive information such as:

Database passwords

SMTP credentials

API keys

Authentication secrets

Private configuration files

Real applicant information

should not be committed to the GitHub repository.

Future Improvements

Possible future enhancements include:

Online interview scheduling

Automated candidate screening

Advanced applicant search and filtering

CV/resume management

Application status workflow

SMS notifications

Role-based administrator permissions

Recruitment analytics dashboard

Online interview integration

Enhanced security and authentication

Production deployment

Purpose

This project was developed as a web-based solution for improving and organizing the hospital staff recruitment process through digital job posting, application management, and communication.

Author

Hillary Kithinji

GitHub:
https://github.com/Hillary-kithinji

License

This project is intended for educational, demonstration, and portfolio purposes.
