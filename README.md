# MKM CSV Processing System

## Introduction

This project is designed to demonstrate a robust data processing system using Laravel 10, which handles CSV file uploads and data manipulation securely and efficiently. It includes JWT authentication for API access, a console command to upload files and a simple Vue.js frontend for uploading files, and utilizes Docker for easy setup and deployment.

## Technologies Used

- **Docker Environment**: Simplifies the setup and deployment by containerizing the application and its environment.
- **Queue Service with Supervisor**: Manages job queues to process tasks asynchronously, ensuring application responsiveness.
- **Redis Queue Management**: Uses Redis for queue management to handle background jobs efficiently.
- **JWT Auth**: Secures API endpoints using JSON Web Tokens, ensuring that only authenticated users can access certain operations.
- **spatie/simple-excel**: Utilized for efficient reading and writing of large CSV files in chunks, optimizing memory usage and processing speed.

## Project Requirements

- Composer >= 2.6.6
- PHP >= 8.2
- Node >= 20.11.0
- Npm >= 10.2.4
- Laravel >= 10.10
- Redis (Latest Version)

## Installation Steps Using Docker

1. **Clone the repo**: `git clone https://github.com/akashmanujaya/mkm-csv-processing.git`
2. **Navigate to the Project Directory**: `cd mkm-csv-processing`
3. **Build and Start Docker Containers**: `docker-compose up -d --build`

    After running this command, your containers will be built. Please wait a few seconds until the container runs all the commands inside the terminal. Check your container terminal for logs. After successful installation of all packages, the application will be available at http://0.0.0.0:8000.

4. **Access the Application**: Open your web browser and visit http://0.0.0.0:8000.

## Installation Steps Without Docker

1. **Clone the Repository**
2. **Navigate to the Project Directory**
3. **Install Composer Dependencies**: `composer install`
4. **Install NPM Packages**: `npm install`
5. **Set Up Environment**
    - **Copy the example environment file**: `cp .env.example .env`
    - **Generate an application key**: `php artisan key:generate`
    - **Generate the JWT Secret key**: `php artisan jwt:secret`
6. **Build Assets**: `npm run dev`
7. **Initialize Redis in your local environment**
8. **Serve the Application**: `php artisan serve`

   Then, access the application at http://127.0.0.1:8000/.

9. **Run the Queue Worker**: To process jobs on your queue, you need to start a queue worker. For development, you can start a worker with `php artisan queue:work`.

## Why Very Simple UI?

The simple UI demonstrates experience in Vue.js, JavaScript, CSS, and Tailwind CSS, focusing on functionality over design for this project scope.

## PHP Console Command

To use the implemented console command to process CSV files directly via command line:

- Ensure the CSV file is within the Docker container at `var/www` (as Docker uses a virtual machine and cannot directly access your PC environment). 
- Example command: `php artisan csv:process {path_to_csv_file_inside_container}`

Or if you are using non docker env:

- run the `php artisan csv:process {path_to_csv_file_in_your_local_env}` cosole command.

## Application Program Interface (API)

To run and test the API functionalities, use Postman, a popular tool for API testing. Follow these steps to set up and execute the API calls:

1. **Import the Postman Collection and Environment**:
    - Navigate to the 'API' folder located in the project root.
    - Import the `AML_MKM.postman_collection.json` into Postman, which contains all the API requests you need to interact with the application.
    - Depending on your setup, import either the `AML_MKM Docker.postman_environment.json` or `AML_MKM Localhost.postman_environment.json` into Postman. These environment files configure Postman to point to the correct base URL and manage environmental variables.

2. **Authentication**:
    - Under the **Auth** folder in Postman, you will find the registration and login APIs.
    - **Register**: If you do not have an account, use the registration request to create one. Fill in the required details such as name, email, and password.
    - **Login**: If you already have an account, use the login request to authenticate. Upon successful authentication, the API will return a JWT (JSON Web Token), which Postman is configured to automatically save to an environment variable called `Token`.

3. **Using the Token**:
    - This token is essential for making requests that require authentication. The Postman environment is set up to use this token automatically for all requests that need it, so you don't have to manually insert the token for each request.
    - Ensure that the `Token` variable in your selected Postman environment is updated with the token received from the login response. This setup allows seamless transition between logging in and using secured routes.

4. **Accessing Secured Endpoints**:
    - Navigate to the **CSV** folder within the Postman collection.
    - **Upload CSV**: Use this request to upload a CSV file. The API expects a file upload as part of the request and uses the token for authentication.
    - **Get Product by SKU**: After uploading data, you can use this request to retrieve details of a product by its SKU. This request also requires the JWT for authentication.

5. **Testing**:
    - Execute the requests in the order of registration (if needed), login, CSV upload, and getting product data.
    - Monitor the responses in Postman to ensure each step is successful. Check for HTTP status codes and response messages to diagnose any issues.

6. **Environment Setup**:
    - Make sure the environment in Postman correctly points to your local or Docker-based application instance.
    - Double-check that the base URL in the Postman environment settings matches your running application’s URL (either `http://0.0.0.0:8000` for Docker or `http://127.0.0.1:8000` for a local setup).


## Running Tests

### Using Docker Environment

1. **Access the Container's Shell**: `docker exec -it aml-mkm-csv-processing /bin/bash                  `
2. **Run the Tests**: `php artisan test`

### In Non-Docker Environment

Simply run the tests with the following command in your project root:

```bash
php artisan test
```