<?php
/**
 * Various class
 */
namespace pqwe\View\Helpers\Vanilla;

/**
 * view helpers for various operations
 */
class Various {
    /**
     * return the rendered passed SessionMessenger
     *
     * @param \pqwe\View\Helpers\SessionMessenger $sessionMessenger
     * @return string
     */
    public function renderSessionMessenger($sessionMessenger) {
        $messages = $sessionMessenger->getMessages();
        $ret = "";
        if (count($messages)>0) {
            foreach ($messages as $k=>$kMessages) {
                $ret .= '<div class="alert alert-'.$k.'" role="alert">';
                $ret .= implode('<br>', $kMessages);
                $ret .= '</div>';
            }
        }
        return $ret;
    }
}

