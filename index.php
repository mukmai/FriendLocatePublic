<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>FriendLocate</title>

  <!-- CSS Style -->
  <link rel="stylesheet" type="text/css" href="resources/css/reset.min.css">
  <link rel="stylesheet" type="text/css" href="resources/css/style.css">

  <!-- Google Map -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCAfeEIFkucYjHA9rtYHx7MQobNOhs10Cc"></script>
  <script src="resources/js/map.js"></script>
</head>
<body>

  <!-- FireBase initialize -->
  <script src="https://www.gstatic.com/firebasejs/4.8.1/firebase.js"></script>
  <script src="resources/js/initfirebase.js"></script>

  <!-- Log in Facebook -->
  <?php include("fb.php"); ?>

  <!-- Access friend list and get picture -->
  <?php include("friend.php"); ?>

  <!-- Write id and location to firebase -->
  <script type="text/javascript">
    var profileName = "<?php echo $profile['name'] ?>";
    var id = "<?php echo $profile['id'] ?>";
    var fdlist = <?php echo json_encode($fdlist['friends']) ?>;
    var profilePic = "<?php echo $picture['url'] ?>";
    var profileIcon = "<?php echo $icon['url'] ?>";
  </script>

  <!-- Info Window format -->
  <script src="resources/js/info.js"> </script>

  <div id="map"></div>

</body>
</html>
