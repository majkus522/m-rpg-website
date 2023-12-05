<nav>
                <a href="?tab=panel">Panel</a>
<?php
                    $apiResult = callApi("players/$_COOKIE[username]", "GET", ["Session-Key: $_COOKIE[session]", "Session-Type: website"]);
                    if($apiResult->content->guild != null)
                        echo "\t\t\t\t" . '<a href="?tab=guild">Guild</a>' . "\r\n";

                    $apiResult = callApi("skills/$_COOKIE[username]/statusFake", "GET", ["Session-Key: $_COOKIE[session]", "Session-Type: website"]);
                    if($apiResult->code >= 200 && $apiResult->code < 300)
                        echo "\t\t\t\t" . '<a href="?tab=statusFake">Fake Status</a>' . "\r\n";
                ?>
                <a href="?tab=skills">Skills</a>
                <a href="?tab=settings">Settings</a>
            </nav>
