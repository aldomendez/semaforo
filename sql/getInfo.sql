SELECT *
FROM
(
SELECT ':id' ID, :facility facility, :device device, to_char(:test_dt, 'dd-mon-yyyy hh24:mi') test_dt
FROM :table
WHERE :test_dt > SYSDATE-.05
AND :facility = ':db_id'
ORDER BY :test_dt DESC
)
WHERE ROWNUM =1
