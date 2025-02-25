#!make

.PHONY: start_docker start_docker_with_build start_gitkraken
.DEFAULT_GOAL= help

help: 
	@echo "\033[33mUsage:\033[0m\n  make [target] [arg=\"val\"...]\n\n\033[33mTargets:\033[0m"
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' Makefile| sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[32m%-15s\033[0m %s\n", $$1, $$2}'


start: # Start codeigniter docker
	docker compose -f docker-compose.yml up -d --remove-orphans

build: # Start codeigniter docker
	docker compose build && docker compose -f docker-compose.yml up -d --remove-orphans

thomas:
	docker compose up -d --build

start_gitkraken: # Start WSL gitkraken
	gitkraken &

