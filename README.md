# Symfony-tools

## Auth

### ControllerHelper

Metodos para la autenticación usando los bundle's:

 - "friendsofsymfony/user-bundle": "^2.1"
 - "jms/serializer": "^1.11"
 - "jms/serializer-bundle": "^2.3"
 - "lexik/jwt-authentication-bundle": "^2.4"

### TokenAuthenticator

 - "doctrine/orm": "^2.6"
 - "lexik/jwt-authentication-bundle": "^2.4"
 - "symfony/http-foundation": "^v4.0"
 - "symfony/security": "^v4.0"

services.yaml:

````yaml
    token_authenticator:
        class: \SymfonyTools\Auth\TokenAuthenticator
        arguments: ['@lexik_jwt_authentication.encoder.default', '@doctrine.orm.entity_manager', '%lexik_jwt_authentication.token_ttl%']
````

## Response

### JsonExceptionResponse

 - "symfony/http-foundation": "^v4.0"

services.yaml:

````yaml
    json_exception_response:
        class: SymfonyTools\Response\JsonExceptionResponse
        tags:
            - { name: kernel.event_listener, event: kernel.exception, method: onKernelException, priority: 200}
````

###
###
###
