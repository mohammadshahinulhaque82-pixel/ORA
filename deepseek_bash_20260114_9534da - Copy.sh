composer create-project laravel/laravel ora-agency
cd ora-agency

# Install required packages
composer require laravel/breeze
composer require spatie/laravel-permission
composer require intervention/image
composer require barryvdh/laravel-dompdf
composer require yajra/laravel-datatables-oracle
composer require laravel/socialite
composer require spatie/laravel-sitemap

# Install NPM packages
npm install
npm install bootstrap @popperjs/core jquery select2 flatpickr chart.js aos sweetalert2 @fortawesome/fontawesome-free axios