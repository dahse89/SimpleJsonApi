<?php
namespace JsonApi\Enums;

abstract class ServerStatus {
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const NOT_FOUND = 404;
    const INTERNAL_SERVER_ERROR = 500;
    const NO_CONTENT = 204;
    const OK = 200;
}