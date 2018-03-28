<?php
require_once 'vendor/autoload.php';
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

session_start();
use CPNVES\Auth\Client\Client;

$clientId = $_ENV["CLIENT_ID"]; //Load your CLIENT_ID from somewhere, here it will be taken from the $_ENV
$clientSecret = $_ENV["CLIENT_SECRET"]; //Load your CLIENT_SECRET from somewhere, here it will be taken from the $_ENV
$redirectUri = "http://localhost:8080/"; //must match exactly (even trailing slash!) of your clients redirect url
$provider = new Client($clientId, $clientSecret,$redirectUri);

if(isset($_GET["logout"])){
    $_SESSION["TOKEN"] = null;
    header("Location: $redirectUri");
    exit;
}

if(isset($_GET["error"])){
    echo "error <br>";
    echo $_GET["error"];
    echo "<br>";
    echo $_GET["message"];
    echo "<br>";
    echo $_GET["hint"];
    exit;
}
// If we don't have an authorization code then get one
if (!isset($_GET['code'])) {

    // Fetch the authorization URL from the provider; this returns the
    // urlAuthorize option and generates and applies any necessary parameters
    // (e.g. state).
    $options = [
        "scope"=>["intranet.something","intranet.something-else"]
    ];
    $authorizationUrl = $provider->getAuthorizationUrl($options);

    // Get the state generated for you and store it to the session.
    $_SESSION['oauth2state'] = $provider->getState();

    // Redirect the user to the authorization URL.
    header('Location: ' . $authorizationUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {

    if (isset($_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
    }

    exit('Invalid state');
} elseif(isset($_SESSION["TOKEN"])) {
    echo "Token:";
    echo "<br>";
    echo $_SESSION["TOKEN"];
    $token = new League\OAuth2\Client\Token\AccessToken(['access_token' => $_SESSION["TOKEN"]]);
    echo "<br>";
    echo print_r($provider->getResourceOwner($token)->toArray(),true);
    echo "<br>";
    echo "<a href='/?logout'>Logout</a>";
} else {

    try {

        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.
        echo 'Access Token: ' . $accessToken->getToken() . "<br>";
        echo 'Refresh Token: ' . $accessToken->getRefreshToken() . "<br>";
        echo 'Expired in: ' . $accessToken->getExpires() . "<br>";
        echo 'Already expired? ' . ($accessToken->hasExpired() ? 'expired' : 'not expired') . "<br>";
            $_SESSION["TOKEN"] = $accessToken->getToken();

        header("Location: http://localhost:8080");
            // Using the access token, we may look up details about the
        // resource owner.
        $resourceOwner = $provider->getResourceOwner($accessToken);

        var_export($resourceOwner->toArray());

        // The provider provides a way to get an authenticated API request for
        // the service, using the access token; it returns an object conforming
        // to Psr\Http\Message\RequestInterface.
        $request = $provider->getAuthenticatedRequest(
            'GET',
            'http://brentertainment.com/oauth2/lockdin/resource',
            $accessToken
        );

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        exit($e->getMessage());

    }

}
