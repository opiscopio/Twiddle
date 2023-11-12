<?php 
session_start();

	include("connection.php");
	include("functions.php");

	$user_data = check_login($con);

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Twiddle</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>

<!--<a href="logout.php">Logout</a>-->

<div class="main">

	<div id="logo">
		<img src="TwiddleLogo.png" alt="Twiddle Website Logo">
	</div>

	<div id="error">
		<input class="output-field" type="text" id="errortext" disabled>
	</div>

	<div id="fields">	
		<input class="input-field INPUT" type="text" oninput="validateInput(this)" id="input1" maxlength="2">
		<input class="input-field INPUT" type="text" oninput="validateInput(this)" id="input2" maxlength="2">
		<input class="input-field INPUT" type="text" oninput="validateInput(this)" id="input3" maxlength="2">
		<input class="input-field INPUT" type="text" oninput="validateInput(this)" id="input4" maxlength="2">
		<input class="input-field INPUT" type="text" oninput="validateInput(this)" id="input5" maxlength="2">
		<input class="input-field INPUT" type="text" oninput="validateInput(this)" id="input6" maxlength="2">
		<input class="input-field INPUT" type="text" oninput="validateInput(this)" id="input7" maxlength="2">
		<input class="input-field INPUT" type="text" oninput="validateInput(this)" id="input8" maxlength="2">
		<input class="input-field INPUT" type="text" oninput="validateInput(this)" id="input9" maxlength="2">
		<input class="input-field INPUT" type="text" oninput="validateInput(this)" id="input10" maxlength="2">
	</div>

	<div id="button">
		<button id="recommendBet" onclick="processInput()"></button>
	</div>

	<div id="numbers">
		<textarea class="output-field RESULTS" id="shownumbers" disabled></textarea>
		<textarea class="output-field RESULTS" id="showdozens" disabled></textarea>
		<textarea class="output-field RESULTS" id="showonfire" disabled></textarea>
	</div>

	<div class="layoutDiv">
		<img src="Layout.png" alt="Layout" class="layout">

		<div class="tokens">
			<img src="token.png" alt="0306" class="token">
			<img src="token.png" alt="0609" class="token">
			<img src="token.png" alt="0912" class="token">
			<img src="token.png" alt="1215" class="token">
			<img src="token.png" alt="1518" class="token">
			<img src="token.png" alt="1821" class="token">
			<img src="token.png" alt="2124" class="token">
			<img src="token.png" alt="2427" class="token">
			<img src="token.png" alt="2730" class="token">
			<img src="token.png" alt="3033" class="token">
			<img src="token.png" alt="3336" class="token">
			<img src="token.png" alt="0205" class="token">
			<img src="token.png" alt="0508" class="token">
			<img src="token.png" alt="0811" class="token">
			<img src="token.png" alt="1114" class="token">
			<img src="token.png" alt="1417" class="token">
			<img src="token.png" alt="1720" class="token">
			<img src="token.png" alt="2023" class="token">
			<img src="token.png" alt="2326" class="token">
			<img src="token.png" alt="2629" class="token">
			<img src="token.png" alt="2932" class="token">
			<img src="token.png" alt="3235" class="token">
			<img src="token.png" alt="0104" class="token">
			<img src="token.png" alt="0407" class="token">
			<img src="token.png" alt="0710" class="token">
			<img src="token.png" alt="1013" class="token">
			<img src="token.png" alt="1316" class="token">
			<img src="token.png" alt="1619" class="token">
			<img src="token.png" alt="1922" class="token">
			<img src="token.png" alt="2225" class="token">
			<img src="token.png" alt="2528" class="token">
			<img src="token.png" alt="2831" class="token">
			<img src="token.png" alt="3134" class="token">
		</div>
	</div>
</div>


