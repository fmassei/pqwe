<?php
namespace pqwe\View\Helpers\Bootstrap;

class Various {
    public function renderSessionMessenger($sessionMessenger) {
        $messages = $sessionMessenger->getMessages();
        $ret = "";
        /* default messages classes */
        if (isset($messages['success'])) {
            $ret .= '<div class="alert alert-success" role="alert">';
            $ret .= implode('<br>', $messages['success']);
            $ret .= '</div>';
        }
        if (isset($messages['warning'])) {
            $ret .= '<div class="alert alert-warning" role="alert">';
            $ret .= implode('<br>', $messages['warning']);
            $ret .= '</div>';
        }
        if (isset($messages['error'])) {
            $ret .= '<div class="alert alert-danger" role="alert">';
            $ret .= implode('<br>', $messages['error']);
            $ret .= '</div>';
        }
        /* check for user-defined classes */
        $haveOtherKeys = false;
        foreach($messages as $k=>$v) {
            if ($k!='success' && $k!='warning' && $k!='error') {
                $haveOtherKeys = true;
                break;
            }
        }
        if ($haveOtherKeys) {
            $ret .= '<div class="alert" role="alert">';
            foreach($messages as $k=>$v) {
                if ($k=='success' || $k=='warning' || $k=='error')
                    continue;
                $ret .= implode('<br>', $messages[$k]);
            }
            $ret .= '</div>';
        }
        return $ret;
    }
}

