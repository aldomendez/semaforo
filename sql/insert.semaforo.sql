INSERT INTO semaforo_test
(id,db_id,name,area,process,dbConnection,dbTable,dbMachine,dbDevice,dbDate,cicleTime)
SELECT 
  (SELECT Max(id)+1 id FROM semaforo) id,
  ':db_id',
  ':name',
  ':area',
  ':process',
  ':dbConnection',
  ':dbTable',
  ':dbMachine',
  ':dbDevice',
  ':dbDate',
  ':cicleTime'
 FROM dual
;