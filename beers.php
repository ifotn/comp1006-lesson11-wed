<?php ob_start();

// set title
$page_title = 'Beer Listings';
require('header.php');

// auth check
require('auth.php');
?>

<h1>Beer Listings</h1>

<!-- search form -->
<div class="col-sm-12 text-right">
    <form method="get" action="beers.php" class="form-inline">
        <label for="keywords">Keywords:</label>
        <input name="keywords" id="keywords" />
        <select name="search_type" id="search_type">
            <option value="OR">Any Keyword</option>
            <option value="AND">All Keywords</option>
        </select>
        <button class="btn btn-success">Search</button>
    </form>
</div>

<?php

try {
    // connect
    require('db.php');

    // prepare the query
    $sql = "SELECT * FROM beers";
    $keyword_list = null;

    // check for keywords, build the WHERE clause dynamically
    if (!empty($_GET['keywords'])) {
        $keywords = $_GET['keywords'];

        // convert 1 single keyword value into a list of separate values
        $keyword_list = explode(" ", $keywords);

        // start building the WHERE clause
        $sql .= " WHERE ";
        $counter = 0;

        // set the search type AND/OR
        $search_type = $_GET['search_type'];

        // check the word_list array
        foreach($keyword_list as $word) {

            // add the word OR if we are not on the first keyword
            if ($counter > 0) {
                $sql .= " $search_type ";
            }

            //works but breaks with special characters
            //$sql .= " name LIKE '%" . $word . "%'";

            $sql .= " name LIKE ?";
            $keyword_list[$counter] = '%' . $word . '%';

            $counter++;
            //echo "$word <br />";
        }
    }

    // echo $sql;

    // add order by clause
    $sql .= " ORDER BY name";
   // $sql = $sql . "  ORDER BY name";

    // run the query and store the results
    $cmd = $conn->prepare($sql);
    $cmd->execute($keyword_list);
    $beers = $cmd->fetchAll();

    // disconnect
    $conn = null;

    // start the grid with HTML
    echo '<table class="table table-striped sortable"><thead>
        <th><a href="#">Name</a></th>
        <th><a href="#">Alcohol Content</a></th>
        <th><a href="#">Domestic</a></th>
        <th><a href="#">Light</a></th>
        <th><a href="#">Price</a></th>
        <th>Edit</th><th>Delete</th></thead><tbody>';

    /* loop through the data, displaying each value in a new column
    and each beer in a new row */
    foreach ($beers as $beer) {
        echo '<tr><td>' . $beer['name'] . '</td>
            <td>' . $beer['alcohol_content'] . '</td>
            <td>' . $beer['domestic'] . '</td>
            <td>' . $beer['light'] . '</td>
            <td>' . $beer['price'] . '</td>
            <td><a href="beer.php?beer_id=' . $beer['beer_id'] . '" title="Edit">Edit</a></td>
            <td><a href="delete-beer.php?beer_id=' . $beer['beer_id'] . '"
                title="Delete" class="confirmation">Delete</a></td>
            </tr>';
    }

    // close the HTML grid
    echo '</tbody></table>';
}
catch (Exception $e) {
    // send ourselves the error
    mail('sofun_corp@hotmail.com', 'Beer Store Error', $e);

    // redirect to the error page
    header('location:error.php');
}

// footer
require('footer.php');
ob_flush();
?>

