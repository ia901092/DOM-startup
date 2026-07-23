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
            border-left: 6px solid #1976d2;
            border-top: 3px solid #1976d2;
        }

        td.review {
            background-color: #fff9c4;
            border-left: 6px solid #f9a825;
            border-top: 3px solid #f9a825;
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
if (isset($_GET['paper']) && $_GET['paper'] !== "") {
    $selected = $_GET['paper'];
} else {
    $selected = "Morning_Edition";
}
?>

<form method="get">
    <select name="paper">
<?php
$papersDoc = new DOMDocument();
$papersDoc->load(
    "https://wwwlab.webug.se/examples/XML/articleservice/papers/"
);

$papers = $papersDoc->getElementsByTagName("NEWSPAPER");

foreach ($papers as $paper) {
    $type = $paper->getAttribute("TYPE");
    $name = $paper->getAttribute("NAME");

    if ($type === $selected) {
        echo "        <option value=\"" . $type .
             "\" selected>" . $name . "</option>\n";
    } else {
        echo "        <option value=\"" . $type .
             "\">" . $name . "</option>\n";
    }
}
?>
    </select>

    <input type="submit" value="Visa">
</form>

<?php
$url =
    "https://wwwlab.webug.se/examples/XML/articleservice/articles/?paper=" .
    $selected;

$dom = new DOMDocument();
$dom->load($url);

$newspapers = $dom->getElementsByTagName("NEWSPAPER");

foreach ($newspapers as $newspaper) {
    echo "<table class=\"newspaper\">\n";
    echo "    <tbody>\n";

    echo "        <tr>\n";
    echo "            <td>\n";

    foreach ($newspaper->attributes as $attr) {
        echo "                " . $attr->name . ": " .
             $attr->value . "<br>\n";
    }

    echo "            </td>\n";
    echo "        </tr>\n";

    echo "        <tr>\n";
    echo "            <td>\n";
    echo "                <table class=\"articles\">\n";
    echo "                    <tbody>\n";
    echo "                        <tr>\n";

    foreach ($newspaper->childNodes as $article) {
        if (strtoupper($article->nodeName) !== "ARTICLE") {
            continue;
        }

        $type = strtolower($article->getAttribute("TYPE"));

        echo "                            <td class=\"" .
             $type . "\">\n";

        foreach ($article->attributes as $attr) {
            echo "                                " .
                 $attr->name . ": " .
                 $attr->value . "<br>\n";
        }

        foreach ($article->childNodes as $node) {
            $nodeName = strtoupper($node->nodeName);

            if ($nodeName === "HEADING") {
                echo "                                <h3>" .
                     $node->textContent . "</h3>\n";
            } elseif ($nodeName === "STORY") {
                echo "                                <div class=\"story\">\n";

                foreach ($node->childNodes as $child) {
                    $childName = strtoupper($child->nodeName);

                    if ($childName === "HEADING") {
                        echo "                                    <h3>" .
                             $child->textContent . "</h3>\n";
                    } elseif ($childName === "TEXT") {
                        echo "                                    <p>" .
                             $child->textContent . "</p>\n";
                    }
                }

                echo "                                </div>\n";
            }
        }

        echo "                            </td>\n";
    }

    echo "                        </tr>\n";
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