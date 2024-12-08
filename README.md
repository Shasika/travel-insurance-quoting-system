
# Travel Insurance Quoting System

A **Livewire** and **Laravel** application to calculate, manage, and display travel insurance quotes dynamically.

---

## Features
- Dynamic form to calculate travel insurance quotes.
- Validation for input fields such as dates, destination, and travelers.
- Reset functionality for clearing forms.
- Quote price calculation based on selected destination and coverage options.
- Quote editing and updating.
- Responsive UI with Tailwind CSS.

---

## Prerequisites
Ensure you have the following installed:
- PHP <= 8.3
- Composer
- Node.js <= 20.18.1
- npm
- MySQL or any other database supported by Laravel

---

## Installation

### Step 1: Clone the Repository
```bash
git clone https://github.com/Shasika/travel-insurance-quoting-system
cd travel-insurance-quoting-system
```
### Step 2: Install PHP Dependencies
```bash
composer install
```
### Step 3: Install Node.js Dependencies
```bash
npm install
```
### Step 4: Set Up Environment File
Duplicate the `.env.example` file and rename it to `.env`
Configure your database credentials:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=travel_insurance
DB_USERNAME=root
DB_PASSWORD=
```
### Step 5: Generate Application Key
```bash
php artisan key:generate
```
### Step 6: Run Migrations
```bash
php artisan migrate
```
### Step 7: Start the Application
```bash
php artisan serve
npm run dev
```
