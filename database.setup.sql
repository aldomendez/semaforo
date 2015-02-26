create tabla semaforo (
    id            number(5) primary key,
    db_id         varchar2(15) not null,
    name          varchar2(15) not null,
    description   varchar2(500),
    area          varchar2(15),
    process       varchar2(500),
    setup_date    date default (sysdate),
    dbConnection  varchar2(30),
    lastTick      date default (sysdate),
    lastRun       date default (sysdate),
    cicleTime     number(9)
);
