<?php
global $wpdb, $site_url, $options;
$site_url = get_bloginfo('url');
$vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish' and post_type='page' limit 1");
$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
// QUERY FOR FETCHING THE PAGE LIMIT AND CURRENT THEME
$vid_page = $wpdb->get_row("SELECT option_value  FROM " . $wpdb->prefix . "options
                            WHERE option_name='current_theme'");
$items = $wpc_pagelimit;
$name_lower = strtolower($vid_page->option_value);
$theme_name = str_replace(' ', '', $name_lower); // getting the theme name
$page = 1;

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
    if ($limit != 0) {
        $pages = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1;
    }
    if ($pages == 1) {
        $pages = '';
    }
    return $pages;
}

/*
 * string pageList (int curpage, int pages)
 * Returns a list of pages in the format of "Ã‚Â« < [pages] > Ã‚Â»"
 * */

function pageList($curpage, $pages, $more=null, $playid=null, $page_id, $search = '') {
    //Pagination
    $page_list = "";
    if ($search != '') {
        $searchKey = urldecode('&search=' . $search);
        $self = '?page_id=' . $page_id . $searchKey;
    }else {
        $self = '?page_id=' . $page_id;
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

?>
<?php
				