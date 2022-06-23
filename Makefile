# customization

PACKAGE_NAME = icanboogie/cldr
PHPUNIT = vendor/bin/phpunit

# do not edit the following lines

.PHONY: usage
usage:
	@echo "test:  Runs the test suite.\ndoc:   Creates the documentation.\nclean: Removes the documentation, the dependencies and the Composer files."

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
	@XDEBUG_MODE=coverage $(PHPUNIT) --coverage-html build/coverage --coverage-text

.PHONY: test-coveralls
test-coveralls: test-dependencies
	@mkdir -p build/logs
	@XDEBUG_MODE=coverage $(PHPUNIT) --coverage-clover build/logs/clover.xml

.PHONY: test-cleanup
test-cleanup:
	@rm -f cache/*

.PHONY: test-container-71
test-container-71:
	@-docker-compose run --rm app71 bash
	@docker-compose down

.PHONY: test-container-81
test-container-81:
	@-docker-compose run --rm app81 bash
	@docker-compose down

.PHONY: lint
lint:
	@XDEBUG_MODE=off vendor/bin/phpstan
