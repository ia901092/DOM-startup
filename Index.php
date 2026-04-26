<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Articles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }
        table.newspaper {
            border: 2px solid #333;
            border-collapse: collapse;
            background-color: #e8f5e9;
            margin-bottom: 16px;
        }
        table.newspaper td {
            border: 1px solid #333;
            padding: 8px;
            vertical-align: top;
        }
        table.articles {
            border: 2px solid #333;
            border-collapse: collapse;
            background-color: #ffffff;
        }
        table.articles td {
            border: 1px solid #333;
            padding: 8px;
            vertical-align: top;
            width: 220px;
        }
        td.news {
            background-color: #bbdefb;
        }
        td.review {
            background-color: #fff9c4;
        }
        .story p {
            border: 1px solid #555;
            padding: 5px 8px;
            margin: 4px 0;
            background-color: #fafafa;
            box-shadow: 1px 1px 4px #bbb;
        }
        h3 {
            font-family: Arial, sans-serif;
            font-size: 15px;
            font-weight: bold;
            margin: 4px 0;
        }
        p {
            font-family: Arial, sans-serif;
            font-size: 12px;
            font-weight: normal;
            margin: 0;
        }
    </style>
</head>
<body>
 
<?php
$selected = (isset($_GET['paper']) && $_GET['paper'] !== "") ? $_GET['paper'] : "Morning_Edition";
?>
 
<form action="" method="get">
    <select name="paper">
<?php
// Papers XML: <NEWSPAPER NAME='Times' TYPE='Morning_Edition'>
$papersDoc = new DOMDocument();
$papersDoc->load("https://wwwlab.webug.se/examples/XML/articleservice/papers/");
 
$papers = $papersDoc->getElementsByTagName("NEWSPAPER");
 
foreach ($papers as $paper) {
    $type = $paper->getAttribute("TYPE");
    $name = $paper->getAttribute("NAME");
    $isSelected = ($type === $selected) ? " selected" : "";
    echo "        <option value=\"" . htmlspecialchars($type) . "\"" . $isSelected . ">" . htmlspecialchars($name) . "</option>\n";
}
?>
    </select>
    <input type="submit" value="Visa">
</form>
 
<?php
$url = "https://wwwlab.webug.se/examples/XML/articleservice/articles/?paper=" . urlencode($selected);
 
$dom = new DOMDocument();
$dom->load($url);
 
// Articles XML uses uppercase tags (confirmed from SAX response.php)
$newspapers = $dom->getElementsByTagName("NEWSPAPER");
 
foreach ($newspapers as $newspaper) {
    echo "<table class=\"newspaper\">\n";
    echo "    <tbody>\n";
 
    // Row 1: newspaper attributes (iterate)
    echo "        <tr>\n";
    echo "            <td>\n";
    foreach ($newspaper->attributes as $attr) {
        echo "                " . htmlspecialchars($attr->name) . ": " . htmlspecialchars($attr->value) . "<br>\n";
    }
    echo "            </td>\n";
    echo "        </tr>\n";
 
    // Row 2: row layout - one tr per article (version C)
    echo "        <tr>\n";
    echo "            <td>\n";
    echo "                <table class=\"articles\">\n";
    echo "                    <tbody>\n";
 
    $articles = $newspaper->getElementsByTagName("ARTICLE");
 
    foreach ($articles as $article) {
        $type = strtolower($article->getAttribute("TYPE"));
        echo "                        <tr>\n";
        echo "                            <td class=\"" . htmlspecialchars($type) . "\">\n";
 
        // Article attributes (iterate)
        foreach ($article->attributes as $attr) {
            echo "                                " . htmlspecialchars($attr->name) . ": " . htmlspecialchars($attr->value) . "<br>\n";
        }
 
        // Story
        $stories = $article->getElementsByTagName("STORY");
        foreach ($stories as $story) {
            echo "                                <div class=\"story\">\n";
 
            foreach ($story->childNodes as $node) {
                if ($node->nodeType !== XML_ELEMENT_NODE) {
                    continue;
                }
                $nodeName = strtoupper($node->nodeName);
                if ($nodeName === "HEADING") {
                    echo "                                    <h3>" . htmlspecialchars($node->textContent) . "</h3>\n";
                } elseif ($nodeName === "TEXT") {
                    echo "                                    <p>" . htmlspecialchars($node->textContent) . "</p>\n";
                }
                // COMMENT is ignored
            }
 
            echo "                                </div>\n";
        }
 
        echo "                            </td>\n";
        echo "                        </tr>\n";
    }
 
    echo "                    </tbody>\n";
    echo "                </table>\n";
    echo "            </td>\n";
    echo "        </tr>\n";
    echo "    </tbody>\n";
    echo "</table>\n";
}
?>
 
</body>
</html>
 