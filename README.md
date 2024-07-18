# DeAd-web-project

## Description

Welcome to our project! This is a web application made in PHP, CSS and JS, for managing prison visits. It allows users to view, create, and delete visits using its user-friendly features.

## Video

A short video that demonstrates the functionalities offered by DeAd.

https://www.youtube.com/watch?v=iGbTPXT7l_U

## Functionality
Users are divided into 2 categories: administrators and normal users.

Normal users can create new visits of existing inmates, view them, and add details post-factum. 

As an example, this is the form for creating a visit:
![image](https://github.com/user-attachments/assets/7b164439-a729-4cfb-b478-c0e260e58632)

Admins can access certain restricted functionalities, such as managing all inmates, users, and visits, as well as exporting data in various formats.

The form for adding an inmate:
![image](https://github.com/user-attachments/assets/cc8bb795-6d02-4e4e-8820-dfbb9ffabab7)

An instance of the list of users, which the admin can control by deleting users or editing their personal data: 
![image](https://github.com/user-attachments/assets/2466565f-6f62-446a-83c4-4290941a1211)

As for the data exporting page, which allows the admin to select the fields by which to sort the data, as well as the export format:
![image](https://github.com/user-attachments/assets/b30541ee-7c3c-4d50-a11c-0c1bf66d6fe1)

## Instructions
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