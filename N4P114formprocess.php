<?php
echo <<<ENDHTML
<html>
<head>
    <title>Calculadora Básica</title>
</head>
<body>
    <h1>Calculadora</h1>
    <form action="N4P115formprocess.php" method="post">
        <label for="num1">Número 1:</label>
        <input type="text" name="num1" id="num1" required><br><br>
        
        <label for="num2">Número 2:</label>
        <input type="text" name="num2" id="num2" required><br><br>

        <label for="operation">Operación:</label>
        <select name="operation" id="operation">
            <option value="suma">Suma</option>
            <option value="resta">Resta</option>
            <option value="multimultiplicacion">Multiplicación</option>
            <option value="division">División</option>
        </select><br><br>

        <input type="submit" value="Calcular">
    </form>
</body>
</html>
ENDHTML;
?>