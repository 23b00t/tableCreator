# Table Creator

## Purpose
The **Table Creator** project provides an easy-to-use web interface that allows users to create, view, and manage database tables dynamically. This tool is designed to simplify table creation and data management within a MariaDB database without needing technical expertise.

## Features

### Table Management
- **Create Tables:** Users can create new tables by defining custom attributes through a simple form.
- **View Tables:** All created tables are displayed in an organized list, making it easy to browse existing data structures.
- **Edit Tables:** Users can update tables and rename attributes as needed.
- **Delete Tables:** Tables and attributes can be removed, with safeguards to ensure data integrity.

### Data Management
- **Add Records:** Users can add new records to the tables, filling in specific data fields.
- **View Records:** Each table displays its records in a clear, structured format.
- **Edit Records:** Users can update individual records in existing tables.
- **Delete Records:** Users can remove records from tables as needed.

### Search and Filter
- **Search Functionality:** Users can search for specific data across tables using partial matches, making it easy to find information.

### Error Handling and Validation
- **Error Messages:** Clear messages are shown to guide users in case of issues like duplicate table names or missing fields.
- **Data Validation:** The system checks inputs to ensure all required fields are complete before saving.

## How to Use Table Creator
1. **Create a New Table:** Go to the creation form, name your table, and add custom attributes.
2. **Manage Tables and Data:** Access your tables through the main view. From there, you can add, edit, or delete tables and records.
3. **Search for Data:** Use the search bar to filter data based on specific attributes.
4. **Handle Errors and Warnings:** If you encounter an error, follow the guidance provided to make adjustments.

## Requirements
- PHP 8 or higher
- MySQL or MariaDB for database management
- Composer for managing dependencies

## Installation and Setup
1. Clone the repository:
   ```bash
   git clone https://github.com/23b00t/tableCreator.git 
   ```
2. Navigate to the project directory:
   ```bash
   cd tableCreator
   ```
3. Install the dependencies using Composer:
   ```bash
   composer install
   ```
4. Copy the configuration template and fill in your database credentials:
   ```bash
   cp configTemplate.php config.php
   # Follow the instructions in the template file
   ```
5. Set up the database with the initial tables and example data:
   ```bash
   mysql -u <username> -p <database_name> < sql/create.sql
   ```
