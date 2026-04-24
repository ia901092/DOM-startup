<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newspaper Viewer</title>
</head>
<body>
    <h1>The Daily Reader</h1>
</body>
</html>
<?php
    $xmlFilePapper = file_get_contents('https://wwwlab.webug.se/examples/XML/articleservice/papers/');
    $domPapers = new DOMDocument;
    $domPapers->preserveWhiteSpace = FALSE;
    $domPapers->loadXML($xmlFilePapper);

    // default paper if nothing is posted
    if (isset($_POST['sender'])) {
        $paper = $_POST['sender'];
    } else {
        $paper = "Morning_Edition";
    }

    $xmlArticles = file_get_contents("https://wwwlab.webug.se/examples/XML/articleservice/articles?paper=" . $paper);
    $domArticles = new DOMDocument;
    $domArticles->preserveWhiteSpace = FALSE;
    $domArticles->loadXML($xmlArticles);
?> 