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

1. User sends a `POST /order` request
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

