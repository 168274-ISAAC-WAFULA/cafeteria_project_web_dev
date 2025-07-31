.PHONY: install test fix clean build deploy

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
	pkill -f "php -S localhost:8000"