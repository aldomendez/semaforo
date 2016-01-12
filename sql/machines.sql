SELECT id,
       db_id,
       name,
       description,
       area,
       process,
       to_char(setup_date, 'dd-mon-yyyy hh24:mi') setup_date,
       dbconnection,
       dbtable,
       dbmachine,
       dbdevice,
       to_char(lasttick, 'dd-mon-yyyy hh24:mi') lasttick,
       to_char(lastrun, 'dd-mon-yyyy hh24:mi') lastrun,
       cicletime,
       bu,
       sysdate
FROM semaforo a
WHERE lasttick = (SELECT Max(lasttick) FROM semaforo 
  WHERE db_id=a.db_id)
and active = 1
