<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>M-RPG - Login</title>
        <link href="../styles/main.css" rel="stylesheet" type="text/css">
        <?php
            $extraPath = "../";
            require "../imports/loginCheck.php";
            $first = true;
            foreach(glob("styles/*.css") as $file)
            {
                if(!$first)
                    echo "\t";
                echo '<link href="' . $file . '" rel="stylesheet" type="text/css">' . "\r\n\t";
                $first = false;
            }
        ?></head>
    <body>
        <?php
            require "../imports/nav.php";
        ?>
        <main>
            <h1>Leaderboards</h1>
<?php
                $params = [(object)["label" => "Level", "short" => "level"], (object)["label" => "Money", "short" => "money"]];
                $params = array_merge($params, json_decode(file_get_contents("../api/data/playerStats.json")));
                foreach($params as $element)
                {
                    $apiResult = callApi("../api/players?order=" . $element->short . "-desc", "GET", ["Result-Count: 10"]);
                    echo <<< END
            <div>
                <h2>$element->label</h2>
                <table>

END;
                    $index = 1;
                    foreach(json_decode($apiResult->content) as $line)
                    {
                        $selected = ((isset($_COOKIE["username"]) && $_COOKIE["username"] == $line->username) ? ' class="selected"' : "");
                        echo <<< END
                    <tr$selected>
                        <td>$index</td>
                        <td>$line->username</td>
                    </tr>

END;
                        $index++;
                    }
                    echo <<< END
                </table>
            </div>

END;
                }
            ?>
        </main>
        <?php
            require "../imports/footer.html";
        ?>
    </body>
</html>