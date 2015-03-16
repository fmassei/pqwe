<?php
protected $f_htaccess =
"RewriteEngine On
# If the requested filename exists, serve it.
RewriteCond %{REQUEST_FILENAME} -s [OR]
RewriteCond %{REQUEST_FILENAME} -l [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^.*$ - [NC,L]
# All other queries to index.php. It works with virtual hosting too.
RewriteCond %{REQUEST_URI}::$1 ^(/.+)(.+)::\2$
RewriteRule ^(.*) - [E=BASE:%1]
RewriteRule ^(.*)$ %{ENV:BASE}index.php [NC,L]
# Disable the multiview function in apache
Options -MultiViews";
protected $f_indexphp =
"<?php
chdir(dirname(__DIR__).'/private/');

if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url(\$_SERVER['REQUEST_URI'], PHP_URL_PATH)))
    return false;

require_once(\"../vendor/autoload.php\");

header('Content-Type: text/html; charset=utf-8');

\$serviceManager = new \pqwe\ServiceManager\ServiceManager(include('config/config.php'));
\$mvc = new \pqwe\MVC\MVC(\$serviceManager);
try {
    \$mvc->run();
} catch(\pqwe\Exception\PqweRoutingException \$ex) {
    header(\"HTTP/1.0 404 Not Found\");
    die();
}";
protected $f_config =
"<?php
return array(
    'service_manager' => array(
        'invokables' => array(),
        'factories' => array(),        
    ),
    'routes' => array(
        array(
            'type' => 'exact',
            'route' => '/',
            'controller' => '',
            'action' => '',
        ),
    ),
);";
