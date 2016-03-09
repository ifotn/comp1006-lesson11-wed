<?php ob_start(); ?>

<!DOCTYPE html>
    <html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type">
        <title>Beer Saved</title>
    </head>
<body>
<?php

try {
    // initialize variables
    $name = null;
    $alcohol_content = null;
    $domestic = null;
    $light = null;
    $price = null;
    $beer_id = null;

    // store the form inputs in variables
    $name = $_POST['name'];
    $alcohol_content = $_POST['alcohol_content'];
    $domestic = $_POST['domestic'];
    $light = $_POST['light'];
    $price = $_POST['price'];
    $beer_id = $_POST['beer_id'];

    // display the values
    /* echo $name . '<br />';
    echo $alcohol_content . '<br />';
    echo $domestic . '<br />';
    echo $light . '<br />';
    echo $price . '<br />'; */

    // validate our inputs individually
    $ok = true;

    if (empty($name)) {
        echo 'Name is required<br />';
        $ok = false;
    }

    if ((empty($alcohol_content)) || (!is_numeric($alcohol_content))
        || ($alcohol_content < 0)
    ) {
        echo 'Alcohol content is required and must be 0 or greater<br />';
        $ok = false;
    }

    if ((empty($price)) || (!is_numeric($price))
        || ($price < 0)
    ) {
        echo 'Price is required and must be 0 or greater<br />';
        $ok = false;
    }

    // check if the form is ok to save or not
    if ($ok == true) {

        // connect to the db
        require('db.php');

        // set up the SQL command to save the data
        if (empty($beer_id)) {
            $sql = "INSERT INTO beers (name, alcohol_content, domestic, light, price)
          VALUES (:name, :alcohol_content, :domestic, :light, :price)";
        } else {
            $sql = "UPDATE beers SET name = :name, alcohol_content = :alcohol_content,
          light = :light, domestic = :domestic, price = :price WHERE beer_id = :beer_id";
        }

        // create a command object
        $cmd = $conn->prepare($sql);

        // put each input value into the proper field
        $cmd->bindParam(':name', $name, PDO::PARAM_STR);
        $cmd->bindParam(':alcohol_content', $alcohol_content, PDO::PARAM_INT);
        $cmd->bindParam(':domestic', $domestic, PDO::PARAM_BOOL);
        $cmd->bindParam(':light', $light, PDO::PARAM_BOOL);
        $cmd->bindParam(':price', $price, PDO::PARAM_INT);

        // add the beer id if we have one for an update
        if (!empty($beer_id)) {
            $cmd->bindParam(':beer_id', $beer_id, PDO::PARAM_INT);
        }

        // execute the save
        $cmd->execute();

        // disconnect
        $conn = null;

        /* echo '<h1>Beer Saved</h1>
             <a href="beers.php" title="View Beers">View Beer Listings</a>'; */
        // redirect
        header('location:beers.php');
    }
}
catch (Exception $e) {
    mail('sofun_corp@hotmail.com', 'Beer Store Error', $e);
    header('location:error.php');
}
?>
</body>
</html>

<?php ob_flush(); ?>