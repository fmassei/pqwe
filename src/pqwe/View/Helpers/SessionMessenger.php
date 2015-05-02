<?php
namespace pqwe\View\Helpers;

class SessionMessenger {
    const SESSIONKEY = "session_messenger";

    public function addMessage($message, $key) {
        if (!isset($_SESSION[self::SESSIONKEY]))
            $_SESSION[self::SESSIONKEY] = array();
        if (!isset($_SESSION[self::SESSIONKEY][$key]))
            $_SESSION[self::SESSIONKEY][$key] = array();
        $_SESSION[self::SESSIONKEY][$key][] = $message;
    }
    public function addMessageSuccess($message) {
        $this->addMessage($message, 'success');
    }
    public function addMessageWarning($message) {
        $this->addMessage($message, 'warning');
    }
    public function addMessageError($message) {
        $this->addMessage($message, 'error');
    }
    public function getMessages($key=null) {
        if (!isset($_SESSION[self::SESSIONKEY]))
            return array();
        if ($key===null) {
            return $_SESSION[self::SESSIONKEY];
        } else {
            if (!isset($_SESSION[self::SESSIONKEY][$key]) )
                return array();
            return $_SESSION[self::SESSIONKEY][$key];
        }
    }
    public function clearMessages($key=null) {
        if (!isset($_SESSION[self::SESSIONKEY]))
            return;
        if ($key===null)
            unset($_SESSION[self::SESSIONKEY]);
        else if (isset($_SESSION[self::SESSIONKEY][$key])
            unset(isset($_SESSION[self::SESSIONKEY][$key]);
    }
}

