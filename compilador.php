
<?php
	if(isset($_POST['codigo'])){
		$codigo = $_POST['codigo'];
		$erros = 0;
		$ultimoCalculo = -1;
		$conteudoFinal = "";

		$codigo = str_replace(" ", "", $codigo);
		$codigo = trim($codigo);

		$linhas = explode(";", $codigo);

		foreach($linhas as $l){
			$l = str_replace(" ", "", $l);
			$l = trim($l);
			if(strlen($l) > 0 && Validar($l) == false)
				$erros++;
		}

		if(empty($_POST['codigo'])){
	    	$conteudoFinal .= "\n"."";
	    }else if($erros == 0){ // Compilou
			foreach($linhas as $l){
				if(strpos($l, "escreva(") == false)
					$ultimoCalculo = Calcular($l);
				else
					$conteudoFinal .= $ultimoCalculo+"\n";
			}
			$conteudoFinal .= "\n"."COMPILADO COM SUCESSO!";
		} else {
			foreach($linhas as $l){
				if(strlen($l) > 0){
					if(Validar($l) == true)
						$conteudoFinal .= $l.";";
					else
						$conteudoFinal .= $l."; <-- ERRO";
				}
			}

			$conteudoFinal .= "\n"."FALHA NA COMPILACAO. ".$erros." erros encontrados";
		}
		
		echo "<h3>Exemplos:<br>";
		echo "<img src=exemplos1.png><br><br>";
		echo "<form method=\"post\">";
		echo "	<textarea name=\"codigo\" style=\"width: 100%; height: 300px;\">".$codigo."</textarea><br><br>";
		echo "	<button type=\"submit\">Compilar</button><br><br>";
		echo "	<textarea style=\"width: 100%; height: 300px;\" readonly>".$conteudoFinal."</textarea>";

		echo "</form>";
	} else {
		echo "<h3>Exemplos:<br><br>";
		echo "<img src=exemplos1.png><br><br>";
		echo "<form method=\"post\">";
		echo "	<textarea name=\"codigo\" style=\"width: 100%; height: 300px;\"></textarea><br><br>";
		echo "	<button type=\"submit\">Compilar</button><br><br>";
		echo "	<textarea style=\"width: 100%; height: 300px;\" readonly></textarea>";
		echo "</form>";
	}



	function Validar($linha){
		$operacao = -1;

		$linha = str_replace(" ", "", $linha);
		$linha = trim($linha);

		if(strpos($linha, "*") && strpos($linha, "*") < 2)
			return false;

		if(strpos($linha, ")") != strlen($linha)-1)
			return false;

		if(strpos($linha, "f=") == 0){ // Expressão
			if(strpos($linha, "(") == -1 || strpos($linha, ")") == -1)
				return false;

			if(strpos($linha, "cosseno(") > 0) // cosseno(D) ou cosseno(D+D)
				$operacao = 0;
			else if(strpos($linha, "seno(") > 0)
				$operacao = 1;
			else if(strpos($linha, "tangente(") > 0)
				$operacao = 2;
			else
				return false;

			$parenteses = substr($linha, strpos($linha, "(")+1, strpos($linha, ")")-strpos($linha, "(")-1);
			if(strlen($parenteses) == 0 || (strpos($parenteses, "+") != false && strpos($parenteses, "+") == 0) || strpos($parenteses, "+") == strlen($parenteses)-1)
				return false;
			return true;
		} else if(strpos($linha, "escreva(") == 0)
			return true;
		return false;
	}

	function Calcular($linha){
		$result = 0.0;
		$operacao = -1;
		$Dparenteses = 0;
		$Dfora = 0;

		$linha = str_replace(" ", "", $linha);
		$linha = trim($linha);

		if(strpos($linha, "*") >= 0){
			$Dfora = substr($linha, 2, strpos($linha, "*")-2);
		}

		if(strpos($linha, "cosseno(") > 0) // cosseno(D) ou cosseno(D+D)
			$operacao = 0;
		else if(strpos($linha, "seno(") > 0)
			$operacao = 1;
		else if(strpos($linha, "tangente(") > 0)
			$operacao = 2;

		$parenteses = substr($linha, strpos($linha, "(")+1, strpos($linha, ")")-strpos($linha, "(")-1);
		if(strpos($parenteses, "+") > 0){
			$D1 = floatval(substr($parenteses, 0, strpos($parenteses, "+")));
			$D2 = floatval(substr($parenteses, strpos($parenteses, "+")+1, strlen($parenteses)-strpos($parenteses, "+")));
			$Dparenteses = $D1+$D2;
		} else
			$Dparenteses = $parenteses;

		$DparentesesNumero = floatval($Dparenteses);
		if($operacao == 0) // Cosseno
			$result = cos($DparentesesNumero);
		else if($operacao == 1) // Seno
			$result = sin($DparentesesNumero);
		else
			$result = tan($DparentesesNumero);


		if($Dfora != 0)
			return $Dfora*$result;
		else
			return $result;
	}
?>


