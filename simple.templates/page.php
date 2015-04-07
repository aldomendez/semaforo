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
          {% set cardsNum = bu|length  %}
          <div class="ui {{size[cardsNum]}} cards">
            {% for manager, areas in bu %}
            <div class="card">
              <div class="content">
                <div class="header"><small>Equipos de </small>{{manager}}</div>
                <div class="ui list">
                  {% for areaName, procesos in areas %}
                    <div class="item">
                      <div class="content"><b>{{areaName}}</b></div>
                      <div class="description">
                        <ui class="list">
                          {% for processName, machines in procesos %}
                          <div class="item">
                            <div class="content">{{processName}}</div>
                            <div class="description">
                              {% for machineName, machine in machines %}
                                <div class="label horizontal tiny ui {{machine.STATUS}}">
                                  {{machine.NAME}}
                                  {% if machine.STATUS == 'red' %}
                                    <div class="detail"><i class="icon warning sign"></i>{{machine.DIFF}}</div>
                                  {% endif %}
                                </div>
                              {% endfor %}
                            </div>
                          </div>
                          {% endfor %}
                        </ui>
                      </div>
                    </div>
                  {% endfor %}
                </div>
              </div>
            </div>
            {% endfor %}
          </div>
        </div>
      </div>

    </div>
      
</body>
</html>