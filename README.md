# Diabetes Prediction System

Sistem prediksi diabetes berbasis Laravel dengan machine learning untuk memprediksi risiko diabetes berdasarkan data pasien.

## 🚀 Features

- **Patient Management**: Add, view, edit, and delete patient records
- **Medical Examinations**: Record patient examination data
- **Machine Learning Models**: Train and manage ML models for diabetes prediction
- **Real-time Predictions**: Get instant diabetes risk predictions
- **Dashboard Analytics**: View statistics and charts of prediction results
- **RESTful API**: Complete API for integration with other systems

## 🏗️ Architecture

- **Backend**: Laravel 10+
- **Frontend**: Blade Templates, Tailwind CSS
- **JavaScript**: Vanilla JS dengan Fetch API
- **Charts**: Chart.js
- **Icons**: Font Awesome
- **Database**: MySQL
- **Containerization**: Docker & Docker Compose

## 🐳 Docker Deployment

Sistem ini sudah siap untuk deployment di Dokploy dengan containerisasi Docker.

### Prerequisites

- Docker & Docker Compose
- Git

### Quick Start with Docker

1. **Clone repository**
   ```bash
   git clone <repository-url>
   cd diabetes-prediction-system
   ```

2. **Deploy with Docker Compose**
   ```bash
   # Option 1: Use deployment script
   chmod +x deploy.sh
   ./deploy.sh
   
   # Option 2: Manual deployment
   docker-compose up --build -d
   ```

3. **Setup application**
   ```bash
   # Install dependencies
   docker-compose exec app composer install
   
   # Generate key
   docker-compose exec app php artisan key:generate
   
   # Run migrations
   docker-compose exec app php artisan migrate --force
   
   # Seed database
   docker-compose exec app php artisan db:seed --force
   ```

4. **Access the application**
   - Web Application: http://localhost:8000
   - Database: localhost:3306

### Docker Services

- **app**: PHP-FPM 8.2 dengan Laravel application
- **webserver**: Nginx dengan PHP-FPM proxy
- **db**: MySQL 8.0 database

### Environment Configuration

File `.env.docker` sudah disiapkan untuk konfigurasi Docker. Untuk deployment production, sesuaikan:
- `APP_URL` dengan domain Anda
- Database credentials
- Security settings

## 📁 Project Structure

```
resources/views/
├── dashboard.blade.php          # Dashboard utama
├── patients/
│   ├── index.blade.php          # Daftar pasien
│   ├── create.blade.php         # Form tambah pasien
│   └── show.blade.php           # Detail pasien
├── predict/
│   └── index.blade.php          # Form prediksi
├── models/
│   └── index.blade.php          # Manajemen model
└── welcome.blade.php            # Halaman default Laravel

docker/
├── nginx/
│   └── conf.d/
│       └── app.conf             # Nginx configuration
├── php/
│   └── php.ini                  # PHP configuration
└── mysql/
    └── my.cnf                   # MySQL configuration
```

## 🔌 API Endpoints

Aplikasi menggunakan API endpoints yang sudah tersedia:

- `GET /api/patients` - Get all patients
- `POST /api/patients` - Create new patient
- `GET /api/patients/{id}` - Get patient details
- `PUT /api/patients/{id}` - Update patient
- `DELETE /api/patients/{id}` - Delete patient
- `GET /api/examinations` - Get all examinations
- `GET /api/models` - Get all ML models
- `POST /api/predict` - Make prediction
- `GET /api/predictions` - Get prediction history

## 🛠️ Development

### Local Development Setup

1. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Setup database**
   - Configure database in `.env`
   - Run migrations: `php artisan migrate`
   - Seed data: `php artisan db:seed`

4. **Start development server**
   ```bash
   php artisan serve
   ```

### Adding New Features

1. **Add new page**
   - Create Blade template in `resources/views/`
   - Add route in `routes/web.php`
   - Update navigation in templates

2. **Custom styling**
   - Use Tailwind CSS classes
   - Add custom CSS in `<style>` tags
   - Responsive breakpoints: sm, md, lg, xl

3. **API integration**
   - All data fetched via Fetch API
   - Error handling with try-catch
   - Loading states for better UX

## 📊 Database Schema

The system includes the following main tables:
- `patients` - Patient demographic and health data
- `examinations` - Medical examination records
- `ml_models` - Machine learning models
- `training_runs` - Model training history
- `predictions` - Prediction results
- `dataset_versions` - Dataset management

## 🔒 Security Features

- Input validation and sanitization
- CSRF protection
- SQL injection prevention
- XSS protection
- Secure API responses

## 📈 Performance

- Optimized database queries
- Efficient frontend loading
- Cached responses
- Responsive design for mobile devices

## 🤝 Contributing

1. Fork the repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

## 📄 License

MIT License

## 🆘 Troubleshooting

### Common Issues

1. **Port already in use**
   - Change ports in `docker-compose.yml`
   - Or stop existing services using the ports

2. **Database connection issues**
   - Check database credentials in `.env.docker`
   - Ensure MySQL container is running

3. **Permission issues**
   - Run: `docker-compose exec app chmod -R 775 storage bootstrap/cache`

4. **Application not loading**
   - Check logs: `docker-compose logs app`
   - Verify nginx configuration

### Useful Commands

```bash
# View logs
docker-compose logs -f app
docker-compose logs -f webserver
docker-compose logs -f db

# Access containers
docker-compose exec app bash
docker-compose exec db mysql -u root -p

# Stop services
docker-compose down

# Rebuild services
docker-compose up --build -d
```

## 📞 Support

For deployment issues or questions about Docker setup, please check the troubleshooting section or create an issue in the repository.
