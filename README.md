# QuickFood

A full-stack food delivery application built with Laravel and React. QuickFood allows users to browse restaurants, view menus, place orders, and manage deliveries efficiently.

## 🎯 Features

- **Restaurant Management**: Browse and view restaurant details, menus, and delivery information
- **Product Catalog**: Browse products by category with detailed information
- **User Authentication**: Secure registration and login system
- **Order Management**: Place orders, track order status, and manage order history
- **User Profiles**: Manage user account settings and preferences
- **Menu Organization**: Categories and products organized by restaurant
- **Order Tracking**: Real-time order status updates

## 🛠️ Tech Stack

### Backend

- **Framework**: Laravel 11
- **Language**: PHP
- **Database**: MySQL
- **Package Manager**: Composer
- **Build Tool**: Vite
- **Testing**: Pest PHP
- **Authentication**: Sanctum (API tokens)

### Frontend

- **Framework**: React 18
- **Build Tool**: Vite
- **State Management**: Redux
- **Package Manager**: npm
- **Styling**: CSS

## 📁 Project Structure

```
QuickFood/
├── Backend/                    # Laravel API backend
│   ├── app/
│   │   ├── Http/Controllers/  # API controllers
│   │   └── Models/            # Eloquent models
│   ├── database/
│   │   ├── migrations/        # Database migrations
│   │   ├── factories/         # Model factories
│   │   └── seeders/           # Database seeders
│   ├── routes/
│   │   └── api.php            # API routes
│   ├── config/                # Configuration files
│   └── public/                # Public directory
│
├── Frontend/                   # React frontend
│   ├── src/
│   │   ├── components/        # React components
│   │   ├── pages/             # Page components
│   │   ├── layouts/           # Layout components
│   │   ├── api/               # API service calls
│   │   ├── redux/             # Redux store configuration
│   │   └── index.css          # Global styles
│   └── public/                # Static assets
```

## 🗄️ Database Models

- **User**: Application users and restaurant owners
- **Restaurant**: Restaurant information and metadata
- **Category**: Product categories within restaurants
- **Product**: Menu items/products
- **Order**: Customer orders
- **OrderItem**: Individual items within orders

## 🚀 Getting Started

### Prerequisites

- Node.js (v16 or higher)
- PHP (v8.2 or higher)
- Composer
- MySQL

### Backend Setup

1. Navigate to the Backend directory:

```bash
cd Backend
```

2. Install dependencies:

```bash
composer install
```

3. Copy the environment file:

```bash
cp .env.example .env
```

4. Generate application key:

```bash
php artisan key:generate
```

5. Create database and run migrations:

```bash
php artisan migrate
```

6. Seed the database (optional):

```bash
php artisan db:seed
```

7. Start the development server:

```bash
php artisan serve
```

The backend will be available at `http://localhost:8000`

### Frontend Setup

1. Navigate to the Frontend directory:

```bash
cd Frontend
```

2. Install dependencies:

```bash
npm install
```

3. Start the development server:

```bash
npm run dev
```

The frontend will be available at `http://localhost:5173` (or as shown in the terminal)

## 📝 Development Guidelines

- Follow PSR-12 coding standards for PHP
- Use meaningful commit messages
- Create feature branches for new features
- Ensure all tests pass before committing
- Update documentation when adding new features

## 📦 Dependencies

### Backend Key Dependencies

- Laravel Framework
- Illuminate components
- PHPUnit / Pest for testing
- Monaco for code documentation

### Frontend Key Dependencies

- React
- Redux
- Vite
- ESLint for code quality

## 🤝 Contributing

1. Create a feature branch (`git checkout -b feature/AmazingFeature`)
2. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
3. Push to the branch (`git push origin feature/AmazingFeature`)
4. Open a Pull Request

## 📄 License

This project is open source and available under the MIT License.

## 📧 Support

For support, please contact the development team or create an issue in the repository.

---

**Last Updated**: February 2026
