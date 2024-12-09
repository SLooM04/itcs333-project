# IT College Room Booking System

## Project Overview
The IT College Room Booking System is a responsive web-based application designed to manage room bookings efficiently for students and administrators. The system allows users to browse available rooms, view room details, make bookings, and manage their profiles. Administrators have additional capabilities to manage room schedules, user accounts, and generate reports.

## Group Members 
The following team members contributed to this project:

- [HUSAIN SAYED SALAH ALAWI AHMED] - Role (1. User Registration and Login) @Mrzizt
- [HASAN ABDULMUNEER ASHOOR MOHAMED] - Role (2. User Profile Management)
- [SALMAN MOHAMED JASIM SALMAN] - Role (3. Room Browsing and Details)
- [MURTADHA AHMED YUSUF YAHYA] - Role (4. Booking System)
- [REDHA MOHAMED SALEH HASAN AHMED] - Role (5. Admin Panel)
- [HUSAIN JAMEEL HUSAIN ABDULRASOOL] - Role (6. Reporting and Analytics)
- [SAYED ALI SHAFEEQ ALAWI AHMED] - Role (7. Comment System)

## Features

### User Features
- **User Registration & Login**
- **Room Browsing** 
- **Room Booking**
- **Booking Cancellation**
- **User Dashboard**: View upcoming and past bookings.

### Admin Features:
- **Admin Panel**: Manage the overall system with a dashboard for room management, user management, and room schedule control.
- **Room Management**: Add, edit, or delete rooms from the system.
- **User Management**: Admins can manage users, including viewing profiles and activities.
- **Reporting & Analytics**: Generate reports on room usage, booking statistics, and popular rooms.
- **Comment System**: View and respond to feedback left by users about rooms.

### Common Features:
- **Responsive Design**: Fully responsive across Desktop, Tablet, and Mobile views.


## Technologies Used
- **Frontend**: HTML, CSS, JavaScript, Bootstrap, PicoCSS (or other CSS frameworks of choice)
- **Backend**: PHP
- **Database**: MySQL with PDO (PHP Data Objects) for secure database access

## Installation Guide

1. Clone the repository to your local machine:
   ```bash
   git clone https://github.com/SLooM04/itcs333-project.git

   Set up XAMPP:
2. Set up XAMPP:
Download and install XAMPP if you don’t have it already.
Launch the XAMPP control panel.
3. Start Apache and MySQL:

- In the XAMPP control panel, click on Start next to Apache to start the Apache server.
- Click on Start next to MySQL to start the MySQL database server.

4. Import the MySQL database schema:
- Open phpMyAdmin by going to http://localhost/phpmyadmin in your web browser.
- Create a new database (e.g., room_booking).
- Import the provided database.sql file into this database.

5. Configure the database connection:
- Open the config.php file in the project directory.
- Update the database connection settings (host, username, password, and database name) to match your local XAMPP configuration.

6. Access the project:
- Move the project folder (e.g., room-booking-system) to the htdocs folder in your XAMPP installation directory (e.g., C:\xampp\htdocs).
- Open your web browser and navigate to
http://localhost/itcs333-project/Home.php


