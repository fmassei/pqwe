<?php
class pqwe_utils {
    require_once('pqwe_utils.i.php');

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
                                   $this->f_indexphp)===false)
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
