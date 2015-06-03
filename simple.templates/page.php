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
    margin-bottom: 6px;
  }
  .titleRed {
    background-color: rgb(170,38,47);
    font-size: 22px !important;
  }
  .titleBlack {
    background-color: rgb(39,40,34);
    color: white;
    font-size: 22px !important;
  }
</style>
<body>
  <div class="ui tall stacked padded grid segment" id="container">
    <div class="ui grid">
      <column class='twelve wide column titleRed'>&nbsp;</column>
      <column class='four wide column titleBlack'>Go Beyond The Standards</column>
    </div>
    <div class="ui hidden divider"></div>
    <div class="row">
      <div class="column">
        {% set cardsNum = bu|length  %}
        <div class="ui {{size[bu|length]}} cards">
          {% for manager, areas in bu %}
          <!-- <div class="card"> -->
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
          <!-- </div> -->
          {% endfor %}
        </div>
      </div>
    </div>
  </div>
</body>
</html>