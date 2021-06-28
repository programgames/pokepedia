CONSOLE=php bin/console --env=prod

NC=033[0m
APP_ENV=dev

.PHONY: full-install
full-install:
full-install:
	@echo "\${BLUE}- Installing pokepedia mapper\${NC}"
	$(CONSOLE) make:migration
	$(CONSOLE) doctrine:migration:migrate
	$(CONSOLE) app:install
