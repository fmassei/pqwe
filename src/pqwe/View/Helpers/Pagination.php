<?php
/**
 * Pagination class
 */
namespace pqwe\View\Helpers;

/**
 * Help rendering pagination links
 *
 * @param $nElements int Total number of elements
 * @param $step int Number of elements to display in a page
 * @param $currentPage int Current page number
 * @param $baseUrl string Page URL to which the page number will be added
 *                          (something like "/list?p=")
 * @param $range int Range of pages to display around current page
 * @param $class string Css classes for the container
 * @return string
 */
class Pagination {
    public static function renderLinks($nElements, $step, $currentPage, $baseUrl, $range=2, $class="pagination") {
        $ret = '<div class="'.$class.'">';
        $nPages = ceil($nElements/(double)$step);
        if ($nElements>$step) {
            if ($currentPage>1) {
                $ret .= '<a href="'.$baseUrl.'1">&lt;&lt;</a> ';
                $ret .= '<a href="'.$baseUrl.''.($currentPage-1).'">&lt</a> ';
            }
            for ($i=$currentPage-$range; $i<($currentPage+$range)+1; ++$i) {
                if ($i>0 && $i<$nPages) {
                    if ($i==$currentPage)
                        $ret .= " [<b>$i</b>] ";
                    else
                        $ret .= '<a href="'.$baseUrl.''.$i.'">'.$i.'</a> ';
                }
            }
            if ($currentPage!=$nPages) {
                $ret .= '<a href="'.$baseUrl.''.($currentPage+1).'">&gt;</a> ';
                $ret .= '<a href="'.$baseUrl.''.$nPages.'">&gt;&gt;</a> ';
            }
        } else {
            $ret .= "[<b>1</b>]";
        }
        $ret .= '</div>';
        return $ret;
    }
}

