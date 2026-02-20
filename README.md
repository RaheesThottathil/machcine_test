# Clone the repository
git clone https://github.com/RaheesThottathil/machcine_test.git

# Go into project folder
cd omnific-test

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database inside .env file

# Run migrations with seeders
php artisan migrate --seed

# Start the server
php artisan serve
