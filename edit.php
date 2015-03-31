<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Semaforo</title>
  <link rel="stylesheet" type="text/css"  href="../jsLib/SemanticUi/1.11.6/semantic.css">
  <link rel="stylesheet" type="text/css"  href="../jsLib/nprogress/1.1.6/nprogress.css">

  <meta name="google-signin-clientid" content="1038555956525-i6lu62ufaniv4hqclfcpk6ig8o9sjbsn.apps.googleusercontent.com" />
  <meta name="google-signin-scope" content="https://www.googleapis.com/auth/plus.login" />
  <meta name="google-signin-requestvisibleactions" content="profile" />
  <meta name="google-signin-cookiepolicy" content="single_host_origin" />
  <meta name="google-signin-callback" content="signinCallback" />
  <script src="https://apis.google.com/js/client:platform.js" async defer></script>
</head>
<body>
  <div class="ui one column grid page" id="container"></div>
  <script id="template" type="text/ractive"><?php include 'edit.template.php' ?></script>
  <script src="../jsLib/jquery/2.1.3/jquery.min.js"></script>
  <script src="../jsLib/nprogress/1.1.6/nprogress.js"></script>
  <!-- // <script src="../jsLib/easing/easing.js"></script> -->
  <!-- // <script src="../jsLib/SemanticUi/1.10.2/components/popup.min.js"></script> -->
  <script src="../jsLib/underscore/1.6.0/underscore.js"></script>
  <script src="../jsLib/ractivejs/0.6.1/ractive.min.js"></script>
  <script src="../jsLib/moment/2.9.0/moment.min.js"></script>
  <script src="../jsLib/mousetrap/1.4.6/mousetrap.min.js"></script>
  <script src="../jsLib/fuse.js/fuse.min.js"></script>
  <script type="text/javascript" src="js/edit.js"></script>
</body>
</html>