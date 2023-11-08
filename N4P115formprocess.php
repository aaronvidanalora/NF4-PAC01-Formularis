<?php
$result = '';
    $num1 = $_POST['num1'] ?? 0;
    $num2 = $_POST['num2'] ?? 0;
    $operation = $_POST['operation'] ?? '';

    if ($operation === 'suma') {
        $result = $num1 + $num2;
    } elseif ($operation === 'resta') {
        $result = $num1 - $num2;
    } elseif ($operation === 'multiplicacion') {
        $result = $num1 * $num2;
    } elseif ($operation === 'division') {
        if ($num2 != 0) {
            $result = $num1 / $num2;
        }
    }

echo <<<ENDHTML
<p>Resultado: $result</p>
ENDHTML;
?>