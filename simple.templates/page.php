<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Semaforo</title>
  <link rel="stylesheet" type="text/css"  href="../jsLib/SemanticUi/1.11.6/semantic.css">
</head>
  <style>
  .ui.tiny.labels .label, .ui.tiny.label {
    margin-bottom: 3px;
  }
</style>
<body>
  
    <!-- El contenido del template ira aqui -->
    <div class="ui grid" id="container">
      <div class="row">
        <div class="column">
          <div class="ui menu">
            <a href="" class="item"><i class="home icon"></i>Avago</a>
        
          </div>
        </div>
      </div>
      <div class="row">
        <div class="column">
          {% set cardSize = sizeof(bu)  %}
          <div class="ui {{cardSize}} cards">
          
            

          </div>
        </div>
      </div>

    </div>
      
</body>
</html>