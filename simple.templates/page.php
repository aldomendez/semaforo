<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Semaforo</title>
  <link rel="stylesheet" type="text/css"  href="http://cymautocert/osaapp/jsLib/SemanticUi/1.11.6/semantic.css">
</head>
  <style>
  .ui.big.labels .label, .ui.big.label {
    margin-bottom: 5px;
  }
</style>
<body>
  <div class="ui grid page" id="container">
    <div class="row">
      <div class="column">
        <div class="ui menu">
          <a href="http://cymautocert/osaapp/semaforo-dev/simple.php" class="item"><i class="home icon"></i>Avago</a>
      
        </div>
      </div>
    </div>
    <div class="row">
      <div class="column">
        {% set cardsNum = bu|length  %}
        <div class="ui {{size[bu|length]}} cards">
          {% for manager, areas in bu %}
          <div class="card">
            <div class="content">
              <h1 class="header"><a href="simple.php/{{manager}}"><small>Equipos de </small>{{manager}}</a></h1>
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
                              <div class="label horizontal big ui {{machine.STATUS}}">
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