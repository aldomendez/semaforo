Semaforo
==========

Datos de las maquinas que tendre en los semaforos:

|Campo|tipo|Descripcion|
|-----|----|-----------|
|id|number|Id de la maquina en la base de datos|
|db_id|string|Nombre del equipo en la base de datos|
|name|string|Como sera desplegada al usuario|
|description|string|descripcion del equipo|
|process|array|Nombre de los procesos/productos que se procesan en el equipo|
|setup_date|dateString|fecha en la que se dio de alta el equipo|
|lastTick|dateString| Ultima vez que se vio una pieza de este equipo|
|lastRun|dateString|Fecha en la que se ejecuto la ultima actualizacion|
|area|string|Area a la que pertenece el equipo|
|cicleTime|number|Tiempo en que termina de hacer una pieza [en segundos]|
|dbConnection|string|Conexion a la base de datos|
|dbtable|string|Nombre de la tabla donde puedo consultar la ultima pieza|
|dbFacility|string|nombre del campo que tiene el nombre de la maquina|
|dbDevice|string|Nombre del campo donde se encuentra cual fue la ultima pieza que se proceso|
|dbDate|string|Nombre del campo que tiene la fecha en la que se proceso|
|dbMachine|string|Nombre del campo donde se encuentra el nombre de la maquina|
