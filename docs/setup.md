# HRIS Setup Guide

This document provides comprehensive instructions for setting up and deploying the Human Resource Information System (HRIS).

## Table of Contents

-   [HRIS Setup Guide](#hris-setup-guide)
    -   [Table of Contents](#table-of-contents)
    -   [Prerequisites](#prerequisites)
    -   [Local Development Setup](#local-development-setup)
        -   [Frontend Setup (Next.js)](#frontend-setup-nextjs)
        -   [Backend Setup (Laravel)](#backend-setup-laravel)
    -   [Docker Setup](#docker-setup)
    -   [Azure Deployment](#azure-deployment)
        -   [Prerequisites for Azure Deployment](#prerequisites-for-azure-deployment)
        -   [Deployment Steps](#deployment-steps)
    -   [CI/CD Configuration](#cicd-configuration)
        -   [Continuous Integration](#continuous-integration)
        -   [Continuous Deployment](#continuous-deployment)

## Prerequisites

Before you begin, ensure you have the following installed:

-   Node.js 18.x or newer
-   PHP 8.2 or newer
-   Composer 2.x
-   Docker and Docker Compose
-   Azure CLI (for deployment)
-   Git

## Local Development Setup

### Frontend Setup (Next.js)

1. Navigate to the frontend directory:
    ```bash
    cd apps/frontend
    ```
2. Install dependencies:
    ```bash
    npm install
    ```
3. Create a .env file:
    ```bash
    cp .env.example .env
    ```
4. Update the environment variables in .env as needed.
5. Start the development server:
    ```bash
    npm run dev
    ```
6. The Next.js application will be available at http://localhost:3000

### Backend Setup (Laravel)

1. Navigate to the backend directory:
    ```bash
     cd apps/backend
    ```
2. Install PHP dependencies:
    ```bash
     composer install
    ```
3. Create an environment file:
    ```bash
     cp .env.example .env
    ```
4. Generate application key:
    ```bash
     php artisan key:generate
    ```
5. Configure your database in the .env file:
    ```bash
     DB_CONNECTION=pgsql
     DB_HOST=localhost
     DB_PORT=5432
     DB_DATABASE=hris
     DB_USERNAME=your_username
     DB_PASSWORD=your_password
    ```
6. Run migrations and seed the database:
    ```bash
     php artisan migrate --seed
    ```
7. Start the development server:
    ```bash
     php artisan serve
    ```
8. The Laravel API will be available at http://localhost:8000

## Docker Setup

For containerized development or production, use Docker Compose:

1. Ensure Docker and Docker Compose are installed.
2. Build and start the containers:
    ```bash
    cd infrastructure/docker
    docker-compose up -d
    ```
3. The services will be available at:
    - Frontend: http://localhost:3000
    - Backend API: http://localhost:80
    - PostgreSQL: localhost:5432
4. To stop the containers:
    ```bash
    docker-compose down
    ```

## Azure Deployment

### Prerequisites for Azure Deployment

-   Azure subscription
-   Azure CLI installed and logged in
-   Service principal with appropriate permissions
-   GitHub repository with your code

### Deployment Steps

1. Set up environment variables for deployment:

    ```bash
    cd infrastructure/azure/scripts
    cp .env.deploy.example .env.deploy
    ```

2. Edit .env.deploy with your PostgreSQL password
3. Run the deployment script:
    ```bash
    ./deploy.sh
    ```
    This script will:
    - Create a resource group
    - Set up Azure Container Registry
    - Create a PostgreSQL database
    - Configure App Service for frontend and backend
    - Set up necessary environment variables
4. For CI/CD setup, add the following secrets to your GitHub repository:
    - AZURE_CREDENTIALS: Service principal credentials
    - AZURE_CONTAINER_REGISTRY: ACR login server
    - AZURE_REGISTRY_USERNAME: ACR username
    - AZURE_REGISTRY_PASSWORD: ACR password
    - AZURE_FRONTEND_PUBLISH_PROFILE: Frontend publish profile
    - AZURE_BACKEND_PUBLISH_PROFILE: Backend publish profile

## CI/CD Configuration

The project includes GitHub Actions workflows for:

### Continuous Integration

The CI workflow runs on pull requests and performs:

-   Code linting
-   Unit tests
-   Integration tests

### Continuous Deployment

Deployment workflows run when code is pushed to the main branch:

-   Build Docker images
-   Push images to Azure Container Registry
-   Deploy the applications to Azure App Service
