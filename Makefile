# customization

PACKAGE_NAME = "ICanBoogie/CLDR"

# do not edit the following lines

usage:
	@echo "test:  Runs the test suite.\ndoc:   Creates the documentation.\nclean: Removes the documentation, the dependencies and the Composer files."

composer.phar:
	@echo "Installing composer..."
	@curl -s https://getcomposer.org/installer | php

vendor: composer.phar
	@php composer.phar install --prefer-source --dev

update:
	@php composer.phar update --prefer-source --dev

autoload:
	@php composer.phar dump-autoload

test: vendor
	@phpunit

doc: vendor
	@mkdir -p "docs"

	@apigen \
	--source ./ \
	--destination docs/ --title $(PACKAGE_NAME) \
	--exclude "*/composer/*" \
	--exclude "*/tests/*" \
	--template-config /usr/share/php/data/ApiGen/templates/bootstrap/config.neon

clean:
	@rm -fR docs
	@rm -fR vendor
	@rm -f composer.lock
	@rm -f composer.phar
