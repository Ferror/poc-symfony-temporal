run:
	composer install --no-interaction --prefer-dist
	vendor/bin/rr get --no-interaction
	mv rr /usr/local/bin/rr
	chmod +x /usr/local/bin/rr
	exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
