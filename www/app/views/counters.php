<div class="text-center m-4">
<h3>Kies een counter</h3>
<?php
    foreach ($counters as $counter) {
        echo "<div class='btn btn-primary m-2'>";
        echo "<a href='/counter?counter=$counter->id' class='btn btn-primary m-2'>$counter->name</a>";
        echo "<a href='/counters?delete=$counter->id' class='btn btn-danger m-2'><i class='fs-6 fa fa-trash'></i></a>";
        echo "</div>";
    }
?>

<h3>Maak een counter aan</h3>
<form action="/counters" method="get" class="form-group p-4">
    <label  class=" m-2 my-auto" for="countername">Voeg een counter toe:</label>
    <div class="d-flex">
        <input type="text" id="countername" name="countername" placeholder="type hier je naam" class='form-control m-2' minlength="1" maxlength="20">
        <input class='btn btn-primary m-2' type="submit" value="Toevoegen">
    </div>
</form>
</div>