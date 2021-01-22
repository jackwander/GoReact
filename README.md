# A Simple File Manager App
* Allow uploading and persisting `.jpg` and `.mp4` files
* Show a UI where users can upload new files, and show the list of files uploaded so far
* Provide a way to "preview" the uploaded files.  For images, we want to see the image, and for mp4's, play the video

### Features:
* Laravel 8
* Livewire
* Authentication
* File Upload
* Image, Video and PDF Rendering
* Amazon S3 Bucket for Storage


## Setup

1. Secure the following first
* Database Credentials
* Create Database for this Application
* AWS S3 Bucket Access Information

2. Install the dependencies:

> $ composer install

> $ npm install && npm run dev

> $ chmod -R 777 storage

3. Run the installer and follow the instructions:

> $ php artisan goreact:install

4. After installing successfully

> $ php artisan serve

## Test

### To test the all the functionalities run
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