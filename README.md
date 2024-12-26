# Membership Administration

## Description:
This PHP application is a membership administration system for an association. It helps manage members, contributions, financial years, and related data. The system includes functionalities for login and user roles (admin, secretary, treasurer, and member), member management, contribution management, and financial year management. 

The application ensures that only authorized users can perform actions according to their roles and provides error handling for invalid actions such as deleting members with active contributions.

## Features:

- **Login and User Roles:**
  - Users can log in and access different features based on their roles.
  - Roles include Admin, Secretary, Treasurer, and Member.
  
- **Member Management:**
  - Add, edit, and delete members.
  - Deleting members only possible if there are no active contributions.
  
- **Contribution Management:**
  - Add contributions with calculated amounts based on membership types and discounts.
  - View paid and outstanding contributions for each family member.
  
- **Financial Year Management:**
  - Add, view, and delete financial years.
  - View income and expenses per financial year.

- **Dashboard:**
  - Displays family member details and total amounts of paid and unpaid contributions for each member.

## Technologies:

- PHP 7.x
- MySQL (Database)
- Visual Studio Code (the final version was initially written using Wing 101 9 IDE)

## Usage:

- After logging in, users can manage members, contributions, and financial years based on their roles.
- The application performs automatic validation for user actions, such as preventing the deletion of members with active contributions.

## Last Modified: 10.12.2024

## File Information

- **MembershipAdministration.sql**: SQL file for creating the database.
- **Db.php**: Handles the database connection. Set the correct database path inside this file to connect.
