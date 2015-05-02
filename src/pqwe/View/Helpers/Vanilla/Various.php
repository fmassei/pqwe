<?php
namespace pqwe\View\Helpers\Vanilla;

class Various {
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

