<?php
/*
* @var string $teamname
* @var int $teamid
*/
?>


<script>
    window.onload = function () { 
            buzzerCooldown();
        }
</script>

<div class="d-flex flex-column align-items-center w-100 h-100 p-4">
    <?php echo "<h1>Team: $teamname</h1>"; ?>
    <form id='buzzerForm' class="d-flex align-items-center justify-content-center w-100 h-75">
        <input type="hidden" id="team" name="team" value="<?= $teamid ?>">
        <input type="hidden" id="buzzed" name="buzzed" value="1">
        <div id="buzzerBottom">
            <button disabled type="button" id="buzzerTop" name="buzzerTop" onclick="playBuzzerSound();"></button>
        </div>
    </form>
</div>