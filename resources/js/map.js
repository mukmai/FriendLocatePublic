function initialize() {
  var userLocation = {
    lat: 0,
    lng: 0
  };
  var mapOptions = {
    zoom: 10,
    center: {
      lat: 32.877492,
      lng: -117.235277
    }
  };

  var map = new google.maps.Map(document.getElementById('map'), mapOptions);

  function addMarker(props) {
    var marker = new google.maps.Marker({
      position: props.coords,
      map: map,
      icon: props.icon
    });

    var infowindow = new google.maps.InfoWindow({
      content: props.info
    });

    google.maps.event.addListener(marker, 'click', function initialize() {
      infowindow.close(); //hide the infowindow
      infowindow.open(map, marker);
    });
  }

  // Write user location to firebase and map
  function writeLocation(position) {
    userLocation = {
      lat: position.coords.latitude,
      lng: position.coords.longitude
    };
    addMarker({
      coords: userLocation,
      info: "<img src=" + profilePic + " />",
      info: "<img src=" + profilePic + " /> <p>" + profileName + "</p>",
      icon: profileIcon
    });
    if (id != "") {
      firebase.database().ref('users/' + id).set({
        name: profileName,
        location: userLocation,
        picture: profilePic,
        icon: profileIcon
      });
    }
  }

  // Get user current location
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(writeLocation);
  } else {
    alert('Oops! No geolocation support!');
  }

  // Get friends data
  for (var i = 0; i < fdlist.length; i++) {
    var fd = fdlist[i];
    console.log(fd);
    firebase.database().ref('users/' + fd.id).once('value').then(function(snapshot) {
      addMarker({
        coords: snapshot.val().location,
        info: "<img src=" + snapshot.val().picture + " /> <p>" + snapshot.val().name + "</p>",
        icon: snapshot.val().icon
      });
    });
  };

  // add marker to map by clicking on map
  // google.maps.event.addListener(map, 'click', function(event) {
  //   var infoContent = event.latLng.lat().toString() + '<br>' + event.latLng.lng().toString();
  //   addMarker({
  //     coords: event.latLng,
  //     info: infoContent
  //   });
  // });
}

google.maps.event.addDomListener(window, 'load', initialize);
