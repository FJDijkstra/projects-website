<?php if ($error != "") {
    echo "<p>error: $error</p>"; 
    }?>

<form action="/buzzer" class="form-group p-4">
    <label  class="m-2 my-auto" for="team">Welke groep ben je?</label>
    <div class="d-flex">
        <select name="team" id="team" class='form-select m-2'>
            <?php
            foreach ($teams as $team) {
                echo "<option value='$team->id'>$team->name</option>";
            }
            ?>
        </select>
        <input class='btn btn-primary m-2' type="submit" value="Meedoen">
    </div>
</form>

<form action="/teams" method="get" class="form-group p-4">
    <label  class=" m-2 my-auto" for="teamname">Voeg een groep toe:</label>
    <div class="d-flex">
        <input type="text" id="teamname" name="teamname" placeholder="type hier je naam" class='form-control m-2'>
        <input class='btn btn-primary m-2' type="submit" value="Toevoegen">
    </div>
</form>