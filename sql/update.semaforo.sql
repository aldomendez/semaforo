update semaforo
set
  db_id = ':DB_ID',
  name = ':NAME',
  description = ':DESCRIPTION',
  area = ':AREA',
  process = ':PROCESS',
  dbconnection = ':DBCONNECTION',
  dbtable = ':DBTABLE',
  dbmachine = ':DBMACHINE',
  dbdevice = ':DBDEVICE',
  dbdate = ':DBDATE',
  cicletime = ':CICLETIME',
  bu = ':BU'
where id = ':ID'