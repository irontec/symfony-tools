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

## Request

### JsonRequestTransformer

services.yaml:

````yaml
    json_request_transformer:
        class: \SymfonyTools\Request\JsonRequestTransformer
        tags:
            - { name: kernel.event_listener, event: kernel.request, method: onKernelRequest, priority: 100 }
````

### Request

## Services

### Setter

Clase que ayuda con a limpiar y asignar variables a la entidad antes de enviarla a MySQL

services.yaml:

````yaml
    SymfonyTools\Services\Setter:
        public: true
````

### DoctrineCommon

### GetEntities

services.yaml:

````yaml
parameters:
    entityNamespace: 'App'

services:
    SymfonyTools\Services\GetEntities:
        public: true
        arguments: ["@doctrine", '%entityNamespace%']
````

### CorsListener

services.yaml:

````yaml
parameters:
    cors_origins:
      - http://example.com
      - http://10.10.0.227:4200
      - http://localhost:4200

services:
    SymfonyTools\Services\CorsListener:
      arguments: ["%cors_origins%"]
      tags:
        - {name: kernel.event_listener, event: kernel.response,   method: onKernelResponse}
        - {name: kernel.event_listener, event: kernel.request,    method: onKernelRequest}
````

## Normalizer

### CircularReference

services.yaml:

````yaml
    SymfonyTools\Normalizer\CircularReference:
        parent: 'serializer.normalizer.object'
        public: false
        tags:
            - { name: serializer.normalizer, priority: -925}
````

## Entity

### CreateTrait

### UpdateTrait


