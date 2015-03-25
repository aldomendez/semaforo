UPDATE semaforo
SET 
	lastTick = to_date(':test_dt','dd-mon-yyyy hh24:mi'),
	lastRun  = to_date(':update-date','dd-mon-yyyy hh24:mi')
WHERE db_id = ':db_id'
