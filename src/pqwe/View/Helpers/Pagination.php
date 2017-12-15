<?php
/**
 * Pagination class
 */
namespace pqwe\View\Helpers;

/**
 * Help sending messages through pages using the session.
 *
 * For this class to work, session_start() has to be called before its usage.
 */
class Pagination {
    public static function renderLinks($total, $step, $currentPage, $range=2, $class="pagination") {
        $ret = '<div class="'.$class.'">';
        $nPages = ceil($total/(double)$step);
        if ($total>$step) {
            if ($currentPage>1) {
                $ret .= '<a href="/admin/audit?p=1">&lt;&lt;</a> ';
                $ret .= '<a href="/admin/audit?p='.($currentPage-1).'">&lt</a> ';
            }
            for ($i=$currentPage-$range; $i<($currentPage+$range)+1; ++$i) {
                if ($i>0 && $i<$nPages) {
                    if ($i==$currentPage)
                        $ret .= " [<b>$i</b>] ";
                    else
                        $ret .= '<a href="/admin/audit?p='.$i.'">'.$i.'</a> ';
                }
            }
            if ($currentPage!=$nPages) {
                $ret .= '<a href="/admin/audit?p='.($currentPage+1).'">&gt;</a> ';
                $ret .= '<a href="/admin/audit?p='.$nPages.'">&gt;&gt;</a> ';
            }
        } else {
            $ret .= "[<b>1</b>]";
        }
        $ret .= '</div>';
        return $ret;
    }
}

