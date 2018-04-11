<?php
session_start();
require_once __DIR__ . '/src/Facebook/autoload.php'; // download official fb sdk for php @ https://github.com/facebook/php-graph-sdk
$fb = new Facebook\Facebook([
  'app_id' => '---',
  'app_secret' => '---',
  'default_graph_version' => 'v2.11',
  ]);
$helper = $fb->getRedirectLoginHelper();
$permissions = ['user_friends'];

try {
  if (isset($_SESSION['facebook_access_token'])) {
    $accessToken = $_SESSION['facebook_access_token'];
  } else {
      $accessToken = $helper->getAccessToken();
  }
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (isset($accessToken)) {
  if (isset($_SESSION['facebook_access_token'])) {
    $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
  } else {
    // getting short-lived access token
    $_SESSION['facebook_access_token'] = (string) $accessToken;
    // OAuth 2.0 client handler
    $oAuth2Client = $fb->getOAuth2Client();
    // Exchanges a short-lived access token for a long-lived one
    $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
    $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
    // setting default access token to be used in script
    $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
  }
  // redirect the user back to the same page if it has "code" GET variable
  if (isset($_GET['code'])) {
    header('Location: ./');
  }
  // getting basic info about user
  try {
    $profile_request = $fb->get('/me?fields=name');
    $profile = $profile_request->getGraphNode()->asArray();
    $picture_request = $fb->get('/me/picture?redirect=false&type=large');
    $picture = $picture_request->getGraphUser();
    $icon_request = $fb->get('/me/picture?redirect=false&height=40&width=40');
    $icon = $icon_request->getGraphUser();
    $fdlist_request = $fb->get('/me?fields=friends');
    $fdlist = $fdlist_request->getGraphNode()->asArray();
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    session_destroy();
    // redirecting user back to app login page
    header("Location: ./");
    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }
} else {

  $loginUrl = $helper->getLoginUrl('https://friendlocate.herokuapp.com/', $permissions);
  // https://facebook.com/profile.php?id=<UID>
  echo '<div id="login" class="modalDialog">
  	<div>
  		<p>FriendLocate requires your permission of accessing data from Facebook</p>
  		<a href="' . $loginUrl . '">
        <button class="loginBtn loginBtn--facebook">
          Log in with Facebook
        </button>
      </a>
  	</div>
  </div>';
}
?>
