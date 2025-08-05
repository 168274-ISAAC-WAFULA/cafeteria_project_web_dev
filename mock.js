const fs = require('fs');
const { execSync } = require('child_process');
const path = require('path');
const os = require('os');

// Configuration
const PROJECT_NAME = 'TaskFlow';
const DAYS_BACK = 14; // How many days back to start commits
const COMMIT_COUNT = 15; // Number of commits to make

// File templates
const fileTemplates = {
  'README.md': `# ${PROJECT_NAME}

A modern PHP task management system built with clean architecture principles.

## Features

- User authentication and authorization
- Task creation and management
- Project organization
- Real-time notifications
- RESTful API
- Responsive web interface

## Requirements

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- Node.js and npm

## Installation

1. Clone the repository
2. Run \`composer install\`
3. Copy \`.env.example\` to \`.env\`
4. Configure your database settings
5. Run \`php artisan migrate\`
6. Start the development server

## License

MIT License
`,

  'CHANGELOG.md': `# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Added
- Initial project structure
- Basic authentication system
- Task CRUD operations

### Changed
- Updated database schema

### Fixed
- Minor bug fixes in user registration

## [1.0.0] - 2025-01-20

### Added
- Initial release
- Core functionality implemented
`,

  'composer.json': JSON.stringify({
    "name": "company/taskflow",
    "description": "A modern PHP task management system",
    "type": "project",
    "license": "MIT",
    "require": {
      "php": ">=8.1",
      "illuminate/database": "^10.0",
      "illuminate/validation": "^10.0",
      "firebase/php-jwt": "^6.0",
      "vlucas/phpdotenv": "^5.4"
    },
    "require-dev": {
      "phpunit/phpunit": "^10.0",
      "friendsofphp/php-cs-fixer": "^3.0"
    },
    "autoload": {
      "psr-4": {
        "App\\": "src/"
      }
    },
    "scripts": {
      "test": "phpunit",
      "fix": "php-cs-fixer fix"
    }
  }, null, 2),

  'package.json': JSON.stringify({
    "name": "taskflow-frontend",
    "version": "1.0.0",
    "description": "Frontend assets for TaskFlow",
    "scripts": {
      "build": "webpack --mode production",
      "dev": "webpack --mode development --watch",
      "test": "jest"
    },
    "devDependencies": {
      "webpack": "^5.0.0",
      "webpack-cli": "^4.0.0",
      "@babel/core": "^7.0.0",
      "sass": "^1.0.0",
      "jest": "^29.0.0"
    }
  }, null, 2),

  '.env.example': `APP_NAME=TaskFlow
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskflow
DB_USERNAME=root
DB_PASSWORD=

JWT_SECRET=your-secret-key-here
JWT_EXPIRE=3600

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
`,

  '.gitignore': `# Dependencies
/vendor/
/node_modules/

# Environment files
.env
.env.local
.env.production

# IDE files
.vscode/
.idea/
*.swp
*.swo

# OS files
.DS_Store
Thumbs.db

# Build files
/public/build/
/public/hot

# Logs
*.log
/storage/logs/

# Cache
/bootstrap/cache/
/storage/framework/cache/
/storage/framework/sessions/
/storage/framework/views/
`,

  'phpunit.xml': `<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/10.0/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="Unit">
            <directory suffix="Test.php">./tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory suffix=".php">./src</directory>
        </include>
    </source>
</phpunit>`,

  'docs/API.md': `# API Documentation

## Authentication

All API endpoints require authentication via JWT token.

### Login
\`\`\`
POST /api/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
\`\`\`

Response:
\`\`\`json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "user@example.com"
  }
}
\`\`\`

## Tasks

### Get All Tasks
\`\`\`
GET /api/tasks
Authorization: Bearer {token}
\`\`\`

### Create Task
\`\`\`
POST /api/tasks
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Task Title",
  "description": "Task description",
  "due_date": "2025-02-15",
  "priority": "high"
}
\`\`\`

### Update Task
\`\`\`
PUT /api/tasks/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Updated Title",
  "status": "completed"
}
\`\`\`

### Delete Task
\`\`\`
DELETE /api/tasks/{id}
Authorization: Bearer {token}
\`\`\`
`,

  'docs/DEPLOYMENT.md': `# Deployment Guide

## Production Setup

### Server Requirements
- PHP 8.1+ with extensions: mbstring, pdo_mysql, curl, json
- MySQL 5.7 or MariaDB 10.3+
- Nginx or Apache
- SSL certificate

### Deployment Steps

1. **Clone repository**
   \`\`\`bash
   git clone https://github.com/company/taskflow.git
   cd taskflow
   \`\`\`

2. **Install dependencies**
   \`\`\`bash
   composer install --no-dev --optimize-autoloader
   npm install && npm run build
   \`\`\`

3. **Environment setup**
   \`\`\`bash
   cp .env.example .env
   # Edit .env with production values
   \`\`\`

4. **Database migration**
   \`\`\`bash
   php artisan migrate --force
   \`\`\`

5. **Set permissions**
   \`\`\`bash
   chmod -R 755 storage bootstrap/cache
   \`\`\`

### Nginx Configuration

\`\`\`nginx
server {
    listen 80;
    server_name taskflow.example.com;
    root /var/www/taskflow/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \\.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
\`\`\`
`,

  'docs/CONTRIBUTING.md': `# Contributing Guide

Thank you for considering contributing to TaskFlow!

## Development Process

1. Fork the repository
2. Create a feature branch (\`git checkout -b feature/amazing-feature\`)
3. Make your changes
4. Write or update tests
5. Run the test suite
6. Commit your changes (\`git commit -m 'Add amazing feature'\`)
7. Push to the branch (\`git push origin feature/amazing-feature\`)
8. Open a Pull Request

## Code Style

We use PHP-CS-Fixer to maintain consistent code style:

\`\`\`bash
composer run fix
\`\`\`

## Testing

Run the test suite before submitting:

\`\`\`bash
composer test
\`\`\`

## Commit Messages

Use conventional commit format:
- \`feat:\` new features
- \`fix:\` bug fixes
- \`docs:\` documentation changes
- \`style:\` formatting changes
- \`refactor:\` code refactoring
- \`test:\` adding tests
- \`chore:\` maintenance tasks

## Code Review

All submissions require review. We use GitHub pull requests for this purpose.
`,

  'webpack.config.js': `const path = require('path');

module.exports = {
  entry: './resources/js/app.js',
  output: {
    path: path.resolve(__dirname, 'public/build'),
    filename: 'app.js',
  },
  module: {
    rules: [
      {
        test: /\\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env']
          }
        }
      },
      {
        test: /\\.scss$/,
        use: ['style-loader', 'css-loader', 'sass-loader']
      }
    ]
  },
  resolve: {
    extensions: ['.js', '.scss']
  }
};`,

  'docker-compose.yml': `version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    ports:
      - "8000:8000"
    volumes:
      - .:/var/www/html
    environment:
      - APP_ENV=local
    depends_on:
      - database

  database:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: secret
      MYSQL_DATABASE: taskflow
      MYSQL_USER: taskflow
      MYSQL_PASSWORD: secret
    ports:
      - "3306:3306"
    volumes:
      - mysql_data:/var/lib/mysql

  redis:
    image: redis:alpine
    ports:
      - "6379:6379"

volumes:
  mysql_data:`,

  'Dockerfile': `FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \\
    git \\
    curl \\
    libpng-dev \\
    libonig-dev \\
    libxml2-dev \\
    zip \\
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www/html \\
    && chmod -R 755 /var/www/html/storage

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]`,

  'Makefile': `.PHONY: install test fix clean build deploy

install:
	composer install
	npm install

test:
	./vendor/bin/phpunit
	npm test

fix:
	./vendor/bin/php-cs-fixer fix

clean:
	rm -rf vendor node_modules public/build

build:
	composer install --no-dev --optimize-autoloader
	npm run build

deploy: build
	rsync -avz --exclude-from='.gitignore' . user@server:/var/www/taskflow/

dev:
	php -S localhost:8000 -t public &
	npm run dev

stop:
	pkill -f "php -S localhost:8000"`
};

