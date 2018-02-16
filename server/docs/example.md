A students create a simple php app for that get's the calendar of the user

- First of login
    - login to the authentication server
    - redirect back to authoization server with a token in url
    - get user identity
- then create a client
- define an audience for this client
on your app
- a remote user logs wants to log in to the app

on authorization server
- use client to forge request to authorization server
    - request scope = "intranet:calendar"
- redirect user to authorization server 
- check the user credentials
    - login to the authentication server
    - redirect back to authoization server with a token in url
    - get user_id
    - now that we have user id, ask for scopes
    - if ok
        - redirect back to app with code = access code
    - if not
        - redirect back to app with error in code
        
On your app
- the app can now decode the authorization code
- ask the auth server for an access token
- store access token and refresh token for this user

On the ressource part
- now if app requests /intranet/me/calendar
- request /intranet/me/calendar with access token in headers
    Authorization: Baerer ....toke
- now intranet can get the token, verify it's signature
- after verifying signature
- decode access token
    - scope -> scopes authorized for this access token in string seperated by spaces
    - aud -> your api url/ressource
    - iss -> issuer, which is oauth2 server
    - exp -> experation
- check audience (can be defined in config) (basicly just check if request hostname is in audience)
- check scopes (must be defined in code)
    
    
https://auth0.com/docs/tokens/access-token
https://auth0.com/docs/api-auth/tutorials/verify-access-token
https://auth0.com/docs/architecture-scenarios/application/server-api/api-implementation-nodejs#check-the-client-permissions
https://auth0.com/docs/scopes/current
https://auth0.com/docs/api-auth/tutorials/adoption/scope-custom-claims

https://www.oauth.com/oauth2-servers/oauth2-clients/single-page-apps/
https://oauth.net/articles/authentication/