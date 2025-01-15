# OpenStax Learning Management System (LMS)

OpenStax is a comprehensive Learning Management System (LMS) designed for educators, students, and administrators. It provides a platform for managing resources, permissions, reviews, and more in a secure and user-friendly manner.

---

## **Features**
- **Admin Panel**: Manage users, resources, permissions, and view dashboard insights.
- **User Panel**: Upload and manage resources, view approved materials, and interact with the LMS.
- **Review System**: Submit and view reviews after logging in as a user.
- **Secure Authentication**: Separate login for Admins and Users with role-based access.
- **Resource Management**: Supports file uploads (audio, video, notes, and YouTube links).
- **Permissions Workflow**: Admins grant or deny permissions for user-uploaded resources.
- **Responsive Design**: Built with Tailwind CSS for a modern and user-friendly interface.

---

## **File Hierarchy**

```plaintext
OpenStax/
├── assets/                     # Contains static resources like images, stylesheets, and scripts
│   ├── css/
│   │   ├── style.css           # Main stylesheet for the website's design and layout
│   ├── images/
│   │   ├── logo.png            # Logo displayed on the website
│   │   ├── banner.jpg          # Banner image used on various pages
│   ├── js/
│       ├── script.js           # JavaScript file for client-side interactions
├── pages/                      # Contains additional web pages
│   ├── contact.php             # Contact form for users to reach out
│   ├── faq.php                 # Frequently Asked Questions page
│   ├── privacy.php             # Privacy policy detailing data handling practices
│   ├── terms.php               # Terms and conditions for website usage
├── uploads/                    # Directory for storing user-uploaded files
│   ├── user1/
│   │   ├── document.pdf        # Example document uploaded by User 1
│   ├── user2/
│       ├── image.png           # Example image uploaded by User 2
├── README.md                   # Overview and documentation of the project
├── aboutus.php                 # Page providing information about the organization or project
├── db.php                      # Script managing database connections
├── index.html                  # Static homepage of the website
├── index.php                   # Dynamic homepage with server-side processing
├── login.html                  # User login page for authentication
├── reviews.php                 # Page displaying user reviews
├── reviews_login.php           # Login page specific to the reviews section
├── reviews_signup.php          # Signup page for users to register and post reviews
├── services.php                # Page outlining the services offered
├── signup.html                 # User registration page
└── test-db.php                 # Script for testing database connectivity and queries
```

## **UI**
![image](https://github.com/user-attachments/assets/06305e63-18da-4cb7-b50b-e354bcd971cc)

## **Table Relation**
```plaintext
register
|-- id (PK)
|-- first_name
|-- last_name
|-- email (Unique)
|-- password
|-- role ('user' or 'admin')
|-- created_at
    |
    | One-to-Many
    |
    v
notes
|-- id (PK)
|-- user_id (FK -> register.id)
|-- filename
|-- filepath
|-- uploaded_at

register
    |
    | One-to-Many
    |
    v
videos
|-- id (PK)
|-- user_id (FK -> register.id)
|-- title
|-- file_path
|-- uploaded_at

register
    |
    | One-to-Many
    |
    v
review
|-- id (PK)
|-- user_id (FK -> register.id)
|-- full_name
|-- comment
|-- created_at

register
    |
    | One-to-Many
    |
    v
permissions
|-- id (PK)
|-- user_id (FK -> register.id)
|-- resource_id
|-- resource_type ('note', 'video')
|-- status ('pending', 'approved', 'denied')
|-- requested_at
|-- updated_at
```

## **Setup Instructions**
1. Install Required Software
Download and install XAMPP or any web server with PHP and MySQL support.
2. Import the Database
Open phpMyAdmin.
Create a new database named openstax.
Import the openstax.sql file from the /db directory.
3. Configure Database Connection
Open db.php in the /db directory.
Update the database credentials if necessary:
php
Copy code
$host = 'localhost';
$username = 'root'; // Update if different
$password = '';     // Update if different
$database = 'openstax';
4. Start the Project
Place the OpenStax folder in the htdocs directory (e.g., C:\xampp\htdocs\OpenStax).
Start the Apache and MySQL services in XAMPP.
Open your browser and navigate to:
bash
Copy code
http://localhost/OpenStax/index.php
Usage Instructions
1. Admin Functionality
Admin Login: /pages/admin_login.php
Features:
Manage users (add, edit, delete).
Manage uploaded resources and permissions.
View user statistics and dashboard insights.
2. User Functionality
User Login: /pages/user_login.php
Features:
Upload and manage resources (notes, videos, audios, YouTube links).
View approved resources.
Submit and view reviews.
3. Reviews
Login: /pages/reviews_login.php
Signup: /pages/reviews_signup.php
Features:
Submit reviews for the platform.
View reviews submitted by other users.
Support and Contributions
If you encounter any issues or would like to contribute to this project, feel free to create an issue or a pull request on GitHub.
