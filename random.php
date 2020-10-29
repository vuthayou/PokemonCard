<?php
    session_start();
    echo "A random pokemon id has been received";
    // Connect to the DB
    $serverName = 'localhost';
    $username = 'root';
    $password = '';
    $db = 'pokemondb';
    $mysql = mysqli_connect($serverName, $username, $password, $db);

    if (!$mysql)
        echo "connection failed: " . mysqli_connect_error();
    
    if (isset($_GET['randomVal'])) {
        $pokemonId = $_GET['randomVal'];
        unset($_GET['randomVal']);
    }
    else 
        $pokemonId = '';

    // Query to select the pokemon with prepare statement
    $query = "SELECT * FROM pokemon_description_image WHERE id=?";

    //Prepare statement 
    $stmt = $mysql->prepare($query); 
    //Check for if prepare failed
    if (!$stmt) {
        echo "Prepare Failed";
    }
    else {
        $stmt->bind_param('i', $pokemonId); //s is string, i is int...
        $stmt->execute();
    }
    //make query and get result (with default pagination)
    $result = $stmt->get_result();
    // fetch the resulting rows as an array
    $output = $result->fetch_all(MYSQLI_ASSOC);
    if ($pokemonId != '') { //if ( && isset($_GET) then we will give out a message that the pokemon doesn't exist)
        //echo json_encode($output);
        //echo "Name is " . $pokemonId;
        //echo $query;
    }
    $stmt->close(); // closes a previously opened database connection
    $mysql->close();
    $_SESSION['pokemonResult'] = $output;
    echo json_encode($_SESSION['pokemonResult']);
    header("Location: index.php");
    exit;
?>