# customization

PACKAGE_NAME = icanboogie/cldr
PHPUNIT = vendor/bin/phpunit
CACHE_DIR = .cldr-cache

# do not edit the following lines

vendor:
	@composer install

# testing

.PHONY: test-dependencies
test-dependencies: vendor test-cleanup

.PHONY: test
test: test-dependencies
	@$(PHPUNIT)

.PHONY: test-coverage
test-coverage: test-dependencies
	@mkdir -p build/coverage
	@XDEBUG_MODE=coverage $(PHPUNIT) --coverage-html build/coverage

.PHONY: test-coveralls
test-coveralls: test-dependencies
	@mkdir -p build/logs
	@XDEBUG_MODE=coverage $(PHPUNIT) --coverage-clover build/logs/clover.xml

.PHONY: test-cleanup
test-cleanup:
	@rm -f $(CACHE_DIR)/*

.PHONY: test-container
test-container: test-container-81

.PHONY: test-container-81
test-container-81:
	@-docker-compose run --rm app81 bash
	@docker-compose down -v

.PHONY: test-container-82
test-container-82:
	@-docker-compose run --rm app82 bash
	@docker-compose down -v

.PHONY: lint
lint:
	@XDEBUG_MODE=off phpcs -s
	@XDEBUG_MODE=off vendor/bin/phpstan --memory-limit=-1

#
# Generate
#

GENERATE=./generator/generate

.PHONY=generate
generate: \
	src/Core/LocaleId.php \
	src/General/Transforms/HasContextTransforms.php \
	src/General/Units/SequenceCompanion.php \
	src/General/Units/UnitsCompanion.php \
	src/Numbers/Currency.php \
	src/Supplemental/Territory/TerritoryCode.php

src/Core/LocaleId.php: generator/src/Command/GenerateLocaleId.php
	$(GENERATE) $@

src/General/Transforms/HasContextTransforms.php: generator/src/Command/GenerateHasContextTransforms.php
	$(GENERATE) $@

src/General/Units/SequenceCompanion.php: generator/src/Command/GenerateSequenceCompanion.php
	$(GENERATE) $@

src/General/Units/UnitsCompanion.php: generator/src/Command/GenerateUnitsCompanion.php
	$(GENERATE) $@

src/Numbers/Currency.php: generator/src/Command/GenerateCurrency.php
	$(GENERATE) $@

src/Supplemental/Territory/TerritoryCode.php: generator/src/Command/GenerateTerritoryCode.php
	$(GENERATE) $@
