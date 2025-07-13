# Xalencia PHP Task

This is a simple RESTful API built using plain PHP and MySQL. The goal of this project was to implement basic user management functionalities CRUD with stored procedures and Docker support, as part of a technical task for a job application.

## Technologies Used

- PHP (without any framework)
- MySQL (with stored procedures)
- Docker & Docker Compose
- Postman (for testing and documentation)

---

## Folder Structure

XILANCIA_TASK/
│
├── sql/
│   ├── schema.sql // Table structure
│   └── procedures.sql // Stored procedures
└── src/
|   ├── index.php // API logic
|   └── .htaccess // Redirects to index.php
├── docker-compose.yml
├── Dockerfile
├── README.md


## Project Features
- Create, read, update and delete users
- Validation of input fields (first name, last name, email)
- Email format and uniqueness check
- API key authentication
- Pagination support on list endpoint
- Postman collection with variables


## 1. Clone the Repository
git clone https://github.com/LaidaR3/xilancia_task
cd xalencia_task


## Start the project
docker-compose up --build


### 4. Test the API in Postman
- Open Postman
- Import `postman/Xilancia_Task.postman_collection.json`
- Update variables:
  - `base_url`: `http://localhost:8000`
  - `API_KEY`: your valid API key (default: `9dj28Jsd!92@Xal#Key`)


## API Endpoints

 Method  Endpoint            Description

 POST    `/users`         --  Create new user    
 GET     `/users`         --  Get all users (supports pagination) 
 GET     `/users/:id`     --  Get user by ID     
 PUT     `/users/:id`     --  Update user        
 DELETE  `/users/:id`     --  Delete user        


## Validation 

- First name and Last name must start with uppercase
- Email must end with `@example.com`
- Email must be unique



## Author
Laida Rusinovci