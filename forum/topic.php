<?php
    $headers = [];
    if($validLogin)
        $headers = ["Session-Key: " . $_COOKIE["session-key"], "Session-ID: " . $_COOKIE["session-id"], "Result-Count: 1"];
    $apiResult = callApi("forum/" . $requestUrlPart[$urlIndex] . "?order=time", "GET", $headers);
?>
<div data-id="<?php echo $apiResult->content[0]->id; ?>" class="main">
                <div class="author">
                    <?php echo $apiResult->content[0]->player; ?>

                </div>
                <div class="content">
                    <h2><?php echo $apiResult->content[0]->title; ?></h2>
                    <p><?php echo $apiResult->content[0]->text; ?></p>
                    <div>
                        <?php echo $apiResult->content[0]->time; ?>

                    </div>
                    <button>
                        <ion-icon name="<?php echo $apiResult->content[0]->liked ? "thumbs-up" : "thumbs-up-outline"; ?>"></ion-icon>
                        <p><?php echo $apiResult->content[0]->likes; ?></p>
                    </button>
                    <button>
                        Comment
                        <ion-icon role="img" class="md hydrated" name="chatbox-ellipses-outline"></ion-icon>
                    </button>
                </div>
            </div>
            <script src="scripts/topic.js" defer></script>
            <script>
                let slug = "<?php echo $requestUrlPart[$urlIndex]; ?>";
            </script>
