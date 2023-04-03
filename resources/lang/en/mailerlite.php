<?php

return [
    'messages' => [
        '1' => 'API key is required and is not provided.',
        '2' => 'The endpoint you are trying to use is non-existing.',
        '302' => 'You are trying to use an invalid API key to authorize.',
        '429' => 'You reached the API rate limit.',
        '200' => 'The request succeeded.',
        '201' => 'The request was fulfilled and resulted in a new resource being created.',
        '204' => 'The server fulfilled the request but does not need to return an entity-body, i.e. when resource is deleted.',
        '400' => 'The request could not be understood by the server due to malformed syntax.',
        '401' => 'The request requires user authentication.',
        '404' => 'The server has not found anything matching the Request-URI. No indication is given of whether the condition is temporary or permanent.',
        '500' => 'The server encountered an unexpected condition which prevented it from fulfilling the request.',
    ]
];
