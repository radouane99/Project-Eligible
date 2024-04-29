<?php
namespace Radouane\Eligibility;
class Router{
    private static $routes = [];
                public static function handle ($method = 'GET', $path='/', $filename ='',) 
                {

                    $currentMethod = $_SERVER['REQUEST_METHOD'];
                    $currentUri = $_SERVER['REQUEST_URI'];
                    if($currentMethod != $method) {
                        return false;
                    }
                    $root = '';
                    $pattern = '#^' . $root.$path. '$#siD';

                    if (preg_match($pattern, $currentUri)){
                        if(is_callable($filename)){
                            $filename();
                        }else {
                            require_once $filename;
                        }
                        exit();
                    }
                        return false;
                }
                public static function get ($path='/', $filename ='') 
                {
                    return self::handle('GET',$path,$filename);
                }
                public static function post ($path='/', $filename ='') 
                {
                    return self::handle('POST',$path,$filename);
                }
                public static function put ($path='/', $filename ='') 
                {
                    return self::handle('PUT',$path,$filename);
                }
                public static function patch ($path='/', $filename ='') 
                {
                    return self::handle('PATCH',$path,$filename);
                }
                public static function delete ($path='/', $filename ='') 
                {
                    return self::handle('DELETE',$path,$filename);
                }
                public static function dispatch() {
                    $method = $_SERVER['REQUEST_METHOD'];
                    $path = $_SERVER['REQUEST_URI'];
            
                    if (isset(self::$routes[$method][$path])) {
                        $handler = self::$routes[$method][$path];
                        if (is_callable($handler)) {
                            $handler();
                        } else {
                            require_once $handler;
                        }
                    } else {
                        http_response_code(404);
                        echo "404 Not Found";
                    }
                }
           
            
        }

?>