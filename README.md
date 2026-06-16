# Project Quantum - Role-Based Inventory & Sales Management System

Project Quantum is a modern, premium role-based inventory and sales management system built using **Laravel 12**, **Tailwind CSS v4 (via Vite)**, and **SQLite**. It allows businesses to manage products, categories, suppliers, track stock levels (with threshold warnings), and record sales with role-specific permissions.

---

## Features

- **Role-Based Access Control (RBAC)**: Custom roles for `Admin`, `Manager`, and `Staff` with granular access control.
  - **Admin**: Full control over products, categories, suppliers, and system configuration.
  - **Manager**: View inventory, manage categories/suppliers, and adjust stock levels.
  - **Staff**: View inventory, log stock adjustments, and record new sales.
- **Stock Threshold Alerts**: Visual indicators and warning flags when stock levels fall below the minimum threshold.
- **Stock Adjustment Logging**: Auditable history of stock adjustments (in/out) with detailed log tracking.
- **Sales Flow**: Dedicated checkout workflow for Staff to record item sales, decrementing stock levels automatically.
- **Modern UI**: Styled with Tailwind CSS v4 featuring sleek gradients, smooth transitions, and responsive layouts.

---

## Prerequisites

Ensure you have the following software installed with the specified versions (or higher):

| Software | Required Version | Purpose |
| :--- | :--- | :--- |
| **PHP** | `^8.2` or `^8.3` | Back-end runtime environment |
| **Composer** | `^2.2` | PHP package dependency manager |
| **Node.js** | `^18.x`, `^20.x`, or `^22.x` | JavaScript runtime environment for asset building |
| **NPM** | `^9.x` or `^10.x` | Node package manager |
| **SQLite** / **MySQL** | SQLite (Default) | Database storage engines |

---

## Installation & Setup

Follow these step-by-step instructions to get your local environment up and running:

### 1. Clone the Repository
```bash
git clone https://github.com/YOUR_USERNAME/project-quantum.git
cd project-quantum
```

### 2. Automated Project Setup
The application comes pre-configured with a custom composer script that handles dependencies installation, database creation, key generation, and asset building:

```bash
composer run setup
```

*This command automatically executes the following steps:*
- Installs PHP packages (`composer install`)
- Creates the `.env` file from `.env.example`
- Generates the app encryption key (`php artisan key:generate`)
- Creates the SQLite database file (`database/database.sqlite`)
- Runs migrations and seeds the database (`php artisan migrate --force`)
- Installs Node modules (`npm install`)
- Builds production assets (`npm run build`)

### 3. Database Seeding (Optional Manual Step)
If you need to re-seed the default users and dummy products/categories/suppliers:
```bash
php artisan db:seed
```

#### Default Seeded Users
You can log in using the following credentials:
- **Admin**: `admin@quantum.com` | Password: `password`
- **Manager**: `manager@quantum.com` | Password: `password`
- **Staff**: `staff@quantum.com` | Password: `password`

---

## Running the Application Locally

You can run the application concurrently (built-in server, queue listener, and Vite hot reload) using:

```bash
composer run dev
```

Alternatively, you can run them in separate terminal windows:

```bash
# Start the PHP development server
php artisan serve

# Start the Vite development server (hot reload CSS/JS)
npm run dev

# Start the queue listener (handles background notifications/jobs)
php artisan queue:listen --tries=1
```

Access the application at [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## Running Tests

Verify the implementation by running the automated Pest test suite:

```bash
composer run test
```
