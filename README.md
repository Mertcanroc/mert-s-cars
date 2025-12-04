# Mert's BMWs Car garage

**Version:** 1.0.0  
**Stack:** Symfony 6, PHP 8.2, MySQL, Bootstrap 5

Description:
This project was created for my final school assignment in year 2,
where we were instructed to work in groups of three to build a complete website of our choice.
The topic was very flexible, anything from a shoe retailer to a car garage.

I chose to work on this project alone to challenge myself and grow further as a developer.
Since I’m passionate about sporty vehicles, I decided to build a BMW themed car dealership website.
This allowed me to combine my personal interests with my technical development.

I received a 9/10 for this project. At the time, several features were missing or unstable,
such as the newsletter system and the admin panel.
I have since improved and expanded the project so I can publish it in my portfolio
and showcase the functionalities I implemented.

Website Features:
Browse BMW models
Book appointments for test drives, maintenance, repairs, and upgrades
Newsletter subscription
Contact form
Interactive BMW helpbot

Admin Panel Features:
User and role management
Send newsletters
Manage appointments

Admin Login (for review):
Email: adminpanel@gmail.com
Password: admin123

> ⚠️ Note: This is version 1.0.0. Some features may still have bugs or glitches and will be polished in future versions.

---

## Installation
Installation
1. Clone the repository
   git clone https://github.com/YOUR_USERNAME/mertsbmw.git
   cd mertsbmw

2. Install dependencies
   composer install
   npm install
   npm run build

3. Configure the environment variables
Create a .env.local file:
cp .env .env.local
Then update the following values inside .env.local:
DATABASE_URL="mysql://root:@127.0.0.1:3306/mertscars?serverVersion=10.4.32-MariaDB&charset=utf8mb4"
MAILER_DSN=null://null

4. Create an empty database
Create the database (do not run migrations):
php bin/console doctrine:database:create

5. Import the SQL backup
Inside the backups folder you will find a .sql file containing the database tables and demo data.
Import it using phpMyAdmin (XAMPP):
Open phpMyAdmin
Select the empty mertscars database
Go to Import
Choose the .sql file
Click Go

6. Start the Symfony server
   symfony server:start

7. Visit the project
   http://127.0.0.1:8000