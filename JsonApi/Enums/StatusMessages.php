<?php
namespace JsonApi\Enums;

abstract class StatusMessages {
    public static function fromServerStatus($statuscode){
        switch($statuscode){
            case ServerStatus::BAD_REQUEST:
                return "Bad Request: The request wasn't valid.";
            case ServerStatus::UNAUTHORIZED:
                return "Unauthorized: Authentication missing or invalid.";
            case ServerStatus::NOT_FOUND:
                return "Not Found: The requested resource could not be found.";
            case ServerStatus::INTERNAL_SERVER_ERROR:
                return "Internal Server Error: Something went wrong on the server";
            case ServerStatus::NO_CONTENT:
                return "The server successfully processed the request, but is not returning any content";
            case ServerStatus::OK:
                return "Standard response for successful HTTP requests";
        }
    }

    public static function headerConform($statuscode){
        switch($statuscode){
            case ServerStatus::BAD_REQUEST: $text = 'Bad Request'; break;
            case ServerStatus::UNAUTHORIZED:$text = 'Unauthorized'; break;
            case ServerStatus::NOT_FOUND:   $text = 'Not Found'; break;
            case ServerStatus::NO_CONTENT:$text = 'No Content'; break;
            case ServerStatus::OK:$text = 'OK';
            default: $text = 'Internal Server Error'; break;
        }
        $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ?
                      $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0'
                    );
        return $protocol . ' ' . $statuscode . ' ' . $text;
    }
}