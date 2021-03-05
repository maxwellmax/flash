# Flash

### Rodando o Projeto (via docker) Symfony 5
 * Symfony 5
 * Mysql
 * PHP 7
 * CQRS
 * Hexagonal
```
$ ./bin/runenv.sh
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