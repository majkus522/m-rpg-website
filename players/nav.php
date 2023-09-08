<nav>
                <a href="?tab=panel">Panel</a>
<?php
                    $apiResult = callApi("../api/endpoints/skills/$_COOKIE[username]/statusFake", "GET", array("Session-Key: $_COOKIE[session]", "Session-Type: website"));
                    if($apiResult->code >= 200 && $apiResult->code < 300)
                        echo "\t\t\t\t" . '<a href="?tab=statusFake">Fake Status</a>' . "\r\n";
                ?>
                <a href="?tab=skills">Skills</a>
                <a href="?tab=settings">Settings</a>
            </nav>
