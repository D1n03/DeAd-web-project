# DeAd-web-project

## :ledger: Index
- [Description](#beginner-description)
- [Video](#movie_camera-video)
- [Functionality](#gear-functionality)
- [Instructions](#zap-instructions)
- [Structure](#books-structure)
- [Team](#team)

## :beginner: Description

Welcome to our project! This is a web application made in PHP, CSS and JS, for managing prison visits. It allows users to view, create, and delete visits using its user-friendly features.


## :movie_camera: Video

A short video that demonstrates the functionalities offered by DeAd.

https://www.youtube.com/watch?v=iGbTPXT7l_U

## :gear: Functionality
Users are divided into 2 categories: administrators and normal users.

Normal users can create new visits of existing inmates, view them, and add details post-factum. 

As an example, this is the form for creating a visit:

![image](https://github.com/user-attachments/assets/7b164439-a729-4cfb-b478-c0e260e58632)

Administrators can access certain restricted functionalities, such as managing all inmates, users, and visits, as well as exporting data in various formats.

The form for adding an inmate:

![image](https://github.com/user-attachments/assets/cc8bb795-6d02-4e4e-8820-dfbb9ffabab7)

An instance of the list of users, which the admin can control by deleting users or editing their personal data: 

![image](https://github.com/user-attachments/assets/2466565f-6f62-446a-83c4-4290941a1211)

As for the data exporting page, which allows the admin to select the fields by which to sort the data, as well as the export format:

![image](https://github.com/user-attachments/assets/b30541ee-7c3c-4d50-a11c-0c1bf66d6fe1)

## :books: Structure

The application is structured into several key components, each fulfilling specific roles and interacting with each other to deliver the overall functionality. Here is an overview of the main components:

**1. User Roles**
- Anonymous User: A user who is not logged in. They can visit public pages of the application.
- User: An authenticated user who can create visits using the application.
- Administrator: An authenticated user who manages the data within the application.

**2. DeAd Web App**

The core of the application, the DeAd Web App, consists of the following components:
- DeAd Web User Pages:
    - Container: CSS, JavaScript, PHP
    - Description: These pages provide the user interface for authenticated users to create visits. They make API calls to the Web Application to perform various actions.
- DeAd Web Admin Pages:
    - Container: CSS, JavaScript, PHP
    - Description: These pages provide the user interface for administrators to manage data. They make API calls to the Web Application to manage application data.
- Web Application:
    - Description: This component provides REST API endpoints that serve the user and admin pages. It handles API calls using JSON/HTTP and manages the rendering of public pages.
    - Interactions:
        - Receives API calls from the DeAd Web User and Admin Pages.
        - Sends emails using Google SMTP.
        - Reads from and writes to the SQL Database.
- SQL Database:
    - Container: MySQL
    - Description: A relational database that stores information about users, inmates, visits, and feedback from users. The Web Application reads from and writes to this database using SQL/TCP.
- Google SMTP:
    - Description: This service is used to send password reset links via email, ensuring secure and efficient password recovery for users. The Web Application sends emails using this service.
    
**3. Interactions and Data Flow**

- Anonymous users can visit public pages of the application.
- Authenticated users interact with the DeAd Web User Pages to create visits. These pages make API calls to the Web Application.
- Administrators use the DeAd Web Admin Pages to manage application data. These pages also make API calls to the Web Application.
- The Web Application serves as the backend, providing REST APIs for both user and admin pages, handling API calls, and managing interactions with the SQL Database.
- The Web Application also uses Google SMTP to send emails for password recovery.
- The SQL Database stores and manages all relevant data, which the Web Application accesses as needed.

This structure ensures a clear separation of concerns, allowing for maintainable and scalable application development.



## :zap: Instructions
To test this web app, you will need to rule it on a localhost webserver. For the setup, you will need to proceed as follows:

-	Install PHP from https://windows.php.net/download
-	Install Composer from https://getcomposer.org/download
-	Install the necessary dependencies using Composer by running the following commands in a terminal inside the project folder: 

```
composer require firebase/php-jwt
```

```
composer require phpmailer/phpmailer
```

```
composer require vlucas/phpdotenv
```


-	Install XAMPP from https://www.apachefriends.org/index.html
-	Clone this repository into the ‘htdocs’ folder of XAMPP
-	Run XAMPP, and start the Apache and MySQL modules
-	On your browser, navigate to ```localhost/DeAd-web-project/src/Index/index.php``` in order to access the home page of the website.
-	In order to add inmates, you will first have to manually add an administrator by accessing ```localhost/phpmyadmin``` and adding a new row to the users table in the database.
-	After adding inmates (through the Inmates panel, as an admin), you can now sign up as a normal user and schedule visits for them.


## Team

Rusu Andrei-Dudu,
Zloteanu Mircea
