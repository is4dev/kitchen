<?php
/*$Id: modusers.php 814 2012-10-16 13:28:15Z dmitriy $*/

require_once('head_inc.php');

    if ( !is_null( $moder ) && $moder > 0 ) {
        $cur_page = $page_m_users;
        $how_many = 50;
        $max_id = 1;

        $last_id = 0;

        $limit = '';

        $query = 'SELECT count(*) from confa_users';
        if ( !is_null($byip) && strlen($byip) > 0) {
            #Subquery with 'in ()' or 'any' are very slow - easier to write 2 queries
            $subquery = 'select distinct(author) from confa_posts where IP=\'' . $byip . '\''; 
            $result = mysql_query($subquery);
            if (!$result) {
                mysql_log(__FILE__, 'query failed ' . mysql_error() . ' QUERY: ' . $query);
                die('Query failed ' );
            }   
            $in = '';
            while( $row = mysql_fetch_row( $result ) ) {
                if ( strlen($in) > 0 ) {
                    $in .=',';
                }
                $in .= $row[0];
            }
            $limit  = ' where id in (' . $in . ') ';
            $query .= $limit;
            $result = mysql_query($query);
        } else {
            $result = mysql_query($query);
        }

        if (!$result) {
            mysql_log(__FILE__, 'query failed ' . mysql_error() . ' QUERY: ' . $query);
            die('Query failed ' );
        }
        $row = mysql_fetch_row($result);
        $count = $row[0]; 

        #update banned users
        $query = 'update confa_users set ban=NULL, ban_ends=\'0000-00-00 00:00:00\' where ban_ends > \'0000-00-00 00:00:00\' and ban_ends < current_timestamp()';
        $result = mysql_query($query);
        if (!$result) {
            mysql_log(__FILE__, 'query failed ' . mysql_error() . ' QUERY: ' . $query);
            die('Query failed ' );
        }

        $query = 'SELECT username, status, moder, ban, CONVERT_TZ(ban_ends, \'' . $server_tz . '\', \'' . $prop_tz . ':00\') as ban_ends, CONVERT_TZ(created, \'' . $server_tz . '\', \'' . $prop_tz . ':00\') as created, id from confa_users ' . $limit . ' order by username'; 

        $result = mysql_query($query);
        if (!$result) {
            mysql_log(__FILE__, 'query failed ' . mysql_error() . ' QUERY: ' . $query);
            die('Query failed ' );
        }

        $num = 1;  

        $out = '';
        if (mysql_num_rows($result) == 0) {
            $max_id = $last_id;
        }
        $num = 1;
        while ($row = mysql_fetch_assoc($result)) {
            $id = $row['id'];
            $created = $row['created'];
            $status = 'Active';
            if (!is_null($row['ban_ends']) && strcmp($row['ban_ends'], '0000-00-00 00:00:00')) {
                $status = 'Banned till ' . $row['ban_ends'];
            }
            if ( $row['status'] == 2 ) {
                $status = 'Disabled';
            }

            $enc_user = htmlentities($row['username'], HTML_ENTITIES,'UTF-8');
            if ( $row['status'] == 2 ) {
                $enc_user= '<del>' . $enc_user . '</del>';
            }
            $line = '<tr><td>' . $num . ' <a target="bottom" href="' . $root_dir . $page_m_user . '?moduserid=' . $id . '"> ' . $enc_user . ' </a>' . '</td><td align="center">' . $id . '</td><td align="center">' . $status . '</td><td align="center">' . $created . '</td></tr>';
            $out .= $line;
            $num++;
        }
    }

require_once('html_head_inc.php');

?>
<base target="bottom">
</head>
<body >
<!--<table width="95%"><tr>
<td>-->
<!--<h3><?php print($title);?></h3>-->
<!--</td>

</tr></table>-->
<?php
    if ( !is_null( $moder ) && $moder > 0 ) {

require('menu_inc.php');
        if (!is_null($err) && strlen($err) > 0) {
            print('<BR><font color="red"><b>' . $err . '</b></font>');
        }
        if (!is_null($byip) && strlen($byip) > 0) {
            print('<P>Posting from IP: <B>' . $byip . '</B>');
        }
?>

<!--<ol>-->
<table width="95%">
<tr><th>Username</th><th>Id</th><th>Status</th><th>Created</th></tr>
<?php print($out); ?>
</table>
<!--</ol>-->
<?php
    } else {
        print( "You have no access to this page." );
    }
?>
</body>
</html>
<?php

require('tail_inc.php');

?>

