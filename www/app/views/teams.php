<div class="text-center m-4">
<h3>Speel mee als een groep</h3>
<form action="/buzzer" class="form-group p-4">
    <label  class="m-2 my-auto" for="team">Welke groep ben je?</label>
    <div class="d-flex">
        <select name="team" id="team" required class='form-select m-2'>
            <option disabled hidden selected value=''>Kies een team...</option>
            <?php
            foreach ($teams as $team) {
                echo "<option value='$team->id'>$team->name</option>";
            }
            ?>
        </select>
        <input class='btn btn-primary m-2' type="submit" value="Meedoen">
    </div>
</form>

<h3>Maak een groep aan</h3>
<form action="/buzzer/teams" method="get" class="form-group p-4">
    <label  class=" m-2 my-auto" for="teamname">Wat is je teamnaam:</label>
    <div class="d-flex">
        <input type="text" id="teamname" name="teamname" placeholder="Vul hier je naam in..." class='form-control m-2' minlength="1" maxlength="20">
        <input class='btn btn-primary m-2' type="submit" value="Toevoegen">
    </div>
</form>

<h3>Log in als spelleider</h3>
<form action="/buzzer/admin" class="form-group p-4">
    <label  class=" m-2 my-auto" for="teamname">Wachtwoord voor Spelleider:</label>
    <div class="d-flex">
        <input type="text" id="password" name="password" placeholder="Vul hier het wachtwoord in..." class='form-control m-2' minlength="1">
        <input class='btn btn-primary m-2' type="submit" value="Inloggen">
    </div>
</form>
</div>