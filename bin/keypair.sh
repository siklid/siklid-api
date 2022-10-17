#!/bin/sh

# Generate private.key and public.key if one of them is missing
if [ ! -f private.key ] || [ ! -f public.key ]; then
    # Get OAUTH_PASSPHRASE from .env
    OAUTH_PASSPHRASE=$(grep OAUTH_PASSPHRASE .env | cut -d '=' -f2)
    # Generate private.key
    openssl genrsa -aes128 -passout pass:"$OAUTH_PASSPHRASE" -out private.key 2048
    # Generate public.key
    openssl rsa -passin pass:"$OAUTH_PASSPHRASE" -pubout -in private.key -out public.key
else
    echo "private.key and public.key already exist"
fi
