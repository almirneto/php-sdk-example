<?php

require_once 'verifica.php';

$obj_verifica = new Verifica('COLOQUE-AQUI-A-API-KEY');

?>
<html>
	<head>
		<meta charset="utf-8"/>
	</head>
	<body>
		<form name="F" method="POST">
		<script src="http://verifica.la/static/verificala.sdk.1.0.js"></script>
		<div verifica-form="<?php echo $obj_verifica->getPublicKey();?>">
		</div>

		<div><input type="submit" value="AO DIGITAR O TOKEN, CLIQUE AQUI VALIDAR"></div>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$telefone_valido = false;
	try {
		$telefone_valido = $obj_verifica->validateToken($_POST['verificalaCountryCode'], $_POST["verificalaPhoneNumber"], $_POST["verificalaToken"]);
	} catch(Exception $e) {
		echo "<hr>";
		echo "EXCEPTION:" . $e->getMessage();
		echo "<hr>";
	}

	?><div>Telefone válido? <?php echo ($telefone_valido)?"SIM":"NÃO";?></div><hr><?php
}
?>

		</form>
	</body>
</html>