# fs-coding-challenge
Flexisource IT Coding Challenge

## Installation
1. Clone the repository
```
git clone https://github.com/jgalman711/fs-coding-challenge.git
cd fs-coding-challenge
```

2. Install dependencies
```
composer install
```

3. Create `.env` file
```
cp .env.example .env
```

4. Generate application key
```
php artisan key:generate
```

5. Run the app
```
php artisan serve
```

6. Run tests
```
php artisan test
```

7. Run the importer command
```
php artisan app:import-user
```
Or provide optional parameters:
```
php artisan app:import-user {nationality} {number of results}
```
Example:
```
php artisan app:import-user AU 50
```
