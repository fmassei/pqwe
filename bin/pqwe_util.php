<?php
class pqwe_utils {
    private $stdin;     /* stdin handle */

    protected $f_htaccess =
"RewriteEngine On
# If the requested filename exists, serve it.
RewriteCond %{REQUEST_FILENAME} -s [OR]
RewriteCond %{REQUEST_FILENAME} -l [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^.*$ - [NC,L]
# All other queries to index.php. It works with virtual hosting too.
RewriteCond %{REQUEST_URI}::$1 ^(/.+)(.+)::\$
RewriteRule ^(.*) - [E=BASE:%1]
RewriteRule ^(.*)$ %{ENV:BASE}index.php [NC,L]
# Disable the multiview function in apache
Options -MultiViews";
    protected $f_indexphp =
"<?php
chdir(dirname(__DIR__).'/[_PRIVATEDIR]/');

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
    create-project <basedir>        create a standard skelethon for a new
                                    project in the given directory
    create-module <name> <dir>      create a new module in the give dir (which
                                    you probably want to set to your project
                                    private dir)
";
    }
    private function print_last_error() {
        $err = error_get_last();
        echo "Last error: {$err['message']}\n";
    }
    private function mkpath() {
        return join(DIRECTORY_SEPARATOR, func_get_args());
    }
    private function getUserInput($default) {
        $line = fgets($this->stdin);
        if ($line===false || ($ui = trim($line))=="")
            return $default;
        return $ui;
    }
    private function mkdir($name) {
        if (@mkdir($name)===false)
            throw new \Exception("could not create dir $name");
        echo "- created directory '$name'\n";
    }
    private function file_put_contents($file, $str) {
        if (@file_put_contents($file, $str)===false)
            throw new \Exception("could not write to file $file");
        echo "- file $file filled\n";
    }
    private function createDirAsk($descr, $default) {
        echo "$descr [$default]: ";
        $name = $this->getUserInput($default);
        $this->mkdir($name);
        return $name;
    }
    private function askPermission($descr, $defaultTrue) {
        if ($defaultTrue) { $y='Y'; $n='n'; }
        else { $y='y'; $n='N'; }
        echo "$descr [$y/$n]? ";
        $ret = strtolower($this->getUserInput($defaultTrue?'y':'n'));
        return $ret=='y';
    }
    protected function do_create_project($basedir) {
        try {
            if (@chdir($basedir)===false)
                throw new \Exception("could not access dir $basedir");
            echo "project creation started:\n".
                 "-------------------------\n";
            $publicDir = $this->createDirAsk(
                "public directory (webserver root)", "public");
            $privateDir = $this->createDirAsk(
                "private directory (code, views, configs, etc.)", "private");
            $configDir = $this->mkpath($privateDir, "config");
            $this->mkdir($configDir);
            if ($this->askPermission("create a default config file", true))
                $this->file_put_contents($this->mkpath($configDir,"config.php"),
                                         $this->f_config);
            if ($this->askPermission("create a default .htaccess", true))
                $this->file_put_contents($this->mkpath($publicDir,".htaccess"),
                                         $this->f_htaccess);
            if ($this->askPermission("create a default index.php", true))
                $this->file_put_contents($this->mkpath($publicDir,"index.php"),
                                    str_replace("[_PRIVATEDIR]", $privateDir,
                                                $this->f_indexphp));
            if ($this->askPermission("create a new module now", true)) {
                echo "module name: ";
                if (($name = $this->getUserInput(""))=="")
                    echo "abort\n";
                else
                    $this->do_create_module($name, $privateDir);
            }
        } catch(\Exception $ex) {
            if (($msg = $ex->getMessage())!="")
                echo "Error: $msg\n";
            $this->print_last_error();
            return false;
        }
        return true;
    }
    protected function do_create_module($name, $privateDir) {
        try {
            if (@chdir($privateDir)===false)
                throw new \Exception("could not access dir $privateDir");
            echo "module creation started:\n".
                 "------------------------\n";
            $moduleDir = $name;
            $this->mkdir($moduleDir);
            $this->mkdir($this->mkpath($moduleDir, "src"));
            $this->mkdir($this->mkpath($moduleDir, "src", "Controller"));
            $this->mkdir($this->mkpath($moduleDir, "src", "Model"));
            $this->mkdir($this->mkpath($moduleDir, "view"));
        } catch(\Exception $ex) {
            if (($msg = $ex->getMessage())!="")
                echo "Error: $msg\n";
            $this->print_last_error();
            return false;
        }
        return true;
    }
    protected function run($argc, &$argv) {
        if ($argc<=1) {
            $this->print_usage();
            exit(1);
        }
        $command = $argv[1];
        switch($command) {
        case "create-project":
            if (!isset($argv[2])) {
                echo "to few parameters for $command\n";
                $this->print_usage();
                exit(1);
            }
            return $this->do_create_project($argv[2]) ? 0 : 1;
        case "create-module":
            if (!isset($argv[2]) || !isset($argv[3])) {
                echo "to few parameters for $command\n";
                $this->print_usage();
                exit(1);
            }
            return $this->do_create_module($argv[2], $argv[3]) ? 0 : 1;
        default:
            echo "Unknown command '$command'\n";
            $this->print_usage();
            exit(1);
        }
        return 0;
    }
    public function __construct() {
        $this->stdin = fopen('php://stdin', 'r');
    }
    public static function main($argc, &$argv) {
        $instance = new self();
        return $instance->run($argc, $argv);
    }
}

return pqwe_utils::main($argc, $argv);
