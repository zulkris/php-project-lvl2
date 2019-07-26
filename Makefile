install:
	composer install
lint: install
	composer run-script phpcs -- --standard=PSR12 src bin	
