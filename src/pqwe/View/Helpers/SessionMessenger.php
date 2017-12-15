<?php
/**
 * SessionMessenger class
 */
namespace pqwe\View\Helpers;

/**
 * Help sending messages through pages using the session.
 *
 * For this class to work, session_start() has to be called before its usage.
 */
class SessionMessenger {
    /** @var string SESSIONKEY The name of the key in $_SESSION */
    const SESSIONKEY = "session_messenger";

    /**
     * add a message with the given key
     *
     * @param string $message The message
     * @param string $key The key in which the message will be stored
     * @return void
     */
    public function addMessage($message, $key) {
        if (!isset($_SESSION[self::SESSIONKEY]))
            $_SESSION[self::SESSIONKEY] = array();
        if (!isset($_SESSION[self::SESSIONKEY][$key]))
            $_SESSION[self::SESSIONKEY][$key] = array();
        $_SESSION[self::SESSIONKEY][$key][] = $message;
    }

    /**
     * add a success message (addMessage() with key "success")
     *
     * @param string $message 
     * @return void
     */
    public function addMessageSuccess($message) {
        $this->addMessage($message, 'success');
    }

    /**
     * add a warning message (addMessage() with key "warning")
     *
     * @param string $message 
     * @return void
     */
    public function addMessageWarning($message) {
        $this->addMessage($message, 'warning');
    }

    /**
     * add a error message (addMessage() with key "error")
     *
     * @param string $message 
     * @return void
     */
    public function addMessageError($message) {
        $this->addMessage($message, 'error');
    }

    /**
     * return the array of stored messages
     *
     * @param string $key If set, return only the messages with the passed key
     * @return array
     */
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

    /**
     * clean all the messages
     * 
     * @param string $key If set, return only the messages with the passed key
     */
    public function clearMessages($key=null) {
        if (!isset($_SESSION[self::SESSIONKEY]))
            return;
        if ($key===null)
            unset($_SESSION[self::SESSIONKEY]);
        else if (isset($_SESSION[self::SESSIONKEY][$key]))
            unset($_SESSION[self::SESSIONKEY][$key]);
    }
}

