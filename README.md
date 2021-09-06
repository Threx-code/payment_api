# payment_api
Laravel (Passport) API for a peer to peer payment app. Please note it's just an in-memory solution without actually implementing an external payment API. The functionalities are: adding users to the app, users depositing money into the app, users sending money to other app users, users checking their balance in the app. 


Endpoints are:
http://localhost:8000/api/register/
http://localhost:8000/api/login/

These routes required authentication [We used Laravel/Passport to manage the token]
add the the token as an Authorization header [Bearer Token ] 
http://localhost:8000/api/deposit
http://localhost:8000/api/transfer
http://localhost:8000/api/balance [This is the only GET request ]