// Commit sequence with realistic messages and timing
const commits = [
  { files: ['README.md'], message: 'docs: add initial README with project overview', daysAgo: 14 },
  { files: ['.gitignore'], message: 'chore: add gitignore for PHP project', daysAgo: 13 },
  { files: ['composer.json'], message: 'feat: initialize composer with core dependencies', daysAgo: 13 },
  { files: ['package.json'], message: 'feat: add package.json for frontend build tools', daysAgo: 12 },
  { files: ['.env.example'], message: 'config: add environment configuration template', daysAgo: 12 },
  { files: ['phpunit.xml'], message: 'test: configure PHPUnit for testing', daysAgo: 11 },
  { files: ['docs/API.md'], message: 'docs: add API documentation', daysAgo: 10 },
  { files: ['webpack.config.js'], message: 'build: configure webpack for asset compilation', daysAgo: 9 },
  { files: ['docker-compose.yml', 'Dockerfile'], message: 'feat: add Docker configuration for development', daysAgo: 8 },
  { files: ['docs/DEPLOYMENT.md'], message: 'docs: add deployment guide and server configuration', daysAgo: 7 },
  { files: ['docs/CONTRIBUTING.md'], message: 'docs: add contributing guidelines', daysAgo: 6 },
  { files: ['Makefile'], message: 'build: add Makefile for common development tasks', daysAgo: 5 },
  { files: ['CHANGELOG.md'], message: 'docs: add changelog to track project changes', daysAgo: 4 },
  { files: ['README.md'], message: 'docs: update README with installation instructions', daysAgo: 3 },
  { files: ['composer.json'], message: 'deps: add development dependencies for code quality', daysAgo: 2 }
];

