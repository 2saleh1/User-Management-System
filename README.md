# User Management System

![Screenshot](screenshot.png)


## What You Need

- XAMPP
- Web browser

## Setup Instructions

### 1. Database Setup

1. Start XAMPP (Apache + MySQL)
2. Open phpMyAdmin: `http://localhost/phpmyadmin`
3. Create database named `info`
4. Run this SQL command:

```sql
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    age INT NOT NULL,
    status INT DEFAULT 0
);
```

### 2. File Setup

1. Put `index.php` in `c:\xampp\htdocs\info\`
2. Open browser: `http://localhost/info/index.php`

## How to Use

- **Add User**: Fill name and age, click "Add User"
- **Toggle Status**: Click "Toggle" to switch between 1 (active) and 0 (inactive)
- **View Users**: All users show in table below form

## Features

- Add users with name and age
- Toggle user status (0 or 1)
- View all users in table
- Users display starting from ID 1

## Troubleshooting

- **Connection error**: Make sure MySQL is running in XAMPP
- **Table error**: Create the `user` table with correct structure
- **Form not working**: Check both fields are filled
