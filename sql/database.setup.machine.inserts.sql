

INSERT INTO semaforo
(id,db_id,name,area,process,dbConnection,dbTable,dbMachine,dbDevice,dbDate,cicleTime)
SELECT ROWNUM+(SELECT Max(id)+1 id, a.* FROM (
  SELECT
    DISTINCT system_id db_id,
    system_id name,
    'LR4-Shim' area,
    '["Tosa-Osa"]' process,
    'mxoptix' dbConnection,
    'lr4_shim_assembly' dbTable,
    'system_id' dbMachine,
    'serial_num' dbDevice,
    'process_date' dbDate,
    '350' cicleTime
  FROM phase2.Lr4_shim_assembly@mxoptix WHERE process_date > SYSDATE - .1
  AND system_id IS NOT NULL
)a
;
INSERT INTO semaforo
(id,db_id,name,area,process,dbConnection,dbTable,dbMachine,dbDevice,dbDate,cicleTime)
  SELECT ROWNUM+(SELECT Max(id)+1 id, a.* FROM (
    SELECT
      DISTINCT system_id db_id,
      system_id name,
      'OSAS' area,
      '["Ensamble"]' process,
      'mxoptix' dbConnection,
      'los_assembly' dbTable,
      'system_id' dbMachine,
      'serial_num' dbDevice,
      'process_date' dbDate,
      '350' cicleTime
    FROM phase2.los_assembly@mxoptix WHERE process_date > SYSDATE - 1
    AND system_id IS NOT NULL
  )a
;

INSERT INTO semaforo
(id,db_id,name,area,process,dbConnection,dbTable,dbMachine,dbDevice,dbDate,cicleTime)
  SELECT ROWNUM+(SELECT Max(id)+1 id, a.* FROM (
    SELECT
      DISTINCT system_id db_id,
      system_id name,
      'OSAS-PD' area,
      '["pd_assembly"]' process,
      'mxoptix' dbConnection,
      'los_assembly' dbTable,
      'system_id' dbMachine,
      'serial_num' dbDevice,
      'process_date' dbDate,
      '350' cicleTime
    FROM phase2.pd_assembly@mxoptix WHERE process_date > SYSDATE - 1
    AND system_id IS NOT NULL
  )a
;

INSERT INTO semaforo
(id,db_id,name,area,process,dbConnection,dbTable,dbMachine,dbDevice,dbDate,cicleTime)
  SELECT ROWNUM+(SELECT Max(id)+1 id FROM semaforo) id, a.* FROM (
    SELECT
      DISTINCT system_id db_id,
      system_id name,
      'OSA-Functional' area,
      '["Functional"]' process,
      'mxoptix' dbConnection,
      'osa_functional_test_25g' dbTable,
      'system_id' dbMachine,
      'serial_num' dbDevice,
      'process_date' dbDate,
      '350' cicleTime
    FROM phase2.osa_functional_test_25g@mxoptix WHERE process_date > SYSDATE - 100
    AND system_id IS NOT NULL
  )a
;

INSERT INTO semaforo
(id,db_id,name,area,process,dbConnection,dbTable,dbMachine,dbDevice,dbDate,cicleTime)
  SELECT ROWNUM+(SELECT Max(id)+1 id FROM semaforo) id, a.* FROM (
    SELECT
      DISTINCT system_id db_id,
      system_id name,
      'OSA-LIV' area,
      '["LIV"]' process,
      'mxoptix' dbConnection,
      'liv_test_35' dbTable,
      'system_id' dbMachine,
      'serial_num' dbDevice,
      'process_date' dbDate,
      '350' cicleTime
    FROM phase2.liv_test_35@mxoptix WHERE process_date > SYSDATE - 100
    AND system_id IS NOT NULL
  )a
;



SELECT * FROM dare_mrc.purge_norm@prod
;

SELECT * FROM semaforo ORDER BY id;

SELECT system_id, serial_num, process_date  FROM phase2.pd_assembly@mxoptix;

