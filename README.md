# Merticalo test

## 🚀 Quick Start

1. **Clone the repository**

```bash
git clone https://github.com/iskanderAB/metricaloTest.git
cd metricaloTest
cp .env .env.local
docker-compose up -d --build
compser install (or docker-compose exec php composer install)
php php bin/console doctrine:migrations:migrate

```
open http://localhost:8000/api/doc
## Happy cooode 