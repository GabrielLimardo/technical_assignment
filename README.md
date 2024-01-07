# Technical Assignment

## Project Objective

The main objective of this project is to develop a CRUD system with authentication capabilities. The system will allow for the addition of clients and the management of their expenses/earnings. Additionally, it will provide the functionality to generate a report that showcases the balance and movements over a specified time frame or overall. Authentication is designated for "admins" who will have the ability to create and view clients. However, clients themselves will not require authentication or access to the system.

## Technologies Used

- Language: PHP Vanilla
- Libraries:
  - PHPUnit for unit testing.
  - JWT for authentication purposes.
  - TCPDF for pdf report.
- Database: No ORM was used to handle database operations, aiming to mitigate potential SQL Injection vulnerabilities.

## Application Details

- Web application and Api
- Automated tests of the main classes.
- Blocking after multiple failed attempts.
- Limitation enforcing a minimum password length of 8 characters.
- Error Handling: During a debugging stage, error messages are exposed. However, in a deployment environment, these messages will not be displayed to prevent vulnerabilities.

## Setting Up the Project with XAMPP (Windows)

1. Open CMD and navigate to the `C:\xampp\htdocs\` directory.

2. Clone the project using the following command:
   ```
   git clone https://github.com/GabrielLimardo/technical_assignment.git
   ```

3. Start Apache and MySQL through XAMPP.

4. Return to the project root and execute the following command to migrate all necessary tables:
   ```
   php database/migration.php
   ```
   This will set up the database structure with all the required tables.

5. If for any reason the database `technical_assignment` isn't created automatically, ensure XAMPP is running and then access [http://localhost/phpmyadmin/index.php?route=/](http://localhost/phpmyadmin/index.php?route=/) to manage your databases.

6. Once in phpMyAdmin, create a new database named `technical_assignment` and do again the `php database/migration.php`.


This adjustment ensures that users are prompted to manually create the `technical_assignment` database in phpMyAdmin if it wasn't automatically created during the migration process.
   
7. After the tables have been created, run the following command to populate the database with predefined users, roles, and transactions:
   ```
   php database/seeder.php
   ```
   This will populate the database with the necessary information for the project to function correctly.

8. Once the above steps are completed, the project will be up and running, and you can access it via:
   ```
   http://localhost/technical_assignment/home
   ```

9. Additionally, to interact with the project's API, you can use the following route:
   ```
   http://localhost/technical_assignment/api
   ```

10. To run the automated tests run the following command:
   ```
   ./vendor/bin/phpunit
   ```

![image](https://github.com/GabrielLimardo/technical_assignment/assets/60992367/64806a8a-77a8-40b3-97dd-0fe37800857b)

## Postman Configuration

1. Import the collection file from: `technical_assignment/storage/postman/technical_assignment.postman_collection.json`.
2. Import the environment from: `technical_assignment/storage/postman/technical_assignment.postman_environment.json`.
3. Ensure you select the `technical_assignment` environment. ![image](https://github.com/GabrielLimardo/technical_assignment/assets/60992367/98abed8e-f17b-4990-a2cd-db08bd204d5d)

4. Log in using the provided admin or client user credentials:
   ```php
   $users = [
       ['id' => 1, 'username' => 'John', 'password' => '1234', 'rol' => 'admin'],
       ['id' => 2, 'username' => 'Jane', 'password' => '1234', 'rol' => 'client'],
   ];
   ```
   ![image](https://github.com/GabrielLimardo/technical_assignment/assets/60992367/58f7b374-da79-4124-b303-ac9c17263259)

5. Test all available routes. Note that the "role" folder is accessible only for admin role users.

## Additional Notes

- All processes are configured for both API and web functionalities.
- To create a transaction, the username must have been previously registered by an admin. Once registered, you can then load transactions.

**Note:** Ensure to follow all instructions and configurations correctly to ensure proper functioning of the system.
