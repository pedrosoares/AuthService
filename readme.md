## Router File

```
[
  {
    "domain": "http://localhost:8080",
    "uri": "/auth/users",
    "whitelist": [
      "127.0.0.1"
    ],
    "method": "post",
    "permission": []
  },{
    "domain": "http://localhost:8080",
    "uri": "/auth/register",
    "method": "post",
    "permission": []
  },{
    "domain": "http://localhost:8080",
    "uri": "/auth/login",
    "method": "post",
    "permission": []
  },{
    "domain": "http://localhost:8080",
    "uri": "/auth/logout",
    "method": "post",
    "permission": []
  },{
    "domain": "http://localhost:8080",
    "uri": "/auth/refresh",
    "method": "post",
    "permission": []
  },{
    "domain": "http://localhost:8080",
    "uri": "/auth/me",
    "method": "post",
    "permission": []
  },{
    "domain": "http://localhost:8080",
    "uri": "/auth/can",
    "method": "post",
    "permission": []
  },{
    "domain": "http://localhost:8080",
    "uri": "/help",
    "method": "get",
    "permission": []
  },{
    "domain": "http://localhost:8080",
    "uri": "/home/{id}",
    "method": "post",
    "permission": [
      "home"
    ]
  }
]

```