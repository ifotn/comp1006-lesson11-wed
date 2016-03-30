<?php ob_start();

// header and auth
$page_title = 'Saving your Beer';
require_once ('header.php');
require_once ('auth.php');

try {
    // initialize variables
    $name = null;
    $alcohol_content = null;
    $domestic = null;
    $light = null;
    $price = null;
    $beer_id = null;
    $logo = null;
    $final_name = null;

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

    // validate and process photo upload if we have one
    if (!empty($_FILES['logo']['name'])) {
        $logo = $_FILES['logo']['name'];

        // get and check type
        $type = $_FILES['logo']['type'];

        if (($type == 'image/png') || ($type == 'image/jpeg')) {
            // save the file - valid type
            $final_name = session_id() . "-" . $logo;
            $tmp_name = $_FILES['logo']['tmp_name'];
            move_uploaded_file($tmp_name, "images/$final_name");
        }
        else {
            echo 'Logo must be a JPG or PNG<br >';
            $ok = false;
        }
    }
    else {
        // if the user didn't upload a new logo, grab the existing logo name if there is one
        if (!empty($_POST['current_logo'])) {
            $final_name = $_POST['current_logo'];
        }
    }

    // check if the form is ok to save or not
    if ($ok == true) {

        // connect to the db
        require('db.php');

        // set up the SQL command to save the data
        if (empty($beer_id)) {
            $sql = "INSERT INTO beers (name, alcohol_content, domestic, light, price, logo)
          VALUES (:name, :alcohol_content, :domestic, :light, :price, :logo)";
        } else {
            $sql = "UPDATE beers SET name = :name, alcohol_content = :alcohol_content,
          light = :light, domestic = :domestic, price = :price, logo = :logo
           WHERE beer_id = :beer_id";
        }

        // create a command object
        $cmd = $conn->prepare($sql);

        // put each input value into the proper field
        $cmd->bindParam(':name', $name, PDO::PARAM_STR);
        $cmd->bindParam(':alcohol_content', $alcohol_content, PDO::PARAM_INT);
        $cmd->bindParam(':domestic', $domestic, PDO::PARAM_BOOL);
        $cmd->bindParam(':light', $light, PDO::PARAM_BOOL);
        $cmd->bindParam(':price', $price, PDO::PARAM_INT);
        $cmd->bindParam(':logo', $final_name, PDO::PARAM_STR, 255);

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