# Marwan Agha's Live Support chat APP Test README #

### How to run
- Run *composer install*
- Adjust the .ENV according to your environment after creating your database
- Run *php artisan migrate*
- Run *php artisan key:generate*
- Run *php artisan passport:install*
- Choose 0
- Run *php artisan passport:install*
- Choose 1


### Cron Jobs
- In *app/Console/Commands* you can find the functionality of requested cron job
- In *app/kernal* you should specify cron job recalling time
- To run the schedule use command *php artisan schedule:run*


### Config
- The *config/constants.php* contains all the needed configuration variables with error codes.

### Mailing
- Place your Mailing account info in .env file to receive System emails
