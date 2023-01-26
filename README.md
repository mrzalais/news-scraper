## Project set-up

In project root folder
- Copy .env.example to .env
- Run `docker compose up -d` (phpunit will not be executable before next command)
- Run `docker compose run --rm composer install`
- Run `docker compose run --rm artisan key:generate`
- Run `docker compose run --rm npm install`
- Run `docker compose run --rm npm run build`
- Run `docker compose run --rm artisan migrate`
<br/><br/>

### Scraper

Executing this command will run the scraper
- Run `docker compose run --rm artisan scraper:scrape`
<br/><br/>
##### Optional

To see if everything is working as intended
- Run `docker compose run --rm phpunit`
