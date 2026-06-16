# School Management System

## Installation

```bash
git clone <repo-url>
cd school_management
composer install
npm install
```

```bash
cp .env.example .env        # Linux/Mac
copy .env.example .env      # Windows
php artisan key:generate
```

```bash
php artisan migrate:fresh --seed
```

```bash
composer run dev
```

App runs at `http://127.0.0.1:8000`

---

## Login Credentials

### Super Admin

| Email            | Password |
|------------------|----------|
| sadmin@admin.com | suad123  |

### Teachers

| Email            | Password | Role                 | Class Group          |
|------------------|----------|----------------------|----------------------|
| alice@school.com | ali123   | Primary Teacher      | Primary - Grade 1    |
| bob@school.com   | bob123   | Primary Teacher      | Primary - Grade 2    |
| carol@school.com | car123   | High School Teacher  | High School - Form 1 |
| david@school.com | dav123   | High School Teacher  | High School - Form 2 |

### Students

| Email               | Password | Class Group          |
|---------------------|----------|----------------------|
| charlie@school.com  | cha123   | Primary - Grade 1    |
| diana@school.com    | dia123   | Primary - Grade 1    |
| ethan@school.com    | eth123   | Primary - Grade 2    |
| fiona@school.com    | fio123   | Primary - Grade 2    |
| george@school.com   | geo123   | High School - Form 1 |
| hannah@school.com   | han123   | High School - Form 1 |
| ivan@school.com     | iva123   | High School - Form 2 |
| julia@school.com    | jul123   | High School - Form 2 |
