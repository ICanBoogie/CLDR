# customization

PACKAGE_NAME = icanboogie/cldr
PACKAGE_VERSION = 2.0
PHPUNIT_VERSION = phpunit-5.phar
PHPUNIT = build/$(PHPUNIT_VERSION)
PHPUNIT_COVERAGE=$(PHPUNIT)

# do not edit the following lines

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
	wget https://phar.phpunit.de/$(PHPUNIT_VERSION) -O $(PHPUNIT)
	chmod +x $(PHPUNIT)

test-container:
	@docker-compose run --rm app sh
	@docker-compose down

test: test-dependencies
	@$(PHPUNIT)

test-coverage: test-dependencies
	@mkdir -p build/coverage
	@$(PHPUNIT_COVERAGE) --coverage-html build/coverage --coverage-clover build/logs/clover.xml

# doc

doc: vendor
	@mkdir -p build/docs
	@apigen generate \
	--source lib \
	--destination build/docs/ \
	--title "$(PACKAGE_NAME) v$(PACKAGE_VERSION)" \
	--template-theme "bootstrap"

# utils

clean:
	@rm -fR build
	@rm -fR vendor
	@rm -f composer.lock
	@rm -f tests/repository/*
