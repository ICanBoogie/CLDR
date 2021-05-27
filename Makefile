# customization

PACKAGE_NAME = icanboogie/cldr
PACKAGE_VERSION = 3.0
PHPUNIT_VERSION = phpunit-5.phar
PHPUNIT = build/$(PHPUNIT_VERSION)

# do not edit the following lines

.PHONY: usage
usage:
	@echo "test:  Runs the test suite.\ndoc:   Creates the documentation.\nclean: Removes the documentation, the dependencies and the Composer files."

vendor:
	@composer install

update:
	@composer update

autoload: vendor
	@composer dump-autoload

# testing

test-dependencies: vendor $(PHPUNIT)

$(PHPUNIT):
	mkdir -p build
	wget -q https://phar.phpunit.de/$(PHPUNIT_VERSION) -O $(PHPUNIT)
	chmod +x $(PHPUNIT)

.PHONY: test
test: test-dependencies
	@$(PHPUNIT)

.PHONY: test-coverage
test-coverage: test-dependencies
	@mkdir -p build/coverage
	@$(PHPUNIT) --coverage-html build/coverage --coverage-text

.PHONY: test-coveralls
test-coveralls: test-dependencies
	@mkdir -p build/logs
	@$(PHPUNIT) --coverage-clover build/logs/clover.xml

.PHONY: test-container
test-container:
	@docker-compose run --rm app sh
	@docker-compose down

.PHONY: doc
doc: vendor
	@mkdir -p build/docs
	@apigen generate \
	--source lib \
	--destination build/docs/ \
	--title "$(PACKAGE_NAME) v$(PACKAGE_VERSION)" \
	--template-theme "bootstrap"

.PHONY: clean
clean:
	@rm -fR build
	@rm -fR vendor
	@rm -f composer.lock
	@rm -f tests/repository/*
