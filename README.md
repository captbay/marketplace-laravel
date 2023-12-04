# Laravel API Documentation

## Authentication

### Login

-   Endpoint: `POST /auth/login`
-   Description: Authenticates a user.
-   Request body:
    -   `email`: The user's email.
    -   `password`: The user's password.

### Register

-   Endpoint: `POST /auth/register`
-   Description: Registers a new user.

### Logout

-   Endpoint: `GET /auth/logout`
-   Description: Logs out the authenticated user.

### Change Password

-   Endpoint: `PUT /auth/changePassword`
-   Description: Changes the password of the authenticated user.

## User

### Get User Data

-   Endpoint: `GET /user/dataLogin`
-   Description: Retrieves the data of the authenticated user.

### Update User

-   Endpoint: `PUT /user/update`
-   Description: Updates the data of the authenticated user.

### Upload Profile Picture

-   Endpoint: `POST /user/uploadProfilePicture`
-   Description: Uploads a new profile picture for the authenticated user.

### Delete Profile Picture

-   Endpoint: `DELETE /user/deleteProfilePicture`
-   Description: Deletes the profile picture of the authenticated user.

### Upload Background Picture

-   Endpoint: `POST /user/uploadBackgroundPicture`
-   Description: Uploads a new background picture for the authenticated user.
-   Request body:
    -   `background_picture`: The new background picture file.

### Delete Background Picture

-   Endpoint: `DELETE /user/deleteBackgroundPicture`
-   Description: Deletes the background picture of the authenticated user.

## TOKO per toko

-   CREATE toko form pengusaha (just one) ✅
-   GET detail toko ( to update toko and show produk) ✅
-   UPDATE toko ✅
-   DELETE toko ✅

## Produk per produk

-   CREATE produk to toko including produk_image (many)
-   UPDATE produk toko including produk_image (many)
-   GET detail produk ( to update produk)
-   DELETE produk

## FAVORITE PRODUK

-   CREATE FAVORITE and delete ✅
-   Show all produk favorites ✅

## FEED user

-   SHOW all produk (terbaru and search by name and grup by categories name) ✅