function createDirectories() {
  const dirs = ['docs', 'tests\\Unit', 'tests\\Feature', 'src', 'public', 'resources\\js'];
  dirs.forEach(dir => {
    if (!fs.existsSync(dir)) {
      fs.mkdirSync(dir, { recursive: true });
    }
  });
}

function createFile(filePath, content) {
  const dir = path.dirname(filePath);
  if (!fs.existsSync(dir)) {
    fs.mkdirSync(dir, { recursive: true });
  }
  fs.writeFileSync(filePath, content);
}

function makeCommit(files, message, daysAgo) {
  try {
    // Create/update files
    files.forEach(file => {
      const content = fileTemplates[file];
      if (content) {
        createFile(file, content);
        console.log(`Created: ${file}`);
      }
    });

    // Add files to git (use double quotes for Windows compatibility)
    const fileList = files.map(f => `"${f}"`).join(' ');
    execSync(`git add ${fileList}`, { stdio: 'inherit' });

    // Calculate commit date
    const commitDate = new Date();
    commitDate.setDate(commitDate.getDate() - daysAgo);
    commitDate.setHours(9 + Math.floor(Math.random() * 8)); // Random hour between 9-17
    commitDate.setMinutes(Math.floor(Math.random() * 60));

    // Format date for Windows git (use ISO format)
    const dateString = commitDate.toISOString();

    // Make commit with backdated timestamp (use double quotes for Windows)
    const commitCmd = `git commit --date="${dateString}" -m "${message}"`;
    execSync(commitCmd, { stdio: 'inherit' });
    
    console.log(`✅ Committed: ${message} (${daysAgo} days ago)`);
  } catch (error) {
    console.error(`❌ Error making commit: ${error.message}`);
  }
}

function initializeGit() {
  try {
    // Check if git repo exists
    execSync('git status', { stdio: 'ignore' });
    console.log('Git repository already exists');
  } catch (error) {
    console.log('Initializing git repository...');
    execSync('git init', { stdio: 'inherit' });
    execSync('git config user.name "Developer"', { stdio: 'inherit' });
    execSync('git config user.email "dev@taskflow.local"', { stdio: 'inherit' });
  }
}

function main() {
  console.log(`🚀 Creating ${PROJECT_NAME} project with commit history...\n`);

  // Initialize git if needed
  initializeGit();

  // Create directory structure
  createDirectories();

  // Make commits in sequence
  commits.forEach((commit, index) => {
    console.log(`\n📝 Commit ${index + 1}/${commits.length}`);
    makeCommit(commit.files, commit.message, commit.daysAgo);
    
    // Small delay to ensure different timestamps (Windows compatible)
    if (index < commits.length - 1) {
      // Use setTimeout instead of sleep for Windows compatibility
      const start = Date.now();
      while (Date.now() - start < 1000) {
        // Wait 1 second
      }
    }
  });

  console.log(`\n✨ Successfully created ${commits.length} commits for ${PROJECT_NAME}!`);
  console.log('\n📊 View the commit history with:');
  console.log('git log --oneline --graph');
  console.log('\n🚀 Push to GitHub with:');
  console.log('git remote add origin <your-repo-url>');
  console.log('git branch -M main');
  console.log('git push -u origin main');
}

// Run the script
if (require.main === module) {
  main();
}