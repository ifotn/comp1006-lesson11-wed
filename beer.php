<?php ob_start();

// set title and show header
$page_title = 'Beer Details';
require('header.php');

// auth
require('auth.php');

// initialize an empty id variable
$beer_id = null;
$name = null;
$alcohol_content = null;
$domestic = null;
$light = null;
$price = null;

//check if we have an beer ID in the querystring
if ((!empty($_GET['beer_id'])) && (is_numeric($_GET['beer_id']))) {

    //if we do, store in a variable
    $beer_id = $_GET['beer_id'];

    //connect
    require('db.php');

    //select all the data for the selected beer
    $sql = "SELECT * FROM beers WHERE beer_id = :beer_id";
    $cmd = $conn->prepare($sql);
    $cmd->bindParam(':beer_id', $beer_id, PDO::PARAM_INT);
    $cmd->execute();
    $beers = $cmd->fetchAll();

    //disconnect
    $conn = null;

    //store each value from the database into a variable
    foreach ($beers as $beer) {
        $name = $beer['name'];
        $alcohol_content = $beer['alcohol_content'];

        if ($beer['domestic'] == true) {
         $domestic = "checked";
        }

        if ($beer['light'] == true) {
            $light = "checked";
        }

        $price = $beer['price'];
        $logo = $beer['logo'];
    }
}
?>

<h1>Beer Information</h1>

<p>* indicates Required Fields</p>
<form method="post" action="save-beer.php" enctype="multipart/form-data">
    <fieldset>
        <label for="name" class="col-sm-2">Name: *</label>
        <input name="name" id="name" required placeholder="Beer Name" value="<?php echo $name; ?>" />
    </fieldset>
    <fieldset>
        <label for="alcohol_content" class="col-sm-2">Alcohol Content: *</label>
        <input name="alcohol_content" id="alcohol_content" required value="<?php echo $alcohol_content; ?>" />
    </fieldset>
    <fieldset>
        <label for="domestic" class="col-sm-2">Domestic:</label>
        <input name="domestic" id="domestic" type="checkbox" <?php echo $domestic; ?> />
    </fieldset>
    <fieldset>
        <label for="light" class="col-sm-2">Light:</label>
        <input name="light" id="light" type="checkbox" <?php echo $light; ?> />
    </fieldset>
    <fieldset>
        <label for="price" class="col-sm-2">Price: *</label>
        <input name="price" id="price" required value="<?php echo $price; ?>" />
    </fieldset>

    <fieldset>
        <label for="logo" class="col-sm-2">Logo:</label>
        <input type="file" name="logo" id="logo" />
    </fieldset>

    <?php if (!empty($logo)) {
    echo '<div class="col-sm-offset-2">
            <img title="Logo" src="images/' . $logo . '" class="img-circle" />
        </div>';
    }
    ?>

    <input type="hidden" name="beer_id" id="beer_id" value="<?php echo $beer_id; ?>" />
    <button class="btn btn-primary col-sm-offset-2">Save</button>
</form>

<?php require('footer.php'); 
ob_flush(); ?>