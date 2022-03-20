# Todo List API

A Todo List API, using [Laravel Sanctum for authentication](https://laravel.com/docs/9.x/sanctum)

## Routes
### User
- Register user: **POST** /api/v1/register
- Login user: **POST** /api/v1/login
- Logout user: **POST** /api/v1/logout
### Note
- View all notes for authenticated user: **GET** /api/v1/notes
- View all notes for arbitrary user: **GET** /api/v1/users/{userId}/notes
- Create note for authenticated user: **POST** /api/v1/notes
- Show single note for authenticated user: **GET** /api/v1/notes/{noteId}
- Update note for authenticated user: **PUT** /api/v1/notes/{noteId}
- Delete note for authenticated user: **DELETE** /api/v1/notes/{noteId}
