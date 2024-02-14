<?php
    $apiResult = callApi("forum/" . $requestUrlPart[$urlIndex] . "?order=time", "GET");
?>
<div>
                <div class="author">
                    <?php echo $apiResult->content[0]->player; ?>
                </div>
                <div class="content">
                    <h2><?php echo $apiResult->content[0]->title; ?></h2>
                    <p><?php echo $apiResult->content[0]->text; ?></p>
                    <button>
                        <ion-icon name="thumbs-up"></ion-icon> <?php echo $apiResult->content[0]->likes; ?>

                    </button>
                    <div>
                        <?php echo $apiResult->content[0]->time; ?>

                    </div>
                </div>
            </div>
<?php
    for($index = 1; $index < sizeof($apiResult->content); $index++)
    {
        $element = $apiResult->content[$index];
        echo <<< END
            <div data-id="$element->id">
                <div class="author">
                    $element->player
                </div>
                <div class="content">
                    <p>$element->text</p>
                    <button>
                        <ion-icon name="thumbs-up"></ion-icon> $element->likes

                    </button>
                    <div>
                        $element->time

                    </div>
                    <button>
                        <ion-icon name="chatbox-ellipses-outline"></ion-icon> Comment
                    </button>
                </div>
            </div>

END;
    }
?>
            <script src="scripts/topic.js" defer></script>
            <script>
                let slug = "<?php echo $requestUrlPart[$urlIndex]; ?>";
            </script>
