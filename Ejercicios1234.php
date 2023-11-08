<?php
//connect to MySQL
$db = mysqli_connect('localhost', 'root', 'root') or 
    die ('Unable to connect. Check your connection parameters.');
mysqli_select_db($db, 'moviesite') or die(mysqli_error($db));


if (isset($_POST['enviar'])) {
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';

    // Convierte los valores de las variables en la consulta SQL a comillas simples
    $autor = $_POST['autor'];
    $descripcion = $_POST['descripcion'];
    $estrellas = $_POST['estrellas'];

   // Reemplaza 'valor_de_movie_id' con el valor adecuado para identificar la película a la que pertenece la reseña
    $movie_id = $_GET['movie_id'];

// Consulta SQL corregida
$query = "INSERT INTO reviews (review_date, reviewer_name, review_comment, review_rating, review_movie_id)
VALUES (NOW(), '$autor', '$descripcion', $estrellas, $movie_id)";


    // Ejecutar la consulta
    $result = mysqli_query($db, $query);

    if ($result) {
        echo "La reseña se ha insertado correctamente en la base de datos.";
    } else {
        echo "Hubo un error al insertar la reseña en la base de datos: " . mysqli_error($db);
    }
}

// function to generate ratings
function generate_ratings($rating) {
    $movie_rating = '';
    for ($i = 0; $i < $rating; $i++) {
        $movie_rating .= '<img src="star.png" alt="star"/>';
    }
    return $movie_rating;
}

// take in the id of a director and return his/her full name
function get_director($director_id) {

    global $db;

    $query = 'SELECT 
            people_fullname 
       FROM
           people
       WHERE
           people_id = ' . $director_id;
    $result = mysqli_query($db, $query) or die(mysql_error($db));

    $row = mysqli_fetch_assoc($result);
    extract($row);

    return $people_fullname;
}

// take in the id of a lead actor and return his/her full name
function get_leadactor($leadactor_id) {

    global $db;

    $query = 'SELECT
            people_fullname
        FROM
            people 
        WHERE
            people_id = ' . $leadactor_id;
    $result = mysqli_query($db, $query) or die(mysql_error($db));

    $row = mysqli_fetch_assoc($result);
    extract($row);

    return $people_fullname;
}

// take in the id of a movie type and return the meaningful textual
// description
function get_movietype($type_id) {

    global $db;

    $query = 'SELECT 
            movietype_label
       FROM
           movietype
       WHERE
           movietype_id = ' . $type_id;
    $result = mysqli_query($query, $db) or die(mysql_error($db));

    $row = mysql_fetch_assoc($result);
    extract($row);

    return $movietype_label;
}

// function to calculate if a movie made a profit, loss or just broke even
function calculate_differences($takings, $cost) {

    $difference = $takings - $cost;

    if ($difference < 0) {     
        $color = 'red';
        $difference = '$' . abs($difference) . ' million';
    } elseif ($difference > 0) {
        $color ='green';
        $difference = '$' . $difference . ' million';
    } else {
        $color = 'blue';
        $difference = 'broke even';
    }

    return '<span style="color:' . $color . ';">' . $difference . '</span>';
}

//connect to MySQL
$db = mysqli_connect('localhost', 'root', 'root') or 
    die ('Unable to connect. Check your connection parameters.');
mysqli_select_db($db, 'moviesite') or die(mysqli_error($db));

$query = 'SELECT 
        AVG(review_rating) as vidanaRating
    FROM
        reviews
    WHERE
    review_movie_id = ' .$_GET['movie_id'];

$result = mysqli_query($db, $query) or die(mysql_error($db));
$row = mysqli_fetch_assoc($result);
$vidana_rating = number_format($row['vidanaRating'], 2);

// Procesar los parámetros de orden
$orderby = 'review_date'; // Orden predeterminado

if (isset($_GET['orderby'])) {
    if ($_GET['orderby'] === 'rating') {
        $orderby = 'review_rating DESC';
    } elseif ($_GET['orderby'] === 'date') {
        $orderby = 'review_date DESC';
    } elseif ($_GET['orderby'] === 'comments') {
        $orderby = 'review_comment ASC'; // Ordenar comentarios en orden alfabético ascendente
    }
}

// Consulta para obtener reseñas ordenadas según el criterio seleccionado
$query = 'SELECT
    review_movie_id, review_date, reviewer_name, review_comment,
    review_rating
FROM
    reviews
WHERE
    review_movie_id = ' . $_GET['movie_id'] . '
ORDER BY
    ' . $orderby;

$result = mysqli_query($db, $query) or die(mysql_error($db));

