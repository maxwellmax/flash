# Flash

### Rodando o Projeto (via docker)
 * Symfony 5
 * Mysql
 * PHP 7
 * CQRS
 * Hexagonal
```
$ ./bin/runenv.sh
```
### Importar o DB
```
$ docker exec -i flash_db_1 sh -c 'exec mysql -uroot -p"$MYSQL_ROOT_PASSWORD"' < ./environment/mysql/sql/CreateDatabase.sql 
```

### Composer

```
$ composer install
```

### URL do projeto
```
http://localhost:81
```

#Arquitetura

### src/Application
* Fica parte dos controladores
 
### src/Domain
* Fica toda a parte de regra de negocios da Api
* Entity, Repository, Command  

### Infrastructure

### Proposta de melhoria na arquitetura
* Testes de integração
* Testes unitários
* Swagger para Documentação da API

### Rotas
 * na pasta config/Flash.postman_collection.json esta uma collection do Postman com as rotas para importar na API