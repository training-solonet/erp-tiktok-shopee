# ERP TikTok & Shopee

![Deploy Status](https://github.com/training-solonet/erp-tiktok-shopee/actions/workflows/deploy.yml/badge.svg)
![PHP](https://img.shields.io/badge/PHP-8.3-blue)
![Laravel](https://img.shields.io/badge/Laravel-11-red)
![License](https://img.shields.io/badge/license-MIT-green)

ERP System terintegrasi dengan TikTok Shop & Shopee API untuk mengelola produk, inventory, dan orders.

## 🚀 Features

- ✅ **TikTok Shop Integration**
  - Auto-refresh access token
  - Product catalog management
  - Real-time stock & pricing
  - Signature authentication (HMAC-SHA256)

- ✅ **Modern UI/UX**
  - Tailwind CSS responsive design
  - Interactive dashboard
  - Real-time statistics

- ✅ **CI/CD Pipeline**
  - Automated code quality checks (Laravel Pint)
  - Blade template formatting
  - Auto-deploy to VPS via SSH

## 📋 Requirements

- PHP 8.3 or higher
- Composer
- Node.js 22+ & NPM
- MySQL/PostgreSQL
- Git

## 🛠️ Development

### Code Quality
```bash
# Fix PHP code style
./vendor/bin/pint