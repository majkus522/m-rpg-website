<div>
                <form>
                    <input type="text" name="search" <?php if(isset($_GET["search"])) echo 'value="' . $_GET["search"] . '"' ?>>
                    <input type="submit" value="Search">
                </form>
            </div>
            <div>
                <h2>Recent posts</h2>
<?php
                    $search = isset($_GET["search"]) ? "&search=" . $_GET["search"] : "";
                    $apiResult = callApi("forum?order=time-desc$search", "GET", ["Return-Count: 10"]);
                    if($apiResult->code < 300)
                        foreach($apiResult->content as $element)
                        {
                            echo <<< END
                <a href="$element->slug">
                    <h3>$element->title</h3>
                    <div>
                        <ion-icon name="thumbs-up"></ion-icon> $element->likes
                    </div>
                    <div>
                        <ion-icon name="chatbox-ellipses-outline"></ion-icon> $element->comments
                    </div>
                    <div>
                        $element->time
                    </div>
                </a>

END;
                        }
                ?>
            </div>
            <div>
                <h2>Most popular posts</h2>
<?php
                    $apiResult = callApi("forum?order=likes-desc$search", "GET", ["Return-Count: 10"]);
                    if($apiResult->code < 300)
                        foreach($apiResult->content as $element)
                        {
                            echo <<< END
                <a href="$element->slug">
                    <h3>$element->title</h3>
                    <div>
                        <ion-icon name="thumbs-up"></ion-icon> $element->likes
                    </div>
                    <div>
                        <ion-icon name="chatbox-ellipses-outline"></ion-icon> $element->comments
                    </div>
                    <div>
                        $element->time
                    </div>
                </a>

END;
                        }
                ?>
            </div>
<?php
                if($validLogin)
                {
            ?>
            <form>
                <h2>Create new post</h2>
                <input type="text" placeholder="Title">
                <textarea placeholder="Content"></textarea>
                <p class="error"></p>
                <input type="button" value="Create">
            </form>
<?php
                }
            ?>
