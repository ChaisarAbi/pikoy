#!/bin/bash

echo "🚀 Starting Diabetes Prediction System Deployment..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker and try again."
    exit 1
fi

# Build and start containers
echo "📦 Building and starting Docker containers..."
docker-compose down
docker-compose up --build -d

# Wait for services to be ready
echo "⏳ Waiting for services to be ready..."
sleep 30

# Install Composer dependencies
echo "📚 Installing Composer dependencies..."
docker-compose exec app composer install --no-dev --optimize-autoloader

# Generate application key
echo "🔑 Generating application key..."
docker-compose exec app php artisan key:generate

# Run database migrations
echo "🗄️ Running database migrations..."
docker-compose exec app php artisan migrate --force

# Seed the database
echo "🌱 Seeding database..."
docker-compose exec app php artisan db:seed --force

# Set proper permissions
echo "🔒 Setting permissions..."
docker-compose exec app chmod -R 775 storage bootstrap/cache

echo "✅ Deployment completed successfully!"
echo "🌐 Application is running at: http://localhost:8000"
echo "📊 Database is accessible at: localhost:3306"
echo ""
echo "📋 Available commands:"
echo "   docker-compose logs -f app    # View application logs"
echo "   docker-compose exec app bash  # Access application container"
echo "   docker-compose down           # Stop all services"
