<html>
    <head>
        <title>untitled</title>
        <meta name="author" content="Steve R, http://www.designplace.org/">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    </head>
    <!-- � http://www.designplace.org/ -->
    <body>

        <form name="form" action="ns.php" method="get">
            <input type="text" name="q" />
            <input type="submit" name="Submit" value="Search" />
        </form>

        <?php
        // Get the search variable from URL

        $var = @$_GET['q'];
        $trimmed = trim($var); //trim whitespace from the stored variable
// rows to return
        $limit = 10;

// check for an empty string and display a message.
        if ($trimmed == "") {
            echo "<p>Please enter a search...</p>";
            exit;
        }

// check for a search parameter
        if (!isset($var)) {
            echo "<p>We dont seem to have a search parameter!</p>";
            exit;
        }

        include("config.php");

// Build SQL Query  
        $query = "select * from b_movies where Title like \"%$trimmed%\"  
  order by Title"; // EDIT HERE and specify your table and field names for the SQL query

        $numresults = mysql_query($query);
        $numrows = mysql_num_rows($numresults);

// If we have no results, offer a google search as an alternative

        if ($numrows == 0) {
            echo "<h4>Results</h4><br/>";
            echo "<p>Sorry, your search: &quot;" . $trimmed . "&quot; returned zero results</p>";

// google
            echo "<p><a href=\"http://www.google.com/search?q="
            . $trimmed . "\" target=\"_blank\" title=\"Look up 
  " . $trimmed . " on Google\">Click here</a> to try the 
  search on google</p>";
        }

// next determine if s has been passed to script, if not use 0
        $s = $_GET['s'];
        if (empty($s)) {
            $s = 0;
        }


// get results
        $query .= " limit $s,$limit";
        $result = mysql_query($query) or die("Couldn't execute query");

// display what the person searched for
        echo "<p>You searched for: &quot;" . $var . "&quot;</p>";

// begin to show results set
        echo "Results<br/>";
        $count = 1 + $s;

// now you can display the results returned
        while ($row = mysql_fetch_array($result)) {
            $title = $row["Title"];

            echo "$count.)&nbsp;$title<br/>";
            $count++;
        }

        $currPage = (($s / $limit) + 1);

//break before paging
        echo "<br />";

        // next we need to do the links to other results
        if ($s >= 1) { // bypass PREV link if s is 0
            $prevs = ($s - $limit);
            print "&nbsp;<a href=\"$PHP_SELF?s=$prevs&q=$var\">&lt;&lt; 
  Prev 10</a>&nbsp&nbsp;";
        }

// calculate number of pages needing links
        $pages = intval($numrows / $limit);

// $pages now contains int of pages needed unless there is a remainder from division

        if ($numrows % $limit) {
            // has remainder so add one page
            $pages++;
        }

// check to see if last page
        if (!((($s + $limit) / $limit) == $pages) && $pages != 1) {

            // not last page so give NEXT link
            $news = $s + $limit;

            echo "&nbsp;<a href=\"$PHP_SELF?s=$news&q=$var\">Next 10 &gt;&gt;</a>";
        }

        $a = $s + ($limit);
        if ($a > $numrows) {
            $a = $numrows;
        }
        $b = $s + 1;
        echo "<p>Showing results $b to $a of $numrows</p>";
        ?>

        <?php include("googleanalytics.php"); ?>
		<?php include("footeranalytics.php"); ?>
    </body>
</html>