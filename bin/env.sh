#!/usr/bin/env sh

# This script is used to set up the environment for different environments.

info () {
    printf "\033[0;34m[Siklid:info 🐟] %s\033[0m" "$1"
}

success () {
    printf "\033[0;32m[Siklid:success 🐟] %s\033[0m" "$1"
}

warn () {
    printf "\033[0;33m[Siklid:warn 🐟] %s\033[0m" "$1"
}

error () {
    printf "\033[0;31m[Siklid:error 🐟] %s\033[0m" "$1"
}

br() {
    printf "\n"
}


info "Setting up environment variables for development..." && br

## Copy the .env.example file to .env if it doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env
    success "Copied .env.example to .env" && br
else
    warn "Found .env file" && br
fi

success "Environment set up successfully 🚀" && br
