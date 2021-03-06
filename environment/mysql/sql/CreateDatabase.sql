CREATE DATABASE gamora;

USE gamora;

-- auto-generated definition
create table TransferStatus
(
    id     int auto_increment
        primary key,
    status varchar(30) not null
);

INSERT INTO gamora.TransferStatus (id, status) VALUES (1, 'Autorizado');
INSERT INTO gamora.TransferStatus (id, status) VALUES (2, 'Enviado');
INSERT INTO gamora.TransferStatus (id, status) VALUES (3, 'Finalizado');
INSERT INTO gamora.TransferStatus (id, status) VALUES (4, 'Estornado');

-- auto-generated definition
create table TransferType
(
    id          int auto_increment
        primary key,
    description varchar(100) null
);

INSERT INTO gamora.TransferType (id, description) VALUES (1, 'transferência');
INSERT INTO gamora.TransferType (id, description) VALUES (2, 'dinheiro');

-- auto-generated definition
create table UserType
(
    id   int auto_increment
        primary key,
    type varchar(100) not null
);

INSERT INTO gamora.UserType (id, type) VALUES (1, 'Comum');
INSERT INTO gamora.UserType (id, type) VALUES (2, 'Lojista');

-- auto-generated definition
create table Wallet
(
    id         int auto_increment
        primary key,
    balance    double(5, 2)                        not null,
    updated_at timestamp default CURRENT_TIMESTAMP not null,
    created_at timestamp default CURRENT_TIMESTAMP not null
);

create table User
(
    id           int auto_increment
        primary key,
    uuid         char(36)                            not null,
    name         varchar(255)                        not null,
    id_user_type int                                 not null,
    id_wallet    int                                 not null,
    cpf_cnpj     varchar(16)                         not null,
    password     varchar(100)                        not null,
    updated_at   timestamp default CURRENT_TIMESTAMP not null,
    created_at   timestamp default CURRENT_TIMESTAMP not null,
    email        varchar(255)                        not null,
    constraint User_cpf_cnpj_uindex
        unique (cpf_cnpj),
    constraint User_email_uindex
        unique (email),
    constraint User_UserType_uuid_fk
        foreign key (id_user_type) references UserType (id),
    constraint User_Wallet_id_fk
        foreign key (id_wallet) references Wallet (id)
);

-- auto-generated definition
create table Transfer
(
    id                 int auto_increment
        primary key,
    uuid               char(36)                            not null,
    payer              int                                 not null,
    payee              int                                 not null,
    id_transfer_status int                                 not null,
    value              decimal(5, 2)                       not null,
    created_at         timestamp default CURRENT_TIMESTAMP not null,
    updated_at         timestamp default CURRENT_TIMESTAMP not null,
    constraint Transfer_TransferStatus_uuid_fk
        foreign key (id_transfer_status) references TransferStatus (id),
    constraint Transfer_User_id_fk
        foreign key (payer) references User (id),
    constraint Transfer_User_id_fk_2
        foreign key (payee) references User (id)
);