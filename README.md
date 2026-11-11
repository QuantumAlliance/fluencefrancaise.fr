# 🇫🇷 Fluence Française — French Learning Platform

> A modern, comprehensive e-learning platform and management system designed for mastering the French language. Built with **Laravel 10** and **Vue 3 (SPA)**, Fluence Française connects students with native French tutors, structured language courses (A1 to C2), official DELF/DALF/TCF exam preparations, homework grading, and automated Stripe billing.

---

## 📋 Table of Contents
- [Project Overview](#-project-overview)
- [Key Features & Portals](#-key-features--portals)
  - [1. Student Portal](#1-student-portal)
  - [2. Tutor Portal](#2-tutor-portal)
  - [3. Admin & Super Admin Dashboard](#3-admin--super-admin-dashboard)
  - [4. Public Platform & Checkout](#4-public-platform--checkout)
- [Technology Stack](#-technology-stack)
- [System Requirements](#-system-requirements)
- [Installation & Local Setup](#-installation--local-setup)
- [Running the Application](#-running-the-application)
- [Default Demo Credentials](#-default-demo-credentials)
- [Project Directory Structure](#-project-directory-structure)
- [License](#-license)

---

## 🌟 Project Overview

**Fluence Française** is a full-stack educational web application tailored specifically for French language pedagogy. It combines a public-facing promotional catalogue with dedicated role-based portals for students, native French teachers (tutors), and academic administrators. 

The application architecture utilizes **Laravel 10** as a robust RESTful API backend with **Laravel Sanctum** authentication, paired with a dynamic, single-page application (SPA) frontend powered by **Vue 3**, **Vite**, and **Tailwind CSS**.

---

## 🚀 Key Features & Portals

### 1. Student Portal (`/student`)
- **Dashboard:** Real-time overview of active enrollments, course progress bars, recent homework submissions, and scheduled classes.
- **My Courses & Interactive Player:** Access enrolled course modules with step-by-step syllabus navigation, video lessons, and interactive learning materials.
- **Exam Preparation:** Modules tailored for CEFR-aligned exams (DELF A1–B2, DALF C1–C2, TCF Canada, TEF).
- **Homework Hub:** Download assignments, upload completed homework, and view tutor feedback and grades.
- **Digital Library (E-Books):** Access downloadable French grammar guides, vocabulary cheat-sheets, and curated literature.
- **Account & Subscription:** Profile personalization, time-zone settings, and Stripe payment history.

### 2. Tutor Portal (`/tutor`)
- **Tutor Dashboard:** Key metrics on active classes, upcoming tutoring sessions, and pending homework reviews.
- **Class Types & Scheduling:** Configure private 1-on-1 sessions, group cohorts, and availability schedules.
- **Attendance Tracker:** Mark and review student attendance across scheduled batches.
- **Homework Assignment & Review:** Distribute assignments to specific course cohorts and review submitted student work with custom grading.
- **Learning Materials Repository:** Upload PDFs, audio exercises, and supplementary curriculum files for students.
- **Student Progress Monitoring:** Track individual learner progress across CEFR levels.

### 3. Admin & Super Admin Dashboard (`/admin`)
- **Executive Analytics:** Platform overview including total revenue, active enrollments, tutor activity, and course popularity.
- **Curriculum & Course Management:** Create and update French courses, manage categories, structure syllabi, and set pricing.
- **User Management:** Full administrative controls for students, tutors, and staff accounts.
- **Exam Prep Management:** Manage certification prep material, sample tests, and question banks.
- **Coupon & Discount Engine:** Generate promo codes, percentage/fixed discounts, and usage limits.
- **Digital Library Management:** Upload and manage book catalog with digital previews.
- **System Configuration & Cache Manager:** Application settings, site metadata, SEO defaults, and instant cache clearing.

### 4. Public Platform & Checkout
- **Modern Landing Page:** Clean French navy and tricolor aesthetic presenting courses, tutors, and learning pathways.
- **Course & Exam Catalog:** Search, filter, and view detailed course curriculum and level requirements.
- **Seamless Stripe Checkout:** Secure payment checkout with automatic course enrollment and instant confirmation.
- **Authentication:** Secure login, registration, email verification, and password recovery.

---

## 🛠️ Technology Stack

| Layer | Technology |
| :--- | :--- |
| **Backend Framework** | [Laravel 10](https://laravel.com/) (PHP 8.1+) |
| **API Authentication** | [Laravel Sanctum](https://laravel.com/docs/10.x/sanctum) |
| **Frontend Framework** | [Vue 3](https://vuejs.org/) (Composition API / `<script setup>`) |
| **Frontend Tooling** | [Vite 8](https://vitejs.dev/) & [Laravel Vite Plugin](https://github.com/laravel/vite-plugin) |
| **Routing & State** | [Vue Router 4](https://router.vuejs.org/) & [Pinia](https://pinia.vuejs.org/) |
| **Styling & Icons** | [Tailwind CSS](https://tailwindcss.com/) & [Lucide Vue Next](https://lucide.dev/) |
| **Charts & Editors** | [ApexCharts](https://apexcharts.com/) & [TinyMCE](https://www.tiny.cloud/) |
| **Database** | [MySQL 8.0](https://www.mysql.com/) / MariaDB |
| **Payments** | [Stripe PHP SDK](https://stripe.com/docs/api) |

---

## 💻 System Requirements

Before running the project locally, ensure you have the following installed:

- **PHP**: `>= 8.1` (with `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `curl` extensions)
- **Composer**: `>= 2.2`
- **Node.js**: `>= 18.x` & **npm**: `>= 9.x`
- **MySQL**: `>= 8.0` (or MariaDB `>= 10.4`)

---

## ⚙️ Installation & Local Setup

### 1. Clone the Repository
```bash
git clone https://github.com/carlowandrew/Fluence-Francaise.git
cd Fluence-Francaise
```

### 2. Install Backend Dependencies
```bash
composer install
```

### 3. Environment Configuration
Copy the example environment file and configure your database credentials:
```bash
cp .env.example .env
```

Open `.env` and configure your database connection:
```env
APP_NAME="Fluence Française"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_PORT=8010
SERVER_PORT=8010
APP_URL=http://localhost:8010

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fluence_francaise
DB_USERNAME=root
DB_PASSWORD=your_password
```

Generate the unique application encryption key:
```bash
php artisan key:generate
```

### 4. Database Setup & Seeding
Run the database migrations and seed the initial catalog and test users:
```bash
php artisan migrate --seed
```

### 5. Link Public Storage
Link the storage directory to serve uploaded books, avatars, and curriculum files:
```bash
php artisan storage:link
```

### 6. Install Frontend Dependencies
```bash
npm install
```

---

## 🏃 Running the Application

### Option A: Run Both Services Simultaneously (Recommended)
You can start both the Laravel backend server and Vite frontend server concurrently:
```bash
npm run dev:all
```

### Option B: Run in Separate Terminals

**Terminal 1 — Backend Server:**
```bash
php artisan serve
```
*Backend runs at: `http://127.0.0.1:8010` (configured via `APP_PORT` / `SERVER_PORT` in `.env`)*

**Terminal 2 — Frontend Dev Server (Vite):**
```bash
npm run dev
```
*Frontend runs at: `http://localhost:5174` (or configured Vite port)*

Open your browser and navigate to: **`http://localhost:5174`**

### Building for Production
To compile and minify all frontend assets for production:
```bash
npm run build
```

---

## 🔑 Default Demo Credentials

When running `php artisan db:seed`, the following development accounts are provisioned:

| Role | Email | Password | Access Area |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `admin@fluencefrancaise.local` | `password` | `/admin` |
| **Tutor** | `tutor@fluencefrancaise.local` | `password` | `/tutor` |
| **Student** | `student@fluencefrancaise.local` | `password` | `/student` |

*(Note: In local testing, you can also use `student@test.com` with `Password123!` if configured).*

---

## 📂 Project Directory Structure

```text
Fluence-Francaise/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/           # REST API Controllers (Student, Tutor, Admin, Course, Payment)
│   │   └── Middleware/        # Authentication & Role verification
│   ├── Models/                # Eloquent Models (User, Course, Enrollment, Homework, Book, etc.)
│   └── Services/              # Service Layer (Stripe, Google Sheets, etc.)
├── config/                    # Laravel Configuration files
├── database/
│   ├── factories/             # Model factories
│   ├── migrations/            # Database schema migrations
│   └── seeders/               # Seeders (Settings, Users, Courses, Pages)
├── public/                    # Compiled assets, favicon, index.php
├── resources/
│   ├── css/                   # Global styles & Tailwind CSS setup
│   ├── js/
│   │   ├── components/        # Reusable Vue components (Header, Footer, Modals, Cards)
│   │   ├── composables/       # Vue Composables (useToast, useLoading, useSEO)
│   │   ├── layouts/           # Portal Layouts (StudentLayout, TutorLayout, AdminLayout)
│   │   ├── pages/             # Vue View Pages (public, student, tutor, admin)
│   │   ├── router/            # Vue Router index with route guards
│   │   └── app.js             # Vue 3 SPA entrypoint
│   └── views/
│       └── app.blade.php      # Main single-page application Blade shell
├── routes/
│   ├── api.php                # API route definitions
│   └── web.php                # Web route fallback to Vue SPA
└── vite.config.js             # Vite bundler configuration with Vue & Laravel plugins
```

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).
