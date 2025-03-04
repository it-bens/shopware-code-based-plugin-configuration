E2E_TESTS_SHOPWARE_SERVICE = e2e-tests-shopware

.PHONY: style-check style-fix code-upgrade static-analysis tests e2e-tests

style-check:
	composer style-check

style-fix:
	composer style-fix

code-upgrade:
	composer code-upgrade

static-analysis:
	composer static-analysis

tests:
	composer unit-tests
	composer integration-tests

e2e-tests-6.5:
	docker compose exec -u www-data $(E2E_TESTS_SHOPWARE_SERVICE)-6.5 /var/www/html/vendor/bin/phpunit --configuration /var/www/html/custom/plugins/TestPlugin/phpunit.shopware-6.5.xml

e2e-tests-6.6:
	docker compose exec -u www-data $(E2E_TESTS_SHOPWARE_SERVICE)-6.6 /var/www/html/vendor/bin/phpunit --configuration /var/www/html/custom/plugins/TestPlugin/phpunit.shopware-6.6.xml