<?php
// have a prepared statement

    session_start();

    // Connect to the DB
    $serverName = 'localhost';
    $username = 'root';
    $password = '';
    $db = 'pokemondb';
    $mysql = mysqli_connect($serverName, $username, $password, $db);

    if (!$mysql)
        echo "connection failed: " . mysqli_connect_error();
    
    if (isset($_GET['pokemon'])) {
        $pokeName = $_GET['pokemon'];
        unset($_GET['pokemon']);
    }
    else 
        $pokeName = '';

    // Query to select the pokemon with prepare statement
    $query = "SELECT * FROM pokemon_description_image WHERE name=?";

    //Prepare statement 
    $stmt = $mysql->prepare($query); 
    //Check for if prepare failed
    if (!$stmt) {
        echo "Prepare Failed";
    }
    else {
        $stmt->bind_param('s', $pokeName); //s is string, i is int...
        $stmt->execute();
    }
    //make query and get result (with default pagination)
    $result = $stmt->get_result();
    // fetch the resulting rows as an array
    $output = $result->fetch_all(MYSQLI_ASSOC);
    if ($pokeName != '') { //if ( && isset($_GET) then we will give out a message that the pokemon doesn't exist)
        //echo json_encode($output);
        //echo "Name is " . $pokeName;
        //echo $query;
    }
    
    // Need this if statement, else our session will get overwritten
    if (!empty($output)) {
        $_SESSION['pokemonResult'] = $output;
    }
    
    $stmt->close(); // closes a previously opened database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> 
    <link rel="stylesheet" href="card.css" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <title>Pokemon Card</title>
</head>
<body>

    <div>
        <div >
            <div class="d-flex justify-content-center">
                <h1>Pokemon Card</h1>
            </div>  
            
            <!-- Use get here because this application is requesting datas -->
            <!--<form id="searchForm" method="GET" action="http://localhost/PokemonCard/index.php"> -->
            <div class="d-flex justify-content-center">
                <form id="searchForm" method="GET" action="index.php">
                    <input class="search form-control mr-sm-2" placeholder="eg: Pikachu" name="pokemon"></input>
                </form>
                <form id="randomForm" method="GET" action="random.php">
                    <input type="hidden"  id="randomVal" name="randomVal" value="">
                    <button type="button" class="random btn btn-success">Random</button>
                </form>
            </div>
        </div>
    
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <img class="card" src="backSide.jpg" alt="Avatar">
                </div>
                <div class="flip-card-back">
                    <?php
                        foreach ($_SESSION["pokemonResult"] as $tmp) {
                            // Case sensitive
                           
                            echo "<p class='name'>" . $tmp['Name'] . "</p>";
                            echo "<div class='characters'> <img class='gif' src='" . $tmp['GIF'] . "'> </div>";
                            echo "<div class='contents'>";
                            echo "<p> Description: " . $tmp['Description'] . "</p>";
                            echo "<div class='main-types'>";
                            echo "<p class='types'> Type 1: " . $tmp['Type 1'] . "</p>";
                            echo "<p class='types'> Type 2: " . $tmp['Type 2'] . "</p>";
                            echo "</div>";
                            echo "<p> HP: " . $tmp['HP'] . "</p>";
                            echo "<p> Attack: " . $tmp['Attack'] . "</p>";
                            echo "<p> Defense: " . $tmp['Defense'] . "</p>";
                            echo "<p> Speed: " . $tmp['Speed'] . "</p>";
                            echo "<p> Special: " . $tmp['Speed'] . "</p>";
                            echo "</div>";
                            
                           
                        }
                    ?>
                </div>
            </div>
            
        </div>
        
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>
    <script>
        // wait for a certain time then submit then flip the card immediately right after)
        $(".search").keyup(function() {
            debounced();
        });
        var debounced = _.debounce(function() { 
            $("#searchForm").submit();
        }, 0); 

        $(".random").click(function() {
            var random = Math.floor(Math.random() * 151) + 1;
            $("#randomVal").val(random);
            $("#randomForm").submit();
        });
        /* a callback function to trigger
        $.when( $.ajax( "/index.php" )).then(Flip, Same);
        function Flip() {
            setTimeout(() => {
                $(".flip-card-inner").css("transform", "rotateY(180deg)");
                console.log("Card is flipped");
            }, 2000);
            
        }
        function Same() {
            console.log("Fail");
        } */
    </script>
    
</body>
</html>