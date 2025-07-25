<?php
//------Constantes------
	const PRECIO_HOTEL = 60;
	const PRECIO_COCHE = 40;
	const DESC_COCHE_3 = -20;
	const DESC_COCHE_7 = -50;

//-----Init Variables------
$nits = 0;
$ciutat = "Selecciona un destino";
$diasCoche = 0;
$resHotel = $resAvio = $resCoche = 0;
$errors = '';
$finalCost = 0;
$err_msg = '';
	
//print_r($_POST); // DEBUG

if (isset($_POST['limpiar'])) {
}
elseif (isset($_POST['enviar'])) {

	try {
//		Validar noches
		$nits = $_POST['noches'] ?? null;
		if (!$nits || !is_numeric($nits) || $nits <= 0) {
			$errors = 'Noches: valor invalido<br>';
		} else {
			$resHotel = costeHotel($nits);
//			print_r($resHotel); // DEBUG
		}

//		Validar ciudad
		$ciutat = $_POST['ciudad'] ?? null;
		if (!$ciutat || $ciutat == 'Selecciona un destino') {
			$errors .= 'Debes seleccionar un destino<br>';
		} else {
			$resAvio = costeAvion($ciutat);
//			print_r($resAvio); // DEBUG
			if ($resAvio == null) { // Protector por si se manipula el valor del combo desde fuera
				$errors .= 'Destino invalido!!!<br>';
			}
		}
		
//		Validar coche
		$diasCoche = $_POST['coche'] ?? null;
		if ($diasCoche != 0) {
			if (is_numeric($diasCoche) && ($diasCoche <= $nits && $diasCoche > 0)) {
				$resCoche = costeCoche($diasCoche);
//				print_r($resCoche); // DEBUG
			} else {
				$errors .= ("Dias alquiler coche: Valor invalido");
			}
		}

		if ($resHotel > 0 && $resAvio != null && $errors === '') {
			$finalCost = $resHotel + $resAvio + ($resCoche ?? 0);
//			print_r($finalCost); //DEBUG
		}

		if (!empty($errors)) {
			throw new Exception($errors);
		}
	} catch (Exception $error) {
		$err_msg = $error->getMessage();
	}
}

//-----Funcions auxiliars-----

	function costeHotel($nits) {
		return $nits * PRECIO_HOTEL; 
	}

	function costeAvion($ciutat) {
		if ($ciutat == 'Madrid') return '150';
		if ($ciutat == 'Paris') return '250';
		if ($ciutat == 'Los Angeles') return '450';
		if ($ciutat == 'Roma') return '200';
		return null;
	}

	function costeCoche($diasCoche) {
		$res = $diasCoche * PRECIO_COCHE;
		if ($diasCoche > 2 && $diasCoche < 7) return $res + DESC_COCHE_3;
		if ($diasCoche > 6) return $res + DESC_COCHE_7;
		return $res;
		
	}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>PLA02</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/estilos.css">
</head>
<body>
	<main>
		<h1 class='centrar'>PLA02: COSTE HOTEL</h1>
		<br>
		<form method="post" action="#">
			<div class="row mb-3">
			    <label for="noches" class="col-sm-3 col-form-label">Número de noches:</label>
			    <div class="col-sm-9">
			      <input type="number" class="form-control" name="noches" id="noches" value="<?php echo $nits; ?>">
			    </div>
			</div>
			<div class="row mb-3">
			    <label for="ciudad" class="col-sm-3 col-form-label">Destino:</label>
			    <div class="col-sm-9">
					<select class="form-select" name="ciudad">
						<option value="Selecciona un destino" <?php echo ($ciutat == 'Selecciona un destino') ? 'selected' : ''; ?>>Selecciona un destino</option>
						<option value="Madrid" <?php echo ($ciutat == 'Madrid') ? 'selected' : ''; ?>>Madrid</option>
						<option value="Paris" <?php echo ($ciutat == 'Paris') ? 'selected' : ''; ?>>Paris</option>
						<option value="Los Angeles" <?php echo ($ciutat == 'Los Angeles') ? 'selected' : ''; ?>>Los Angeles</option>
						<option value="Roma" <?php echo ($ciutat == 'Roma') ? 'selected' : ''; ?>>Roma</option>
					</select>
				</div>
			</div>
			<div class="row mb-3">
			    <label for="coche" class="col-sm-3 col-form-label">Días alquiler coche (opcional):</label>
			    <div class="col-sm-9">
			      <input type="number" class="form-control" name="coche" id="coche" value="<?php echo $diasCoche; ?>">  
			    </div>
			</div>
			<label class="col-sm-3 col-form-label"></label>
		  	<button type="submit" class="btn btn-primary" name='enviar'>Enviar datos</button>
			<button type="submit" class="btn btn-success" name='limpiar'>Limpiar</button>
		  	<br><br>
		  	<div class="row mb-3">
			    <label class="col-sm-3 col-form-label">Coste total: </label>
			    <div class="col-sm-9">
			      <input type="number" class="form-control" name="total" id="total" disabled value="<?php echo $finalCost; ?>"> 
			    </div>
			</div><br>
			<span class='errores'><?php echo $err_msg; ?></span>
		</form>
	</main>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>