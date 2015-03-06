<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Semaforo</title>
  <link rel="stylesheet" type="text/css"  href="../jsLib/SemanticUi/1.10.2/semantic.css">
</head>
<body>
  <div class="ui grid" id="container"></div>
  
  <script src="coffee/template.search.html" type="text/template" id="search_template">
<div class="row">
  <div class="column">
    <label for="search">Search</label>
    <input type="text" id="search_input">
    <input type="button" value="Search" id="search_button">
  </div>
</div>
  </script>
  <script src="../jsLib/jquery/2.1.3/jquery.min.js"></script>
  <script src="../jsLib/easing/easing.js"></script>
  <script src="../jsLib/SemanticUi/1.10.2/components/popup.min.js"></script>
  <script src="../jsLib/underscore/1.6.0/underscore.js"></script>
  <script src="../jsLib/backbone/1.1.2/backbone.js"></script>
  <script src="../jsLib/moment/2.9.0/moment.min.js"></script>
  <script src="../jsLib/fuse.js/fuse.min.js"></script>
  <script type="text/javascript" src="js/home.js"></script>
</body>
</html>