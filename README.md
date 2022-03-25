## Project Features
- Enter URL and get short URL format
- URL shortened with 6 symbols code.
- Used "Google Safe Browsing" API to validate provided URL.
- Fetched short url from DB if URL already exists.
- Can set optional subdomain for short URL
- Redirect with short URL in multiple ways
    - click on short URL to redirect
    - put the generated short url on browser and get redirected to original URL.
    - Enter short URL and click on "redirect" button.    
 

## Installation
- `composer install`
- `npm install`
- `rename .env.example to .env file`
- `put your own db credentials`
- `php artisan key:generate`
- `php artisan config:cache`
- `php artisan migrate`
- `php artisan serve`
