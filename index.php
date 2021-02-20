<?php
    ini_set('display_errors', 1);
    preg_match('/<title>(\w+)\s/', file_get_contents('https://it.wiktionary.org/wiki/Speciale:PaginaCasuale'), $matches)
?>
<html>
    <head>
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <style>
            body {
                font-size: 1.2em;
                font-family: 'Roboto', sans-serif;
                margin: 1em;
            }
            input {
                font-size: inherit;
                font-family: inherit;
                margin-right: 0.5em;
            }
            input[type="button"] {
                width: 6em;
            }
        </style>
        <script src="https://code.responsivevoice.org/responsivevoice.js"></script>
    </head>
    <body>
        <?php
            if (isset($_GET['g']) && isset($_GET['t'])) {
                echo '<div style="margin-bottom: 0.5em"><input type="button" value="ricomincia" onclick="restart()">punteggio: ' . $_GET['g'] . ' su ' . $_GET['t'] . '</div>';
            } else {
                $_GET['g'] = 0;
                $_GET['t'] = 0;
            }
        ?>
        <input type="button" value="ascolta" onclick="listen()">
        <input type="text" id="input">
        <span id="message" />
        <script>
            var input = document.getElementById("input");
            var message = document.getElementById("message");
            var attempt = 1;

            function check(input){
                if (input == "<?php echo $matches[1]; ?>") {
                    message.style.color = '#0c0';
                    message.innerHTML = 'giusto';
                    setTimeout(function(){
                        window.location.replace('.?g=' + (<?php echo $_GET['g']; ?> + 1) + '&t=' + (<?php echo $_GET['t']; ?> + 1));
                    }, 500);
                } else {
                    message.style.color = '#c00';
                    if (attempt < 3) {
                        message.innerHTML = 'riprova';
                        setTimeout(function(){ message.innerHTML = ''; }, 1000);
                        attempt += 1;
                    } else {
                        message.innerHTML = 'era <?php echo strtoupper($matches[1]); ?>';
                        setTimeout(function(){ 
                            window.location.replace('.?g=' + <?php echo $_GET['g']; ?> + '&t=' + (<?php echo $_GET['t']; ?> + 1));
                        }, 10000);
                    }
                }
            }

            function listen() {
                responsiveVoice.speak("<?php echo $matches[1]; ?>", "Italian Female", {rate: 0.85});
                input.focus();
            }
            listen();

            function restart() {
                window.location.replace('.');
            }

            input.addEventListener("keyup", function(event) {
              event.preventDefault();
              if (event.keyCode === 13) {
                check(input.value);
              }
            });
        </script>
    </body>
</html>