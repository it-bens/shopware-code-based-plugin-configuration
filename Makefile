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