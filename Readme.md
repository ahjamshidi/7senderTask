# Symfony CQRS-Domain Project

This is a sample modular Symfony project implementing **Domain-Driven Design (DDD)**, **CQRS (Command Query Responsibility Segregation)**, and **Event-Driven Architecture**. It is containerized with Docker, uses PostgreSQL as the database, and includes automated tests with PHPUnit.

---

## 🚀 Setup

### 1. Clone the repository

```bash
git clone git@github.com:ahjamshidi/7senderTask.git
cd 7senderTask
```

### 2. Start Docker containers

```bash
docker-compose up -d --build
```

### 3. Bootstrap

```bash
chmod +x ./bootstrap.sh
./bootstrap.sh
```
This shell will :
- Install dependencies
- create database (main and test)
- migrate all migrations
- load sample data to database

---

##  Testing

###  Run tests

```bash
make test
```

---
##  Async consumer Run
```bash
make consume
```
---

## 🧱 Project Structure

```
src/
│── Application/        # Commands and Command Handlers
│── Domain/             # Entities, Value Objects, Domain Events
│── Infrastructure/     # Doctrine Repositories, Filesystem
│── Interface/          # HTTP Controllers
tests/
```

---

## 💡 Architecture

### ✅ CQRS

- Commands represent write operations (e.g. `CreateOrderCommand`)
- Handlers (`CreateOrderHandler`) execute the business use case

### ✅ DDD Principles

- **Entity**: e.g. `Order`, `Product`, `Invoice`
- **Value Object**: e.g. `Money`, `Status`
- **Domain Events**: captured and dispatched using Symfony Messenger
- **Repositories** abstract persistence logic

### ✅ Application Layer

- Accepts DTOs / Commands
- Coordinates use cases
- Validates commands before executing

### ✅ Infrastructure Layer

- Doctrine repositories, external services, etc.

### ✅ Interface Layer

- Controllers
---

## 📁 Example: Order Create Flow

1. User sends a `POST /order` request with this inputs (you should update ids from db Product table ) : 
```env
{
    "email":"a@b.c",
    "items" : [
        {"productId":"250f30a8-9b50-4b07-a156-bba78a9a6514","quantity":2},
        {"productId":"071c3465-4a2d-47ed-a496-05f1dd5f8877","quantity":3},
        {"productId":"d2af10b5-c929-4c66-a7d5-eb20a26f99b3","quantity":4}
    ]
}
```
2. `CreateOrderController` 
    - Validates inputs
    - creates a `CreateOrderCommand`
3. `CreateOrderHandler`:
   - create Order with OrderFactory class
   - decrease Product stock ( implement Optimistic lock to prevent cuncurrency issues )
   - Persists it using `orderRepository`
   - Dispatches an `orderCreated` event
4. `Async Handler`:
    - ProcessPendingOrderHandler consume message
    - processes order
    - create Invoice
    - change order Status 

---

## 📄 .env Configuration

```env
DATABASE_URL=pgsql://postgres:123@database:5432/7Senders_db?serverVersion=16&charset=utf8
POSTGRES_PASSWORD=123
POSTGRES_USER=postgres
POSTGRES_DB=7Senders_db
```

---

## 🐘 Docker Services

```yaml
services:
  php:
    build: ./docker/php
    volumes:
      - .:/var/www/html
    depends_on:
      - database

  nginx:
    image: nginx:stable-alpine
    ports:
      - "8080:80"
    volumes:
      - .:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf

  database:
    image: postgres:16-alpine
    environment:
      POSTGRES_DB: 7Senders_db
      POSTGRES_PASSWORD: 123
      POSTGRES_USER: postgres
    volumes:
      - database_data:/var/lib/postgresql/data:rw

volumes:
  database_data:
```

---

## 🧪 Tests Coverage

### Types of tests:

- ✅ **Unit Tests**: Pure logic, no external dependencies
- ✅ **Integration Tests**: Interactions between multiple components/services
- ✅ **Functional Tests**: Route-to-service verification

---

## 🪪 License

MIT – use it freely.

