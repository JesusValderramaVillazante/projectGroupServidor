<?php
namespace Src\Middleware;

use Exception;
use Src\Lib\Auth;

class AuthMiddleware
{
    private $app = null;

    public function __construct($app){
        $this->app = $app;
    }

    public function __invoke($request, $response, $next){
        $c = $this->app->getContainer();
        //APP-TOKEN
        $app_token_name = $c->settings['app_token_name'];

        $token = $request->getHeader($app_token_name);

        if (isset($token[0])) {
            $token = $token[0];
        }

        try {
            Auth::Check($token);  
            $request = $request->withAttribute("id", Auth::GetData($token)->id);
        } catch (Exception $e) {
            return $response->withStatus(401)->write('Unauthorized');
        }

        
        return $next($request, $response);

    }
}
