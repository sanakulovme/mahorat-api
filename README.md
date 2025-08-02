# Education Center Admin Panel

A responsive Laravel admin panel for managing an education center's courses, posts, and applications.

## Features

- **Authentication**: Secure login system with username/password
- **Course Management**: Full CRUD operations for courses
- **Post Management**: Full CRUD operations for blog posts
- **Application Management**: View and manage course applications
- **Responsive Design**: Modern UI built with Tailwind CSS
- **Real-time Updates**: Dynamic content loading with Axios

## Pages

### Admin Panel Pages
- **Login**: `/admin/login` - Admin authentication
- **Dashboard**: `/admin/dashboard` - Overview with statistics
- **Courses**: `/admin/courses` - Course management
- **Posts**: `/admin/posts` - Post management
- **Applications**: `/admin/applications` - Application management

## Database Schema

### Users Table
- `id` (Primary Key)
- `name` (string)
- `username` (string, unique)
- `email` (string, unique)
- `password` (string, hashed)
- `created_at`, `updated_at`

### Courses Table
- `id` (Primary Key)
- `name` (string)
- `description` (text)
- `price` (decimal)
- `url` (string)
- `level` (string)
- `duration` (integer)
- `created_at`, `updated_at`

### Posts Table
- `id` (Primary Key)
- `title` (string)
- `body` (text)
- `user_id` (foreign key to users)
- `status` (string: draft, published, archived)
- `url` (string)
- `created_at`, `updated_at`

### Applications Table
- `id` (Primary Key)
- `fullname` (string)
- `message` (text)
- `phone` (string)
- `course` (string)
- `email` (string)
- `created_at`, `updated_at`

## API Endpoints

### Authentication
- `POST /api/user/login` - Admin login
- `POST /api/user/logout` - Admin logout (protected)

### Courses
- `GET /api/course/viewAll` - Get all courses (protected)
- `GET /api/course/{id}/view` - Get single course (protected)
- `POST /api/course/create` - Create course (protected)
- `POST /api/course/update` - Update course (protected)
- `POST /api/course/{id}/delete` - Delete course (protected)

### Posts
- `GET /api/post/viewAll` - Get all posts (protected)
- `GET /api/post/{id}/view` - Get single post (protected)
- `POST /api/post/create` - Create post (protected)
- `POST /api/post/update` - Update post (protected)
- `POST /api/post/{id}/delete` - Delete post (protected)

### Applications
- `GET /api/application/viewAll` - Get all applications (protected)
- `POST /api/application/create` - Create application (public)
- `POST /api/application/{id}/delete` - Delete application (protected)

## Setup Instructions

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Environment Configuration
Copy the `.env.example` file to `.env` and configure your database:
```bash
cp .env.example .env
```

Update the database configuration in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. Generate Application Key
```bash
php artisan key:generate
```

### 4. Run Migrations
```bash
php artisan migrate
```

### 5. Seed the Database
```bash
php artisan db:seed
```

This will create a default admin user:
- **Username**: `admin`
- **Password**: `admin123`

### 6. Start the Development Server
```bash
php artisan serve
```

### 7. Access the Admin Panel
Navigate to `http://localhost:8000/admin/login`

## Default Admin Credentials

- **Username**: `admin`
- **Password**: `admin123`

## Usage

### Adding a New Course
1. Navigate to `/admin/courses`
2. Click "Add Course"
3. Fill in the course details
4. Click "Save"

### Creating a New Post
1. Navigate to `/admin/posts`
2. Click "Add Post"
3. Fill in the post details
4. Select status (draft, published, archived)
5. Click "Save"

### Viewing Applications
1. Navigate to `/admin/applications`
2. View all submitted applications
3. Click the eye icon to view details
4. Click the trash icon to delete

## Security Features

- **Token-based Authentication**: Uses Laravel Sanctum for API authentication
- **CSRF Protection**: All forms include CSRF tokens
- **Input Validation**: Server-side validation for all inputs
- **Password Hashing**: Secure password storage using Laravel's Hash facade

## Technologies Used

- **Backend**: Laravel 10
- **Frontend**: HTML, CSS (Tailwind CSS), JavaScript
- **Authentication**: Laravel Sanctum
- **Database**: MySQL/PostgreSQL
- **Icons**: Font Awesome
- **HTTP Client**: Axios

## File Structure

```
resources/views/admin/
├── layout.blade.php          # Main admin layout
├── login.blade.php           # Login page
├── dashboard.blade.php       # Dashboard overview
├── courses.blade.php         # Course management
├── posts.blade.php          # Post management
└── applications.blade.php    # Application management

app/Http/Controllers/
├── AuthController.php        # Authentication logic
├── CourseController.php      # Course CRUD operations
├── PostController.php        # Post CRUD operations
└── ApplicationController.php # Application management

app/Models/
├── User.php                  # User model
├── Course.php               # Course model
├── Post.php                 # Post model
└── Application.php          # Application model
```

## Customization

### Adding New Fields
To add new fields to courses, posts, or applications:

1. Create a new migration:
```bash
php artisan make:migration add_new_field_to_table_name
```

2. Update the model's `$fillable` array
3. Update the controller validation rules
4. Update the admin panel forms

### Styling
The admin panel uses Tailwind CSS. You can customize the styling by modifying the classes in the Blade templates.

## Troubleshooting

### Common Issues

1. **Migration Errors**: Make sure your database is properly configured in `.env`
2. **Authentication Issues**: Clear browser cache and localStorage
3. **API Errors**: Check browser console for detailed error messages
4. **Styling Issues**: Ensure Tailwind CSS is loading properly

### Support
For issues or questions, please check the Laravel documentation or create an issue in the repository.