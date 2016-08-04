#
# JBZoo PHPUnit
#
# This file is part of the JBZoo CCK package.
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
# @package   PHPUnit
# @license   MIT
# @copyright Copyright (C) JBZoo.com,  All rights reserved.
# @link      https://github.com/JBZoo/PHPUnit
#

.PHONY: build update test-all validate autoload test phpmd phpcs phpcpd phploc reset coveralls

build: update server curl

update:
	@echo -e "\033[0;33m>>> >>> >>> >>> >>> >>> >>> >>> \033[0;30;46m Update build \033[0m"
	@composer update

server:
	@echo -e "\033[0;33m>>> >>> >>> >>> >>> >>> >>> >>> \033[0;30;46m Run built-in web-server \033[0m"
	@chmod +x ./public_html/webserver.sh
	@./public_html/webserver.sh "localhost" "8888" "./public_html" "./fakedir/fake-index.php" "--some=123 --other=123123 -v"

curl:
	@echo -e "\033[0;33m>>> >>> >>> >>> >>> >>> >>> >>> \033[0;30;46m Check curl \033[0m"
	@curl -S http://localhost:8888
	@curl -S http://localhost:8888/robots.txt
