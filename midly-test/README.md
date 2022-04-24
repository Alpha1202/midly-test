
Midly Test - Completed by Nzubechukwu Nnamani.
=======


## Getting Started
Clone the Repo.
-------------
`git clone https://github.com/Alpha1202/midly-test.git`

## Prerequisites
The following tools will be needed to run this application successfully:
* php v7.3.24 or above
* composer v2.0.9 or above
* mysql

## Installation
**On your Local Machine**
- Pull the [master]( https://github.com/Alpha1202/midly-test.git) branch off this repository
- Run `composer install` to install all dependencies
- Run `php artisan serve` to start the app
- Run `php artisan userListening:update` to start the cronjob
- Access endpoints on **localhost:8000**
## Running the database migrations
Run `php artisan migrate` in the terminal run migrations.


## API Spec
The preferred JSON object to be returned by the API should be structured as follows:

### Users (for authentication)

```source-json
{
  
  "success": true,
  "message": "Authentication successful",
  "total_count": 0,
  "data": {
    "id": 1,
    "name": "Zuby",
    "email": "nzubennamani@gmail.com",
    "token": "AQDK-GhdMh4caiCFsfpRnFzf54rnL7w9_BE0E8BWeZ9QB8-TKvHM7AN3iw-Px-g1Bvnt4KK4MHXgS1y9rKseS9VSzc8jz_REpkWr1ceJT9LrqCWPCzuKtBjHauIMakqmIBs",
    "spotify_id": "31erhjv2sbxdiiiw3xqvi7db7c2e",
    "avatar": ""
  }
}
```
### For user listenings
```source-json
{
  "success": true,
  "message": "Successful",
  "total_count": 0,
  "data": true
}
```
### Errors and Status Codes
If a request fails any validations, expect errors in the following format:

```source-json
{
 "success": false,
 "message": "error message",
}
```

Endpoints:
----------

### Authentication:

`GET /api/fetchSpotifyData`

Attach this url to the base url `http://localhost:8000/` and enter in a browser

When asked, kindly approve the spotify authentication

If you approve, the application will display the user data as stated above

If denied, the application will display an error

This endpoint returns a callback url `http://localhost:8000/api/authenticateUser`


`GET /api/authenticateUser`

This route is used as the callback URL

It is called automatically by spotify after authentication

It returns a user object

### Get User listening

`GET /api/recentTracks`

This endpoint retrieves all the users in the db, then fetches each users recently played data and stores same in the db



### CRON JOBS

The above `GET /api/recentTracks` endpoint has been automated to run daily at midnight routinely


### LIMITATIONS

This application in development mode on spotify, hence can only allow whitelisted users to interact with it. Kindly create a new app or use an exisiting spotify app credentials. find below the .env sample user


### .ENV SAMPLE
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

SPOTIFY_CLIENT_ID=
SPOTIFY_CLIENT_SECRET=
SPOTIFY_REDIRECT_URI=