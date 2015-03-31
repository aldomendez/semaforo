INSERT INTO semaforo
(id,db_id,name,description,area,process,dbConnection,dbTable,dbMachine,dbDevice,dbDate,cicleTime,bu)
SELECT 
  (SELECT Max(id)+1 id FROM semaforo) id,
  ':DB_ID',
  ':NAME',
  ':DESCRIPTION',
  ':AREA',
  ':PROCESS',
  ':DBCONNECTION',
  ':DBTABLE',
  ':DBMACHINE',
  ':DBDEVICE',
  ':DBDATE',
  ':CICLETIME',
  ':BU'
 FROM dual