SimpleJsonApi
=============

A simple Json API. Methods can be defined in a class and will be available by HTTP POST. This API has full JSON support, uses header authorization and deal with corret server status codes.

A general way to call the API is using CURL:
for example:
```bash
 curl -XPOST -H 'Authorization: test' localhost/JsonApi/api.php -d '{"method":"test","params":[1,2,3]}'
```
A reponse could be:
```json
{
    "success": {
        "status": 200,
        "description": "Standard response for successful HTTP requests",
        "data": 2,
        "request": "{\"method\":\"test\",\"params\":[1,2,3]}",
        "at": "2014-12-23 10:07:02"
    }
}
```
Otherwise there are also detailed error messages:
```json
{
    "error": {
        "status": 404,
        "description": "Not Found: The requested resource could not be found.",
        "message": "Method test2() not exist",
        "request": "{\"method\":\"test2\",\"params\":2}",
        "at": "2014-12-23 09:56:54"
    }
}
```
```json
{
    "error": {
        "status": 400,
        "description": "Bad Request: The request wasn't valid.",
        "message": "Method test() requires min. 2 and accept max. 3 parameter(s), given: 1",
        "request": "{\"method\":\"test\",\"params\":[1]}",
        "at": "2014-12-23 10:12:32"
    }
}
```
