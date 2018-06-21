# Freelancer Website
<p align="center"><img src="https://github.com/kahell/athmonitor/blob/master/laravel_logo.png" width="160"></p>
<p align="center">
<a href="#" rel="nofollow"><img src="https://camo.githubusercontent.com/aa6cd44c832344c7b6e5edfc8524c46d4bec971b/68747470733a2f2f696d672e736869656c64732e696f2f62616467652f446f6e6174652d50617950616c2d677265656e2e7376673f6d61784167653d363030" alt="Donate" data-canonical-src="https://img.shields.io/badge/Donate-PayPal-green.svg?maxAge=600" style="max-width:100%;"></a>
</p>

## Freelancer Website
Freelancer web is job portal which connect company and expert freelancer/part-timer.
```
Company : Create / Post Job
Freelancer : Apply more job depend your Rank
```
## Architecture
MVC (Model View Controller) - Laravel

## Library Used
- RESTFUL API

## Running
- Clone this repository
- Duplicate .env.example and remove ".example" on that file it must look ".env"
- Create database (ex: db name is 'freelancer_web') and change .env
  DB_DATABASE=freelancer_web
  DB_USERNAME=root
  DB_PASSWORD=
- Create database with name 'testing'
- composer install
- php artisan migrate --seed
- php artisan jwt:secret
- php artisan storage:link
- Activate your xamp / wamp
- php artisan serve
- Open your browser and type 127.0.0.0:8000/

## Preview
check website : -
