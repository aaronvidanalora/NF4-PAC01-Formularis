<?php


$input1 = $_POST['input1'];
$input2 = $_POST['input2'];
$input3 = $_POST['input3'];
$input4 = $_POST['input4'];
$input5 = $_POST['input5'];



echo <<<ENDHTML
  <html>
  <head></head>
  <body>
  <select id="seleccion" name="seleccion">
            <option value="input1">$input1</option>
            <option value="input2">$input2</option>
            <option value="input3">$input3</option>
            <option value="input4">$input4</option>
            <option value="input5">$input5</option>
        </select>
  </body>
  </html>
ENDHTML;


?>
