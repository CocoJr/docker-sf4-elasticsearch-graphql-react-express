### Package

 - Nginx (hostname: backend)
 - php 7.2-fpm (hostname: php)
 - node 9.11 (hostname: node)
 - mysql 5.7 (hostname: db)
 - redis 4.0.0 (hostname: redis)
 - elasticsearch 6.3.1 (hostname: elasticsearch{1,2})
 - kibana 6.3.1 (hostname: kibana)

### Installation

1. Create and customise our `.env` files using `.env.dist` template
2. Create your own `crontab` in `engine` using `engine/crontab.dist` template (don't forget to keep one blank new line at the end)
3. Start the project with `docker-compose up --build[ -d]`
4. In development inside the php container:
	1. If it's the first time, install the project: `composer install-dev`
	2. after the first time, you can just rebuild the project using `composer build-dev`
5. In production inside the php container:
	1. `composer build-prod`

### Information

This is a complete boilerplate using react + graphql with symfony and elasticsearch to create your own website.
This boilerplate include complete user management and administration.
It's compatible with server side rendering, using express, and optimized for SEO using Helmet.
You can use it freedomly.
Don't hesite to fork this project and to submit your PR if you want to contribute to this boilerplate.

### Production

Don't forget to use a reverse proxy in your production server to protect your kibana installation, and use with certbot-auto to secure your projects with HTTPS.

### Recommandation

Separate your volumes and your docker when you use this boilerplate. Use different git repository and use submodule.

### Tests

`composer build-test && APP_ENV=test ./bin/paratest` or `composer build-test && APP_ENV=test ./bin/paratest --coverage-html ./var/coverage` for the coverage.