<script>

	// Function to set focus on the first input field when the page loads
	function focusFirstInputField() {
		document.getElementById('input1').focus();
	}

	function hideTokens() {
		const tokens = document.querySelectorAll('.token');
		tokens.forEach(token => {
			token.style.visibility = 'hidden';
		});
	}

	window.addEventListener('load', focusFirstInputField);
	window.addEventListener('load', hideTokens);

	document.addEventListener('keydown', function (event) {

		if (event.key === "Tab") {
			event.preventDefault(); // Prevent default tab behavior
			const inputFields = document.querySelectorAll('.input-field');
			const focusedInput = document.activeElement;
			const index = Array.from(inputFields).indexOf(focusedInput); // Find the index of the currently focused input
			const nextIndex = (index + 1) % inputFields.length; // Focus on the next input or wrap back to the first one
			inputFields[nextIndex].focus();
		}

		if (event.key === "Enter" || event.key === "Return") {
			event.preventDefault(); // Prevent default Enter key behavior
			document.getElementById('recommendBet').click(); // Trigger the button click event
		}
	});


	// Function to check if the input is a valid number between 0 and 36
	function isValidNumber(input) {
		const value = parseInt(input.value);
		return !isNaN(value) && value >= 0 && value <= 36;
	}

	// Function to validate input
	function validateInput(input) {

		if (!isValidNumber(input)) {
			document.getElementById('errortext').value = "You can only enter numbers between 0 and 36.";
			input.value = '';
		}
	}


    function processInput() {
        document.getElementById('errortext').value = '';

        const inputFields = document.querySelectorAll('.input-field'); // Get all input values
        const values = [];
        let isValid = true;
		let decenas = [];
		let unidades = [];
		const combinations = [];
		const onFire = [];
		let dozens = [];
		let half = [];

		inputFields.forEach(input => {
			const inputValue = input.value;

			if (inputValue.length === 1) {
				input.value = "0" + inputValue;
			}

			if (input.value === '') {
				isValid = false;
				document.getElementById('errortext').value = "You have not entered all the numbers.";
			} else if (!isValidNumber(input)) {
				isValid = false;
				document.getElementById('errortext').value = "You can only enter numbers between 0 and 36.";
			}
			values.push(input.value);
		});

		function processDigits() {

			let firstDozen = 0;
			let secondDozen = 0;
			let thirdDozen = 0;
			let firstHalf = 0;
			let secondHalf = 0;

			// Remove duplicates
			const uniqueUnidades = [...new Set(unidades)];
			const uniqueDecenas = [...new Set(decenas)];

			// Create an array of all digits from 0 to 9 and 0 to 3
			const allUnidades = Array.from({ length: 10 }, (_, i) => i.toString());
			const allDecenas = ['0', '1', '2', '3'];

			// Find the missing digits by filtering out the ones that are already
			const missingUnidades = allUnidades.filter(unidad => !uniqueUnidades.includes(unidad));
			const missingDecenas = allDecenas.filter(decena => !uniqueDecenas.includes(decena));

			// Add the missing digits
			const updatedUnidades = [...uniqueUnidades, ...missingUnidades];
			const updatedDecenas = [...uniqueDecenas, ...missingDecenas];

			// Remove the first 5 and 2 values
			const finalUnidades = updatedUnidades.slice(5);
			const finalDecenas = updatedDecenas.slice(2);

			unidades = finalUnidades;
			decenas = finalDecenas;

			for (const decena of finalDecenas) {

				for (const unidad of finalUnidades) {

					const combinationValue = decena + unidad;
					const onFireValue = parseInt(unidad + decena, 10);
					const onFirePlus = onFireValue + 1;
					const onFireMinus = onFireValue - 1;


					if (parseInt(combinationValue) >= 0 && parseInt(combinationValue) <= 36) {
						combinations.push(combinationValue);
						
						if (parseInt(combinationValue) <= 12) {
							firstDozen = firstDozen + 1;
							firstHalf = firstHalf + 1;

						} else if (parseInt(combinationValue) <= 24) {
							secondDozen = secondDozen + 1;

							if (parseInt(combinationValue) <= 18){
								firstHalf = firstHalf + 1;

							} else {
								secondHalf = secondHalf + 1;
							}

						} else {
							thirdDozen = thirdDozen + 1;
							secondHalf = secondHalf + 1;
						}
					}

					if (onFireValue >= 0 && onFireValue <= 36 && !onFire.includes(unidad + decena)) {
						onFire.push(unidad + decena);
					}

					if (onFireValue !== 36 && onFirePlus >= 0 && onFirePlus <= 36 && !onFire.includes((onFirePlus < 10 ? "0" : "") + onFirePlus)) {
						onFire.push((onFirePlus < 10 ? "0" : "") + onFirePlus);
					}

					if (onFireValue !== 0 && onFireMinus >= 0 && onFireMinus <= 36 && !onFire.includes((onFireMinus < 10 ? "0" : "") + onFireMinus)) {
						onFire.push((onFireMinus < 10 ? "0" : "") + onFireMinus);
					}
				}
			}

			if (thirdDozen >= firstDozen && thirdDozen >= secondDozen) {

				if (secondDozen >= firstDozen) {
					dozens = "2nd, 3rd";

				} else {
					dozens = "1st, 3rd";
				}

			} else if (secondDozen >= firstDozen && secondDozen >= thirdDozen) {
				dozens = "1st, 2nd";

			} else {
				dozens = "1st, 3rd";
			}

			half = firstHalf >= secondHalf ? "1-18" : "19-36";
		}

		if (isValid) {
			decenas = values.map(value => value.charAt(0));
			unidades = values.map(value => value.charAt(1));

			hideTokens();

			const result = processDigits();

			combinations.sort();
			onFire.sort();

			var imgElements = document.querySelectorAll('.token');

			for (var i = 0; i < imgElements.length; i++) {
				var img = imgElements[i];
				var altValue = img.getAttribute('alt');

				console.log("Checking alt value: " + altValue);
				console.log("First Two Characters: " + altValue.substring(0, 2));
				console.log("Last Two Characters: " + altValue.substring(2));

				if (combinations.includes(altValue.substring(0, 2)) || combinations.includes(altValue.substring(2))) {
					img.style.visibility = 'visible';
				}
			}

			function generateLines(array) {
				const groupedLines = {};

				for (const value of array) {
					const firstDigit = value.charAt(0);
					if (groupedLines[firstDigit] === undefined) {
						groupedLines[firstDigit] = [];
					}
					groupedLines[firstDigit].push(value);
				}

				const combinedLines = Object.keys(groupedLines)
				.map(key => groupedLines[key].join(' '))
				.filter(line => line.trim() !== ''); // Filter out empty lines

				return combinedLines.join('\n');
			}

			document.getElementById('shownumbers').value = generateLines(combinations);
			document.getElementById('showdozens').value = dozens + '\n' + half;
			document.getElementById('showonfire').value = generateLines(onFire);
		}

		focusFirstInputField();
	}

</script>
</body>
</html>
