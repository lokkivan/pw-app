# PW-app

### Installation
1. Install dependencies
    ```bash
    composer install
    npm install 
    ```
2. Edit `.env` and set your database connection details
3. Run migrations
    ```bash
    php artisan migrate   
    ```
4. Generate application key:
    ```bash
    php artisan key:generate
    php artisan jwt:secret
    ```
5. Running seeders
    ```
    php artisan db:seed 
   
   # Super User 
    login: admin@example.ru
    password: 123456
   
    ``` 
   
6. Command to create your own super user
    ```bash
    php artisan create:super-user
    ``` 
## Usage

#### Development

```bash
# Build and watch
npm run watch
```

#### Production

```bash
npm run production
```
