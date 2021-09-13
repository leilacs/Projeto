
define({ "api": [
  {
    "type": "post",
    "url": "/getAllClientes",
    "title": "getAllClientes",
    "name": "getAllClientes",
    "group": "Clientes",
    "description": "<p>Busca resgistros de clientes.</p>",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>token</p>"
          }
        ]
      }
    },    
    "contentType": "application/json",
    "examples": [
      {
        "title": "Example usage:",
        "content": "curl -i -k -X POST \\\n-H \"Content-Type: application/json\" -H \"Authorization: <token>\" \\\n https://intranet.localhost/api/v1/getAllClientes",
        "type": "curl"
      }
    ],
    "version": "0.0.0",
    "filename": "v1/api.php",
    "groupTitle": "Clientes",
    "sampleRequest": [
      {
        "url": "https://intranet.localhost/api/v1/getAllClientes"
      }
    ]
  }
] });
