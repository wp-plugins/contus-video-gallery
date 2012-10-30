<?php 
class pagination{
	function listPagesNoTitle($args) { //Pagination
        if ($args) {
            $args .= '&echo=0';
        } else {
            $args = 'echo=0';
        }
        $pages = wp_list_pages($args);
        echo $pages;
    }
    function findStart($limit) { //Pagination
        if ((!isset($_GET['page'])) || ($_GET['page'] == "1")) {
            $start = 0;
            $_GET['page'] = 1;
        } else {
            $start = ($_GET['page'] - 1) * $limit;
        }
        return $start;
    }
    /*
     * int findPages (int count, int limit)
     * Returns the number of pages needed based on a count and a limit
     */
    function findPages($count, $limit) { //Pagination
        $pages = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1;
        if ($pages == 1) {
            $pages = '';
        }
        return $pages;
    }
    /*
     * string pageList (int curpage, int pages)
     * Returns a list of pages in the format of "Ã‚Â« < [pages] > Ã‚Â»"
     **/
    function pageList($curpage, $pages, $more=null, $playid=null, $page_id, $search = '') {
        //Pagination
        $page_list = "";
        if ($playid == '') {
            $playid = '&more=' . $more;
        }else if($playid != '' && $more!=''){
            $playid = '&more=' . $more.'&playid=' . $playid;
        }else {
            $playid = '&playid=' . $playid;
        }
        if ($search != '') {
            $searchKey = urldecode('&video_search=' . $search);
            $self = '?page_id=' . $page_id . $searchKey;
        } else {
            $self = '?page_id=' . $page_id . $playid;
        }

        /* Print the first and previous page links if necessary */
        if (($curpage != 1) && ($curpage)) {
            $page_list .= "  <a href=\"" . $self . "&page=1\" title=\"First Page\"><<</a> ";
        }

        if (($curpage - 1) > 0) {
            $page_list .= "<a href=\"" . $self . "&page=" . ($curpage - 1) . "\" title=\"Previous Page\"><</a> ";
        }

        /* Print the numeric page list; make the current page unlinked and bold */
        for ($i = 1; $i <= $pages; $i++) {
            if ($i == $curpage) {
                $page_list .= "<b>" . $i . "</b>";
            } else {
                $page_list .= "<a href=\"" . $self . "&page=" . $i . "\" title=\"Page " . $i . "\">" . $i . "</a>";
            }
            $page_list .= " ";
        }

        /* Print the Next and Last page links if necessary */
        if (($curpage + 1) <= $pages) {
            $page_list .= "<a href=\"" . $self . "&page=" . ($curpage + 1) . "\" title=\"Next Page\">></a> ";
        }

        if (($curpage != $pages) && ($pages != 0)) {
            $page_list .= "<a href=\"" . $self . "&page=" . $pages . "\" title=\"Last Page\">>></a> ";
        }
        $page_list .= "</td>\n";

        return $page_list;
    }
    /*
     * string nextPrev (int curpage, int pages)
     * Returns "Previous | Next" string for individual pagination (it's a word!)
     */
    function nextPrev($curpage, $pages) { //Pagination
        $next_prev = "";

        if (($curpage - 1) <= 0) {
            $next_prev .= "Previous";
        } else {
            $next_prev .= "<a href=\"" . $_SERVER['PHP_SELF'] . "&page=" . ($curpage - 1) . "\">Previous</a>";
        }
        $next_prev .= " | ";
        if (($curpage + 1) > $pages) {
            $next_prev .= "Next";
        } else {
            $next_prev .= "<a href=\"" . $_SERVER['PHP_SELF'] . "&page=" . ($curpage + 1) . "\">Next</a>";
        }
        return $next_prev;
    }	
}
?>