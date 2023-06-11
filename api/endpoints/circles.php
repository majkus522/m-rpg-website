<?php
    switch($requestMethod)
    {
        case "GET":
            $headerPlayer = getHeader("Player");
            $headerPassword = getHeader("Password");
            if(isSingleGet())
            {
                $query = 'select * from `circles` where `slug` = "' . $requestUrlPart[$urlIndex + 1] . '" limit 1';
                $queryResult = connectToDatabase($query);
                if(empty($queryResult))
                    exitApi(404, "Circle doesn't exists");
                $circle = $queryResult[0];
                if($circle->private)
                {
                    if($headerPlayer == false)
                        exitApi(400, "Specify Player header");
                    if($headerPassword == false)
                        exitApi(400, "Specify Password header");
                    $query = 'select `username` from `players` where `id` = ' . $circle->player . ' limit 1';
                    $queryResult = connectToDatabase($query);
                    if(empty($queryResult))
                        exitApi(500, "Circle without creator");
                    if($queryResult[0]->username != $headerPlayer)
                        exitApi(403, "You can't see someones private circle");
                    if(!callApi("endpoints/players/" . $headerPlayer . "/logged", "GET", ["Password: " . $headerPassword])[0])
                        exitApi(401, "Wrong password");
                }
                $result = $circle;
            }
            else
            {
                $header = getHeader("Items");
                $limit = 50;
                $offset = 0;
                if($header != false)
                {
                    if(str_contains($header, "-"))
                    {
                        $part = explode("-", $header);
                        $offset = (int)$part[0];
                        $limit = (int)$part[1];
                    }
                    else
                        $limit = (int)$header;
                }
                if($headerPlayer != false && $headerPassword != false)
                {
                    if(!callApi("endpoints/players/" . $headerPlayer . "/logged", "GET", ["Password: " . $headerPassword])[0])
                        exitApi(401, "Wrong password");
                    $query = 'select * from `circles` where (!`private` or `player` = (select `id` from `players` where `username` = "' . $headerPlayer . '"))';
                }
                else
                    $query = 'select * from `circles` where !`private`';
                foreach($_GET as $key => $value)
                {
                    switch($key)
                    {
                        case "minMana":
                            $query = $query . ' and `mana` >= ' . $value;
                            break;

                        case "maxMana":
                            $query = $query . ' and `mana` <= ' . $value;
                            break;

                        case "olderThan":
                            $query = $query . ' and `creationDate` > "' . $value . '"';
                            break;

                        case "youngerThan":
                            $query = $query . ' and `creationDate` < "' . $value . '"';
                            break;
                    }
                }
                $query = $query . ' limit ' . $limit . ' offset ' . $offset;
                $result = connectToDatabase($query);
                if(empty($result))
                    exitApi(404, "Can't find any circles matching conditions");
                header("Items-Count: " . sizeof($result));
            }
            echo json_encode($result);
            break;

        case "OPTIONS":
            echo json_encode([
                "GET /api/endpoints/circles = select all circles (hidden data), doeasn't show private ones (player don't logged)",
                "   Items: {offset(optional)}-{limit} = specify number and offset of selected players (default[Items: 0-50])",
                "   ?minMana={minMana} = specify minimal mana usage of circle",
                "   ?maxMana={maxMana} = specify maxminal mana usage of circle",
                "   ?olderThan={date(rrrr-mm-dd hh:mm:ss)} = specify if circle is older than given date time",
                "   ?youngerThan={date(rrrr-mm-dd hh:mm:ss)} = specify if circle is younger than given date time",
                "GET /api/endpoints/circles = select all circles (hidden data), doeasn't show private ones (player logged)",
                "   (required)Player: {username} = specify player username",
                "   (required)Passowrd: {password} = send player password",
                "   Apply all parameters from endpoint above",
                "GET /api/endpoints/circles/{slug} = select circle with specified slug, doeasn't show private ones (player don't logged)",
                "GET /api/endpoints/circles/{slug} = select circle with specified slug, doeasn't show other players private ones (player logged)",
                "   (required)Player: {username} = specify player username",
                "   (required)Passowrd: {password} = send player password"
            ]);
            break;

        default:
            exitApi(501, "Method not implemented");
            return;
    }
?>