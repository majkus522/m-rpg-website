<?php
    $headers = [];
    if($validLogin)
        $headers = ["Session-Key: " . $_COOKIE["session-key"], "Session-ID: " . $_COOKIE["session-id"], "Result-Count: 1"];
    $apiResult = callApi("forum/" . $requestUrlPart[$urlIndex] . "?order=time", "GET", $headers);
    if($apiResult->code > 300)
        header("Location: ../forum");
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
                        <ion-icon name="<?php echo isset($apiResult->content[0]->liked) && $apiResult->content[0]->liked ? "thumbs-up" : "thumbs-up-outline"; ?>"></ion-icon>
                        <p><?php echo $apiResult->content[0]->likes; ?></p>
                    </button>
<?php
                        if($validLogin)
                        {
                    ?>
                    <button>
                        Comment
                        <ion-icon name="chatbox-ellipses-outline"></ion-icon>
                    </button>
                    <button>
                        Delete
                        <ion-icon name="trash-outline"></ion-icon>
                    </button>
<?php
                        }
                    ?>
                </div>
            </div>
            <script>
                let slug = "<?php echo $requestUrlPart[$urlIndex]; ?>";
            </script>
