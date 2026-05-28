<?php
function para_decimal($num, $base_e){
    $dec = 0;
    $pot = 0;
    $num = strrev($num); 
    for ($i = 0; $i < strlen($num); $i++){
        $caracter = $num[$i];
        if (is_numeric($caracter)){
            $valor = intval($caracter);
        } else {
            $valor = ord(strtoupper($caracter)) - 55; 
        }
        $dec = $dec + $valor * pow($base_e, $pot);
        $pot++;
    }
    return $dec;
}

function de_decimal($dec, $base_s){
    if ($dec == 0) return "0";
    $todos_caracteres = "0123456789ABCDEFGHIJKLMNOPQRSTUV";
    $result = "";
    while ($dec > 0){
        $resto = $dec % $base_s;
        $result = $todos_caracteres[$resto] . $result;
        $dec = intdiv($dec, $base_s);
    }
    return $result;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num = strtoupper($_POST['numero']);
    $base_e = intval($_POST['base_entrada']);
    $base_s = intval($_POST['base_saida']);
    $erro = "";

    if ($base_e < 2 || $base_e > 32 || $base_s < 2 || $base_s > 32) {
        $erro = "Erro: As bases devem estar entre 2 e 32.";
    }

    if (!$erro) {
        $todos_caracteres = "0123456789ABCDEFGHIJKLMNOPQRSTUV";
        $caracteres_validos = substr($todos_caracteres, 0, $base_e);

        for ($i = 0; $i < strlen($num); $i++) {
            if (strpos($caracteres_validos, $num[$i]) === false) {
                $erro = "Erro: O caractere '{$num[$i]}' é inválido para a base $base_e. Caracteres permitidos: $caracteres_validos";
                break;
            }
        }
    }

    if (!$erro) {
        $dec = para_decimal($num, $base_e);
        $result = de_decimal($dec, $base_s);
    }
}
?>
<html>
    <meta charset="UTF-8">
    <header>
        <title style="color: midnightblue; font-size:40px">Conversor de Bases</title>
    </header>
    <body bgcolor="cornflowerblue">
	    <center>
        <p style="color: midnightblue; font-size: 60px">Conversor de Bases</p>
		</center>
        <br><br>
        <p style="right: 500px; top: 50px; position: absolute; font-size: 50px; color: midnightblue">Robson a calculadora</p>
        <img src="calculadora.png" style="right: 200px; top: 200px; position: absolute" width="700" height="700">
        <form method="POST">
            <p style="line-height: 1.5; font-size:40px; color: midnightblue">Digite o número que será convertido</p>
            <input style="line-height: 2.5; font-size:16px" type="text" name="numero" required><br>
            <p style="line-height: 1.5; font-size:40px; color: midnightblue">Escolha a base de entrada</p>
            <input style="line-height: 2.5; font-size:16px" type="number" name="base_entrada" min="2" max="32" required><br>
            <p style="line-height: 1.5; font-size:40px; color: midnightblue">Escolha a base de saída</p>
            <input style="line-height: 2.5; font-size:16px" type="number" name="base_saida" min="2" max="32" required><br>
            <br><br>
            <button style="background-color: lightskyblue; font-color: midnightblue; font-size: 40px" type="submit">Converter</button>
        </form>
    </body>
</html>
<?php
if (isset($erro) && $erro) {
    echo "<h3 style='color: purple; font-size: 24px'>$erro</h3>";
} elseif (isset($result)) {
    echo "<h3 style='color: midnightblue; font-size: 40px'>Resultado: $result</h3>";
}
?>
