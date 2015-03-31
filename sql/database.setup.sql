create table semaforo (
    id            number(5) primary key,
    db_id         varchar2(25) not null,
    name          varchar2(25) not null,
    description   varchar2(500),
    area          varchar2(15),
    process       varchar2(500),
    setup_date    date default (sysdate),
    dbConnection  varchar2(30),
    dbTable       VARCHAR2(25),
    dbMachine     VARCHAR2(15),
    dbDevice      VARCHAR2(15),
    dbDate        VARCHAR2(15),
    lastTick      date default (sysdate),
    lastRun       date default (sysdate),
    cicleTime     number(9),
    bu            varchar2(25)
);

DROP TABLE semaforo;

