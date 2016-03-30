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
        <button class="btn btn-success">Search</button>
    </form>
</div>

<?php

try {
    // connect
    require('db.php');

    // prepare the query
    $sql = "SELECT * FROM beers ORDER BY name";
    $cmd = $conn->prepare($sql);

    // run the query and store the results
    $cmd->execute();
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