// retrieve information
$query = 'SELECT
        movie_name, movie_year, movie_director, movie_leadactor,
        movie_type, movie_running_time, movie_cost, movie_takings
    FROM
        movie
    WHERE
        movie_id = ' . $_GET['movie_id'];
$result = mysqli_query($db, $query) or die(mysqli_error($db));

$row = mysqli_fetch_assoc($result);
$movie_name         = $row['movie_name'];
$movie_director     = get_director($row['movie_director']);
$movie_leadactor    = get_leadactor($row['movie_leadactor']);
$movie_year         = $row['movie_year'];
$movie_running_time = $row['movie_running_time'] .' mins';
$movie_takings      = $row['movie_takings'] . ' million';
$movie_cost         = $row['movie_cost'] . ' million';
$movie_health       = calculate_differences($row['movie_takings'],
                          $row['movie_cost']);

// display the information
echo <<<ENDHTML
<html>
 <head>
 <style>
 .even-row {
     background-color: #0000FF; /* Azul para filas pares */
 }

 .odd-row {
     background-color: #FF0000; /* Rojo para filas impares */
 }
</style>


  <title>Details and Reviews for: $movie_name</title>
 </head>
 <body>
  <div style="text-align: center;">
   <h2>$movie_name</h2>
   <h3><em>Details</em></h3>
   <table cellpadding="2" cellspacing="2"
    style="width: 70%; margin-left: auto; margin-right: auto;">
    <tr>
     <td><strong>Title</strong></strong></td>
     <td>$movie_name</td>
     <td><strong>Release Year</strong></strong></td>
     <td>$movie_year</td>
    </tr><tr>
     <td><strong>Movie Director</strong></td>
     <td>$movie_director</td>
     <td><strong>Cost</strong></td>
     <td>$$movie_cost<td/>
    </tr><tr>
     <td><strong>Lead Actor</strong></td>
     <td>$movie_leadactor</td>
     <td><strong>Takings</strong></td>
     <td>$$movie_takings<td/>
    </tr><tr>
     <td><strong>Running Time</strong></td>
     <td>$movie_running_time</td>
     <td><strong>Health</strong></td>
     <td>$movie_health<td/>
    </tr><tr>
     <td><strong>Average Rating</strong></td>
     <td>$vidana_rating</td>
     <td></td>
     <td></td>
    </tr>
   </table>
ENDHTML;

// retrieve reviews for this movie
$query = 'SELECT
        review_movie_id, review_date, reviewer_name, review_comment,
        review_rating
    FROM
        reviews
    WHERE
        review_movie_id = ' . $_GET['movie_id'] . '
    ORDER BY
        review_date DESC';

$result = mysqli_query($db, $query) or die(mysql_error($db));

// display the reviews
echo <<<ENDHTML
<h3><em>Reviews</em></h3>
<table cellpadding="2" cellspacing="2" style="width: 90%; margin-left: auto; margin-right: auto;">
    <tr>
    <th style="width: 7em;"><a href="?movie_id={$_GET['movie_id']}&orderby=date">Date</a></th>
    <th style="width: 10em;"><a href="?movie_id={$_GET['movie_id']}&orderby=rating">Reviewer</a></th>
    <th><a href="?movie_id={$_GET['movie_id']}&orderby=comments">Comments</a></th>
    <th style="width: 5em;"><a href="?movie_id={$_GET['movie_id']}&orderby=rating">Rating</a></th>
    
    </tr>
ENDHTML;

$even_row = false; // Variable para rastrear filas pares e impares

while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['review_date'];
    $name = $row['reviewer_name'];
    $comment = $row['review_comment'];
    $rating = generate_ratings($row['review_rating']);

    // Alternar clases CSS para filas pares e impares
    $row_class = $even_row ? 'even-row' : 'odd-row';
    $even_row = !$even_row; // Alternar entre pares e impares

    echo <<<ENDHTML
    <tr class="$row_class">
      <td style="vertical-align:top; text-align: center;">$date</td>
      <td style="vertical-align:top;">$name</td>
      <td style="vertical-align:top;">$comment</td>
      <td style="vertical-align:top;">$rating</td>
    </tr>
ENDHTML;
}


echo <<<ENDHTML
  </table>
  <h1>Formulario</h1>
  <form action="Ejercicios1234.php?movie_id={$_GET['movie_id']}" method="post">
  <label for="autor">Autor:</label>
  <input type="text" id="autor" name="autor">

  <label for="descripcion">Descripcion:</label>
  <textarea id="descripcion" name="descripcion" rows="4"></textarea>

  <label for="estrellas">Valoración:</label>
  <select id="estrellas" name="estrellas">
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
  </select>
  <input type="submit" name="enviar" value="Enviar">
  </form>
 </body>
</html>
ENDHTML;

?>