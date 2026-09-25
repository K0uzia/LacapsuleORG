PORT ?= 8080
LOCAL ?= bash setup-local.sh

.PHONY: help info deps init reset seed dev db-shell open

help: ## Affiche l'aide
	@awk 'BEGIN {FS = ":.*##"; printf "\nTargets:\n\n"} /^[a-zA-Z0-9_.-]+:.*##/ { printf "  \033[36m%-14s\033[0m %s\n", $$1, $$2 }' $(MAKEFILE_LIST)
	@echo

info: ## Versions & chemins
	$(LOCAL) info

deps: ## Installe extensions PHP (apt/dnf)
	$(LOCAL) deps

init: ## Crée storage + SQLite + seed
	$(LOCAL) init

reset: ## Recrée la DB SQLite (destructif)
	$(LOCAL) reset

seed: ## Réapplique le seed local
	$(LOCAL) seed

dev: ## Serveur PHP (docroot public/)
	PORT=$(PORT) $(LOCAL) dev

db-shell: ## Shell sqlite
	$(LOCAL) db.shell

open: ## Ouvre le site local
	xdg-open http://localhost:$(PORT) || true
