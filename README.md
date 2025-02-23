# Laravel Project - Setup Guide

##  Project Overview
This is a Laravel-based project with the following features:
-  **Image Upload with Validation & Storage**
-  **Product Inventory Management (Stock Decreases on Orders)**
-  **Docker Containerization**
-  **Caching for Product Listings**
-  **Role-Based Authorization (Super Admin, Admin, Customer)**

---

##  1. Clone the Repository
```bash
git clone https://github.com/priyankaillikkattil/Life.git
cd life
```

---

##  2. Environment Setup
###  Copy `.env` file
```bash
cp .env.example .env
```
###  Generate Application Key
```bash
php artisan key:generate
```

---

##  3. Run with Docker (Recommended)
Ensure **Docker** & **Docker Compose** are installed.

###  Start Docker Containers
```bash
docker-compose up -d
```
###  Run Migrations & Seed Database
```bash
docker exec -it laravel_app php artisan migrate --seed
```

---

##  4. Run Locally (Without Docker)
###  Install Dependencies
```bash
composer install
npm install && npm run dev
```
###  Start Local Server
```bash
php artisan serve
```
###  Run Database Migrations
```bash
php artisan migrate --seed
```

---

##  5. Storage & Image Uploads
###  Link Storage
```bash
php artisan storage:link
```
Uploaded images will be available at:
```
http://127.0.0.1:8000/storage/products/image.jpg
```

---

##  6. Caching
###  Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

##  7. Running Tests
Run **PHPUnit** tests to ensure everything is working.
```bash
php artisan test
```
