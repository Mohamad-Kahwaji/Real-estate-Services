# 🏗️ Real Estate Services Platform

A full-stack real estate services platform built with Laravel 12, connecting property owners with buyers and renters — featuring real-time chat, push notifications, multi-gateway payments, and WhatsApp OTP authentication.

---

## ✨ Features

- 🔐 **Authentication** — Token-based auth via Laravel Sanctum + WhatsApp OTP (UltraMsg)
- 🏘️ **Property Listings** — Create, manage, and browse real estate service ads
- 💬 **Real-Time Chat** — Live messaging with file sharing powered by Pusher & Laravel Broadcasting
- 🔔 **Push Notifications** — In-app notifications (Pusher) + mobile push (Firebase FCM)
- 💳 **Multi-Gateway Payments** — Stripe, PayPal, and Bank Transfer support
- 🛠️ **Service Requests** — Users can request and track real estate services
- 📊 **Admin Dashboard** — Full management panel for admins and super admins (Blade UI)
- 🌐 **REST API** — 60+ API endpoints for full mobile/frontend integration

---

## 👥 Role System

| Role            | Description                                                               |
| --------------- | ------------------------------------------------------------------------- |
| **Super Admin** | Full platform control — manages admins, settings, and all data            |
| **Admin**       | Manages users, listings, and service requests                             |
| **User**        | Browses listings, chats, submits service requests, and processes payments |

---

## 🛠️ Tech Stack

| Layer              | Technology                    |
| ------------------ | ----------------------------- |
| Backend            | Laravel 12, PHP 8.2           |
| Authentication     | Laravel Sanctum               |
| Real-Time          | Pusher, Laravel Broadcasting  |
| Push Notifications | Firebase FCM                  |
| Payments           | Stripe, PayPal, Bank Transfer |
| Frontend (Admin)   | Blade, Bootstrap 5            |
| Database           | MySQL                         |
| OTP                | UltraMsg (WhatsApp)           |

---

## 🚀 Getting Started

### Requirements

- PHP 8.2+
- Composer
- MySQL
- Node.js & npm

### Installation

```bash
# Clone the repository
git clone https://github.com/Mohamad-Kahwaji/Real-estate-Services.git
cd Real-estate-Services

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Copy environment file and configure
cp .env.example .env
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# Build frontend assets
npm run build

# Start the server
php artisan serve
```

### Environment Setup

Copy `.env.example` to `.env` and fill in the following:

```env
# Database
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Pusher (Real-Time)
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=

# Stripe
STRIPE_KEY=
STRIPE_SECRET=

# PayPal
PAYPAL_CLIENT_ID=
PAYPAL_CLIENT_SECRET=

# Firebase (Push Notifications)
FIREBASE_CREDENTIALS=storage/app/firebase/service-account.json

# UltraMsg (WhatsApp OTP)
ULTRAMSG_INSTANCE_ID=
ULTRAMSG_TOKEN=
```

---

## 📁 Project Structure

```
app/
├── Http/Controllers/     # 60+ API & web controllers
├── Models/               # Eloquent models
├── Notifications/        # Push & in-app notifications
resources/
├── views/                # Blade templates (User UI + Admin Dashboard)
routes/
├── api.php               # API routes
├── web.php               # Web routes
```

---

## 📌 API Overview

The platform exposes 60+ REST API endpoints covering:

- Auth (register, login, logout, WhatsApp OTP verification)
- Listings (CRUD, search, filter)
- Service Requests (create, update, track status)
- Chat (conversations, messages, file sharing)
- Notifications (list, mark as read)
- Payments (Stripe, PayPal, Bank Transfer)
- Admin (user management, dashboard stats)

---

## 👨‍💻 Author

**Mohamad Kahwaji** — Laravel Backend Developer  
[GitHub](https://github.com/Mohamad-Kahwaji) · [LinkedIn](https://linkedin.com/in/mohamad-kahwaji)

---

## 📄 License

This project is open-source under the [MIT License](LICENSE).
