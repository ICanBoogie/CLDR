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
	src/Core/LocaleData.php \
	src/General/Transforms/HasContextTransforms.php \
	src/Numbers/CurrencyData.php \
	src/Supplemental/Territory/TerritoryData.php \
	src/Supplemental/Units/SequenceCompanion.php \
	src/Supplemental/Units/UnitsCompanion.php

src/Core/LocaleData.php: generator/src/Command/GenerateLocaleData.php
	$(GENERATE) $@

src/General/Transforms/HasContextTransforms.php: generator/src/Command/GenerateHasContextTransforms.php
	$(GENERATE) $@

src/Numbers/CurrencyData.php: generator/src/Command/GenerateCurrencyData.php
	$(GENERATE) $@

src/Supplemental/Territory/TerritoryData.php: generator/src/Command/GenerateTerritoryData.php
	$(GENERATE) $@

src/Supplemental/Units/SequenceCompanion.php: generator/src/Command/GenerateSequenceCompanion.php
	$(GENERATE) $@

src/Supplemental/Units/UnitsCompanion.php: generator/src/Command/GenerateUnitsCompanion.php
	$(GENERATE) $@
