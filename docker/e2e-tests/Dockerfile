ARG SHOPWARE_VERSION=6.6.9.0

FROM dockware/dev:$SHOPWARE_VERSION

RUN set -ex; \
    jq '. + {"minimum-stability": "dev"} | . + {"prefer-stable": true}' composer.json > composer.tmp.json && \
    mv composer.tmp.json composer.json && \
    jq '.repositories = (.repositories // []) + [{"type": "composer", "url": "https://packagist.org", "exclude": ["it-bens/shopware-code-based-plugin-configuration"]}]' composer.json > composer.tmp.json && \
    mv composer.tmp.json composer.json

RUN set -ex; \
    PHPUNIT_VERSION=$(jq -r '."require-dev"."phpunit/phpunit" // empty' vendor/shopware/core/composer.json) && \
    composer require phpunit/phpunit:"$PHPUNIT_VERSION"

COPY docker/e2e-tests/boot_end.sh /var/www/boot_end.sh