# API Documentation

## Authentication

All API endpoints require authentication via JWT token.

### Login
```
POST /api/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

Response:
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "user@example.com"
  }
}
```

## Tasks

### Get All Tasks
```
GET /api/tasks
Authorization: Bearer {token}
```

### Create Task
```
POST /api/tasks
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Task Title",
  "description": "Task description",
  "due_date": "2025-02-15",
  "priority": "high"
}
```

### Update Task
```
PUT /api/tasks/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Updated Title",
  "status": "completed"
}
```

### Delete Task
```
DELETE /api/tasks/{id}
Authorization: Bearer {token}
```
