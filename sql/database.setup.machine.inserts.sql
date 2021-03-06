

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

INSERT INTO semaforo
(id,db_id,name,area,process,dbConnection,dbTable,dbMachine,dbDevice,dbDate,cicleTime)
SELECT 
  (SELECT Max(id)+1 id FROM semaforo) id,
  'CYBOND62',
  'CYBOND62',
  '4x25',
  'SiLens',
  'mxoptix',
  'four_x25_shim_assembly',
  'system_id',
  'serial_num',
  'process_date',
  '1200'
 FROM dual
;
INSERT INTO semaforo
(id,db_id,name,area,process,dbConnection,dbTable,dbMachine,dbDevice,dbDate,cicleTime)
SELECT 
  (SELECT Max(id)+1 id FROM semaforo) id,
  'CYBOND41',
  'CYBOND41',
  '4x25',
  'Shim Attach',
  'mxoptix',
  'four_x25_shim_assembly',
  'system_id',
  'serial_num',
  'process_date',
  '900'
 FROM dual
;

apogee.pkg_ther_sweep@mxappsro


SELECT * FROM dare_mrc.purge_norm@prod
;

SELECT * FROM semaforo ORDER BY id;

SELECT system_id, serial_num, process_date  FROM phase2.pd_assembly@mxoptix;

ALTER TABLE semaforo ADD BU VARCHAR(25);


UPDATE semaforo SET BU = '1' ;



INSERT INTO semaforo
(id,db_id,name,area,process,dbConnection,dbTable,dbMachine,dbDevice,dbDate,cicleTime)
SELECT 
  (SELECT Max(id)+1 id FROM semaforo) id,
  'CYBOND50',
  'CYBOND50',
  'LR4-Shim',
  'Rosa-Shim',
  'mxoptix',
  'lr4_shim_assembly',
  'system_id',
  'serial_num',
  'process_date',
  '1'
 FROM dual
;
  SELECT ROWNUM+(SELECT Max(id)+1 id FROM semaforo) id, a.* FROM (
    SELECT
      DISTINCT testset_id db_id,
      testset_id name,
      'OSA-LIV' area,
      '["LIV"]' process,
      'mxoptix' dbconnection,
      'liv_test_40' dbtable,
      'testset_id' dbmachine,
      'serial_num' dbdevice,
      'test_date' dbdate,
      '350' cicletime
    FROM phase2.liv_test_40@mxoptix WHERE test_date > SYSDATE - 10
    AND testset_id IS NOT NULL
  )a
;


SELECT * FROM semaforo
WHERE db_id IN (
  SELECT DISTINCT system_id 
  FROM
    phase2.lr4_shim_assembly@mxoptix
  WHERE
    step_name LIKE 'ROSA SUBASSEM2%'
  ) ;


UPDATE semaforo 
SET
  cicletime = 720,
  process = 'Rosa-Shim'
WHERE db_id IN (
  SELECT DISTINCT system_id 
  FROM
    phase2.lr4_shim_assembly@mxoptix
  WHERE
    step_name LIKE 'ROSA SUBASSEM2%' AND
    process_date > SYSDATE -10
  ) 
;                                                                                      
SELECT DISTINCT system_id, step_name FROM phase2.lr4_shim_assembly@mxoptix WHERE process_date > SYSDATE - .1 AND step_name LIKE 'LR4 GLA%' 


