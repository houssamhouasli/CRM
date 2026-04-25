# CRM Laravel Project

## 📌 Description
Application web développée avec Laravel pour la gestion des clients, commandes et stock.

## ⚙️ Installation

```bash
git clone https://github.com/houssamhouasli/CRM.git
cd CRM
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve