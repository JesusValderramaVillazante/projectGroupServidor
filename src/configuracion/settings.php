<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/'
        ],
        
        'app_token_name' => 'APP-TOKEN',
        
        '$connectionString' => [
            'dns' => 'mysql:host=localhost;port=8889;dbname=proyecto',
            'user' => 'root',
            'pass' => 'root'
        ],

        'rutaUpload' => $_SERVER['DOCUMENT_ROOT'].'/uploads/'
    ],
];
