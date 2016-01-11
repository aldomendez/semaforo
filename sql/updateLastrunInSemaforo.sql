UPDATE semaforo
SET 
	lastRun  = to_date(':update-date','dd-mon-yyyy hh24:mi')
WHERE id = ':id'
