# Travel IMS - Inventory Management System

![Travel IMS Dashboard](https://img.shields.io/badge/Travel-IMS-blue)
![Laravel](https://img.shields.io/badge/Laravel-11.x-red)
![PHP](https://img.shields.io/badge/PHP-8.1+-indigo)

Travel IMS is a comprehensive, scalable, and professional Inventory Management System built specifically for the travel and aviation industry. It handles inquiries, customer management, quotations, sales tracking, and follow-ups through a modern, responsive interface.

## 🚀 Features

- **Customer Management**: Maintain a central database of all customers, with detailed contact and preference tracking.
- **Inquiry Tracking**: Record and track customer inquiries, assign them to sales representatives, and monitor progress.
- **Quotation Generator**: Build and manage detailed quotations for travel services, hotels, and airlines.
- **Follow-up System**: Never miss a lead with a built-in follow-up tracking and remark system.
- **Role-based Access Control**: Fine-grained permissions allowing Super Admins, Sales Agents, and Managers to only access what they need.
- **Dashboard & Analytics**: Get a birds-eye view of your travel business's performance.

## 🛠 Tech Stack

- **Backend Framework**: Laravel 11
- **Database**: MySQL
- **Frontend**: Bootstrap 5, jQuery, DataTables
- **Authentication**: Laravel Session & Spatie Permissions

## 📦 Installation Guide

Follow these steps to deploy Travel IMS locally or on a production server:

1. **Clone the repository**:
   ```bash
   git clone <repository-url> travel_ims
   cd travel_ims
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install && npm run dev
   ```

3. **Environment Setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Update the `.env` file with your database credentials.

4. **Database Setup**:
   Since the core structure relies on specific lookup tables, import the base database schema:
   ```bash
   mysql -u root -p travel_ims < database/schema.sql
   ```
   *(Note: The `database/schema.sql` file contains the exact table structure required to run the application).*

5. **Storage Link**:
   ```bash
   php artisan storage:link
   ```

6. **Serve the Application**:
   ```bash
   php artisan serve
   ```

## 🔒 Default Credentials

Once the database is imported, you can log in using the default Super Admin account.

## 📄 License

This software is proprietary. All rights reserved.
