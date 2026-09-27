# Task Management System

Aplikasi Task Management berbasis web yang dibuat menggunakan Laravel, Blade, Tailwind CSS, JavaScript, dan MySQL.

Project ini dibuat sebagai technical test untuk menunjukkan implementasi dashboard responsive, REST API, CRUD task, search, pagination, validation, error handling, loading state, empty state, dan responsive layout.

## Tech Stack

* Laravel 12
* PHP 8.2+
* MySQL
* Blade
* Tailwind CSS
* JavaScript
* Vite
* Eloquent ORM
* REST API

## Features

### Dashboard

Dashboard menyediakan beberapa fitur utama:

* User profile
* Task statistics
* Task list
* Search task
* Pagination
* Recent activity
* Responsive desktop dan mobile layout
* Loading state
* Empty state
* Error state

### Task Management

Task memiliki data:

* ID
* User ID
* Title
* Description
* Status
* Priority
* Created At
* Updated At

Status task:

* `pending`
* `in_progress`
* `completed`

Priority task:

* `low`
* `medium`
* `high`

### REST API

API menyediakan endpoint untuk:

* Menampilkan seluruh task
* Menampilkan detail task
* Membuat task baru
* Mengubah task
* Mengubah status task
* Menghapus task

## Requirements

Sebelum menjalankan project, pastikan sudah tersedia:

* PHP >= 8.2
* Composer
* Node.js
* NPM
* MySQL
* Git

## Installation

### 1. Clone Repository

```bash
git clone https://github.com/USERNAME/task-management.git
```

Masuk ke folder project:

```bash
cd task-management
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install JavaScript Dependencies

```bash
npm install
```

### 4. Setup Environment

Copy file `.env.example` menjadi `.env`.

```bash
cp .env.example .env
```

Untuk Windows, dapat dilakukan secara manual dengan membuat file:

```text
.env
```

Kemudian sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` dengan konfigurasi MySQL lokal.

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Migration and Seeder

```bash
php artisan migrate:fresh --seed
```

Command tersebut akan membuat tabel database sekaligus memasukkan sample data task.

### 7. Run Development Server

Jalankan Laravel:

```bash
php artisan serve
```

Kemudian jalankan Vite pada terminal lain:

```bash
npm run dev
```

Aplikasi dapat diakses melalui:

```text
http://127.0.0.1:8000/dashboard
```

## API Documentation

Base URL:

```text
http://127.0.0.1:8000/api
```

### Get All Tasks

```http
GET /api/tasks
```

Optional search:

```http
GET /api/tasks?search=dashboard
```

Optional pagination:

```http
GET /api/tasks?page=2
```

Example response:

```json
{
    "success": true,
    "message": "Tasks retrieved successfully.",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "user_id": 1,
                "title": "Build dashboard",
                "description": "Create responsive task management dashboard",
                "status": "in_progress",
                "priority": "high",
                "created_at": "2026-09-27T10:00:00.000000Z",
                "updated_at": "2026-09-27T10:00:00.000000Z"
            }
        ]
    }
}
```

### Get Task Detail

```http
GET /api/tasks/{id}
```

Example:

```http
GET /api/tasks/1
```

If the task does not exist, the API returns:

```http
404 Not Found
```

### Create Task

```http
POST /api/tasks
```

Request body:

```json
{
    "user_id": 1,
    "title": "Create documentation",
    "description": "Write README documentation for the project",
    "status": "pending",
    "priority": "medium"
}
```

Validation:

* `user_id` must exist in the users table
* `title` is required
* `title` maximum 255 characters
* `description` is optional
* `status` must be `pending`, `in_progress`, or `completed`
* `priority` must be `low`, `medium`, or `high`

Successful response:

```http
201 Created
```

### Update Task

```http
PUT /api/tasks/{id}
```

Example:

```http
PUT /api/tasks/1
```

Request body:

```json
{
    "title": "Build responsive dashboard",
    "description": "Update dashboard layout and responsive behavior",
    "status": "in_progress",
    "priority": "high"
}
```

Successful response:

```http
200 OK
```

### Update Task Status

