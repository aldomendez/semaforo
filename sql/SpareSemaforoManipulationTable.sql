SELECT * FROM (
SELECT * FROM phase2.PIC_OSA_PD_ASM@mxoptix WHERE process_date > SYSDATE -1
) WHERE ROWNUM = 1;

INSERT INTO semaforo
(id,db_id,name,area,process,dbConnection,dbTable,dbMachine,dbDevice,dbDate,cicleTime,bu)
SELECT
  (SELECT Max(id)+1 id FROM semaforo) id,
  'CYTEST802', -- db_id
  'CYTEST802', -- name
  'SUPERNOVA',  -- area
  'PKG Continuity', -- process
  'mxoptix', --dbConnection
  'PIC_CONTINUITY_TEST', --dbTable
  'system_id', --dbMachine
  'serial_num', --dbDevice
  'process_date', --dbDate
  '120', --cicleTime
  'Gerardo_Martinez' --BU
 FROM dual
;
  SELECT ROWNUM+(SELECT Max(id)+1 id FROM semaforo) id, a.* FROM (
    SELECT
      DISTINCT testset_id db_id,
      testset_id name,
      '4x25 Shim' area,
      'Shim Attach' process,
      'mxoptix' dbconnection,
      'four_x25_shim_assembly' dbtable,
      'system_id' dbmachine,
      'serial_num' dbdevice,
      'process_date' dbdate,
      '350' cicletime
    FROM phase2.four_x25_shim_assembly@mxoptix
    WHERE
      test_date > SYSDATE - 10
    AND testset_id IS NOT NULL
  )a
;

SELECT system_id,step_name FROM phase2.PIC_OSA_PD_ASM@mxoptix WHERE process_date > SYSDATE -10 GROUP BY system_id ,step_name
;

SELECT * FROM semaforo WHERE area = 'LR4-OSA'
;

WHERE db_id IN (
  SELECT DISTINCT system_id
  FROM
    phase2.lr4_shim_assembly@mxoptix
  WHERE
    step_name LIKE 'ROSA SUBASSEM2%'
  ) ;


UPDATE semaforo
SET
  --process='OSA Assembly',
  bu = 'Gerardo_Martinez'
WHERE
  id = 86


;

SELECT * FROM semaforo ORDER BY id desc
;

DELETE FROM semaforo WHERE id = 87;

SELECT dbconnection, dbtable FROM semaforo GROUP BY dbconnection, dbtable
;

SELECT * FROM(
SELECT * FROM dare_mrc.purge_norm@prodmx
) WHERE ROWNUM = 1
;
UPDATE semaforo
SET
  BU = 'Alfredo_Tongo'
WHERE area IN ( '4x25','LR4-OSA','LR4-Shim')
;
UPDATE semaforo
SET
  BU = 'Tomas_Lugo'
WHERE area IN ( 'OSA Pruebas','OSAS','FlexAttach')

;

UPDATE semaforo
SET
  cicletime = 420,
  process = 'OSA-10G'
WHERE db_id IN (
  SELECT DISTINCT system_id
  FROM
    phase2.lr4_shim_assembly@mxoptix
  WHERE
    step_name LIKE 'LR4 OSA%' AND
    process_date > SYSDATE -10
  )
;
SELECT DISTINCT system_id, step_name FROM phase2.los_assembly@mxoptix WHERE process_date > SYSDATE - .1 AND system_id = 'CYBOND70';






DELETE FROM semaforo WHERE id = '83'