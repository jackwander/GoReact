# Welcome

### Features:
* Laravel 8
* Livewire
* Authentication
* File Upload
* Image, Video and PDF Rendering
* Amazon S3 Bucket for Storage


## Setup

1. Secure Database Credentials and Create Database for this Application

2. Install the dependencies:

> $ composer install
> $ npm install && npm run dev
> $ chmod -R 775 storage

3. Run the install and follow the instructions:

> $ php artisan goreact::install

## Test

### To test the all the functionalities run:
> $ vendor/bin/phpunit

### Functionality Testing
1. Only Logged-in Users can see the Dashboard Page.

> $ vendor/bin/phpunit --filter only_logged_in_users_can_see_the_dashboard

2. Only Logged-in Users can see the Upload Page.

> $ vendor/bin/phpunit --filter only_logged_in_users_can_see_the_upload

3. Authenticated Users can see the Dashboard Page.

> $ vendor/bin/phpunit --filter authenticated_users_can_see_the_dashboard

4. Authenticated Users can see the Upload Page.

> $ vendor/bin/phpunit --filter authenticated_users_can_see_the_upload

5. Authenticated Users can upload Image.

> $ vendor/bin/phpunit --filter authenticated_users_can_upload_jpg

6. Authenticated Users can upload Video.

> $ vendor/bin/phpunit --filter authenticated_users_can_upload_mp4

7. Authenticated Users can upload PDF.

> $ vendor/bin/phpunit --filter authenticated_users_can_upload_pdf

7. Authenticated Users can view the File.

> $ vendor/bin/phpunit --filter authenticated_can_view_file