```http
PATCH /api/tasks/{id}/status
```

Example:

```http
PATCH /api/tasks/1/status
```

Request body:

```json
{
    "status": "completed"
}
```

Successful response:

```http
200 OK
```

### Delete Task

```http
DELETE /api/tasks/{id}
```

Example:

```http
DELETE /api/tasks/1
```

Successful response:

```http
200 OK
```

## HTTP Status Codes

The API uses appropriate HTTP status codes:

| Status Code | Description                   |
| ----------- | ----------------------------- |
| 200         | Request successful            |
| 201         | Resource successfully created |
| 404         | Resource not found            |
| 422         | Validation error              |
| 500         | Internal server error         |

## Error Handling

API errors are handled using JSON responses.

Example validation error:

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "title": [
            "The title field is required."
        ]
    }
}
```

Unexpected server errors are logged using Laravel's logging system and return a `500 Internal Server Error` response.

## Logging

Important API operations are logged, including:

* Task creation
* Task update
* Task status update
* Task deletion
* Unexpected API errors

Laravel log files can be found in:

```text
storage/logs/laravel.log
```

## Database Structure

### users

| Column     | Type      |
| ---------- | --------- |
| id         | bigint    |
| name       | varchar   |
| email      | varchar   |
| status     | varchar   |
| created_at | timestamp |
| updated_at | timestamp |

### tasks

| Column      | Type      |
| ----------- | --------- |
| id          | bigint    |
| user_id     | bigint    |
| title       | varchar   |
| description | text      |
| status      | enum      |
| priority    | enum      |
| created_at  | timestamp |
| updated_at  | timestamp |

Relationship:

```text
User
  |
  | 1:N
  ↓
Tasks
```

A user can have multiple tasks, while each task belongs to one user.

## Project Structure

```text
app/
├── Http/
│   └── Controllers/
│       ├── Api/
│       │   └── TaskController.php
│       └── DashboardController.php
│
├── Models/
│   ├── Task.php
│   └── User.php
│
resources/
├── js/
│   └── app.js
│
└── views/
    ├── components/
    │   ├── header.blade.php
    │   └── sidebar.blade.php
    │
    ├── layouts/
    │   └── app.blade.php
    │
    └── dashboard.blade.php

routes/
├── api.php
└── web.php

database/
├── migrations/
└── seeders/
    └── TaskSeeder.php
```

## Testing API

API dapat diuji menggunakan tools seperti:

* Postman
* Insomnia
* Thunder Client
* Browser untuk GET request

Contoh menggunakan cURL:

```bash
curl http://127.0.0.1:8000/api/tasks
```

Create task:

```bash
curl -X POST http://127.0.0.1:8000/api/tasks \
-H "Content-Type: application/json" \
-d "{\"user_id\":1,\"title\":\"Test Task\",\"description\":\"Testing API\",\"status\":\"pending\",\"priority\":\"medium\"}"
```

## Dashboard States

Dashboard memiliki beberapa state untuk meningkatkan user experience:

### Loading State

Ditampilkan ketika dashboard sedang mengambil data task dari REST API.

### Empty State

Ditampilkan ketika tidak terdapat task atau hasil pencarian tidak menemukan task.

### Error State

Ditampilkan ketika terjadi kegagalan saat mengambil data dari API dan menyediakan tombol untuk mencoba kembali.

### Responsive State

Dashboard menyesuaikan tampilan berdasarkan ukuran layar:

* Desktop: sidebar dan table
* Mobile: collapsible sidebar dan task cards

## Development Notes

Project menggunakan JavaScript `fetch()` untuk berkomunikasi dengan REST API.

Alur pengambilan data:

```text
Dashboard
    ↓
JavaScript Fetch
    ↓
GET /api/tasks
    ↓
TaskController
    ↓
Eloquent ORM
    ↓
MySQL
    ↓
JSON Response
    ↓
Dashboard
```

Search dan pagination dilakukan melalui API sehingga data task tidak perlu dimuat seluruhnya ke halaman pada saat awal.

## Author

Naura Azzahra Budiyono

Software Engineering Student
Politeknik Negeri Indramayu
