<?php
class pqwe_utils {
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

    protected function print_usage() {
        echo
"usage: pqwe_util <command> <parameters>
command:
    create-project <name> <basedir>      create a standard skelethon for a new
                                         project in the given directory
";
    }
    private function print_last_error() {
        $err = error_get_last();
        echo "Error: {$err['message']}\n";
    }
    private function mkpath() {
        return join(DIRECTORY_SEPARATOR, func_get_args());
    }
    protected function do_create_project($name, $basedir) {
        /* TODO check or change name for folder name */
        $privateDir = $this->mkpath($basedir, "private");
        $configDir = $this->mkpath($privateDir, "config");
        $prjDir = $this->mkpath($privateDir, $name);
        $publicDir = $this->mkpath($basedir, "public");
        if (    @chdir($basedir)===false ||
                @mkdir($privateDir)===false ||
                @mkdir($configDir)===false ||
                @mkdir($prjDir)===false ||
                @mkdir($publicDir)===false ||
                @file_put_contents($this->mkpath($publicDir,".htaccess"),
                                   $this->f_htaccess)===false ||
                @file_put_contents($this->mkpath($configDir,"config.php"),
                                   $this->f_config)===false ||
                @file_put_contents($this->mkpath($publicDir,"index.php"),
                                   $this->f_indexphp)===false) {
            $this->print_last_error();
            return 1;
        }
        return 0;
    }
    protected function run($argc, &$argv) {
        if ($argc<=1) {
            $this->print_usage();
            exit(1);
        }
        $command = $argv[1];
        switch($command) {
        case "create-project":
            if (!isset($argv[2]) || !isset($argv[3])) {
                echo "to few parameters for create-project\n";
                $this->print_usage();
                exit(1);
            }
            return $this->do_create_project($argv[2], $argv[3]);
        default:
            echo "Unknown command '$command'\n";
            $this->print_usage();
            exit(1);
        }
        return 0;
    }
    public static function main($argc, &$argv) {
        $instance = new self();
        return $instance->run($argc, $argv);
    }
}

return pqwe_utils::main($argc, $argv);
