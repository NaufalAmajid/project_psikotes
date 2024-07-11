<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convert Unicode Escape Sequence to Symbols</title>
</head>
<body>
    <div id="symbol-container"></div>

    <script>
        // Unicode escape sequence string
        let unicodeString = "\u25fc\ufe0f\u25fc\ufe0f\u25fc\ufe0f\u2b1b\ufe0f";

        // Function to convert Unicode escape sequence to symbols
        function unicodeToChar(text) {
            return text.replace(/\\u([\d\w]{4})/gi, function(match, grp) {
                return String.fromCharCode(parseInt(grp, 16));
            });
        }

        // Convert and display the symbols
        let symbols = unicodeToChar(unicodeString);
        document.getElementById('symbol-container').innerText = symbols;
    </script>
</body>
</html>