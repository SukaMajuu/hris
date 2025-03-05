#!/bin/bash

# Exit on error
set -e

source .env.deploy

# Variables
RESOURCE_GROUP="hris-resources"
LOCATION="eastus"
ACR_NAME="hrisregistry"
POSTGRESQL_SERVER_NAME="hris-postgres-server"
POSTGRESQL_DB_NAME="hris"
POSTGRESQL_ADMIN="hrisadmin"
APP_SERVICE_PLAN="hris-app-service-plan"
FRONTEND_APP_NAME="hris-frontend-sukamaju123"
BACKEND_APP_NAME="hris-backend-sukamaju123"

if [[ -z "$POSTGRESQL_PASSWORD" ]]; then
  echo "Error: POSTGRESQL_PASSWORD not set or empty"
  exit 1
fi

# Create Resource Group if it doesn't exist
echo "Creating resource group..."
az group create --name $RESOURCE_GROUP --location $LOCATION

# Create the Azure Container Registry
echo "Creating Azure Container Registry..."
az acr create --resource-group $RESOURCE_GROUP --name $ACR_NAME --sku Standard

# Get ACR login server (we'll need this later)
ACR_LOGIN_SERVER=$(az acr show --name $ACR_NAME --query loginServer --output tsv)
echo "ACR login server: $ACR_LOGIN_SERVER"

# Create PostgreSQL server
echo "Creating PostgreSQL server..."
az postgres server create \
  --resource-group $RESOURCE_GROUP \
  --name $POSTGRESQL_SERVER_NAME \
  --location $LOCATION \
  --admin-user $POSTGRESQL_ADMIN \
  --admin-password $POSTGRESQL_PASSWORD \
  --sku-name B_Gen5_1 \
  --version 11

# Configure firewall rules to allow Azure services
echo "Configuring PostgreSQL firewall..."
az postgres server firewall-rule create \
  --resource-group $RESOURCE_GROUP \
  --server-name $POSTGRESQL_SERVER_NAME \
  --name AllowAllAzureIPs \
  --start-ip-address 0.0.0.0 \
  --end-ip-address 0.0.0.0

# Create database
echo "Creating PostgreSQL database..."
az postgres db create \
  --resource-group $RESOURCE_GROUP \
  --server-name $POSTGRESQL_SERVER_NAME \
  --name $POSTGRESQL_DB_NAME

# Create App Service Plan (Linux)
echo "Creating App Service Plan..."
az appservice plan create \
  --resource-group $RESOURCE_GROUP \
  --name $APP_SERVICE_PLAN \
  --is-linux \
  --sku B1

# Create Frontend Web App
echo "Creating Frontend Web App..."
az webapp create \
  --resource-group $RESOURCE_GROUP \
  --plan $APP_SERVICE_PLAN \
  --name $FRONTEND_APP_NAME \
  --deployment-container-image-name nginx:alpine

# Create Backend Web App
echo "Creating Backend Web App..."
az webapp create \
  --resource-group $RESOURCE_GROUP \
  --plan $APP_SERVICE_PLAN \
  --name $BACKEND_APP_NAME \
  --deployment-container-image-name nginx:alpine

# Configure Frontend Web App
echo "Configuring Frontend Web App..."
az webapp config appsettings set \
  --resource-group $RESOURCE_GROUP \
  --name $FRONTEND_APP_NAME \
  --settings \
  WEBSITES_PORT=3000 \
  NEXT_PUBLIC_API_URL=https://$BACKEND_APP_NAME.azurewebsites.net

# Configure Backend Web App
echo "Configuring Backend Web App..."
az webapp config appsettings set \
  --resource-group $RESOURCE_GROUP \
  --name $BACKEND_APP_NAME \
  --settings \
  WEBSITES_PORT=80 \
  APP_ENV=production \
  APP_DEBUG=false \
  DB_CONNECTION=pgsql \
  DB_HOST=$POSTGRESQL_SERVER_NAME.postgres.database.azure.com \
  DB_PORT=5432 \
  DB_DATABASE=$POSTGRESQL_DB_NAME \
  DB_USERNAME=$POSTGRESQL_ADMIN@$POSTGRESQL_SERVER_NAME \
  DB_PASSWORD=$POSTGRESQL_PASSWORD

# Get ACR credentials
ACR_USERNAME=$(az acr credential show --name $ACR_NAME --query username --output tsv)
ACR_PASSWORD=$(az acr credential show --name $ACR_NAME --query passwords[0].value --output tsv)

# Configure Frontend Web App with ACR
echo "Configuring Frontend Web App for container deployment..."
az webapp config container set \
  --resource-group $RESOURCE_GROUP \
  --name $FRONTEND_APP_NAME \
  --docker-custom-image-name "$ACR_LOGIN_SERVER/hris-frontend:latest" \
  --docker-registry-server-url "https://$ACR_LOGIN_SERVER" \
  --docker-registry-server-user $ACR_USERNAME \
  --docker-registry-server-password $ACR_PASSWORD

# Configure Backend Web App with ACR
echo "Configuring Backend Web App for container deployment..."
az webapp config container set \
  --resource-group $RESOURCE_GROUP \
  --name $BACKEND_APP_NAME \
  --docker-custom-image-name "$ACR_LOGIN_SERVER/hris-backend:latest" \
  --docker-registry-server-url "https://$ACR_LOGIN_SERVER" \
  --docker-registry-server-user $ACR_USERNAME \
  --docker-registry-server-password $ACR_PASSWORD

# List all resources in the resource group
echo "Listing all resources in $RESOURCE_GROUP..."
az resource list --resource-group $RESOURCE_GROUP --output table
