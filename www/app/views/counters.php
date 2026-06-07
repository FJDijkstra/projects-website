<div class="text-center m-4 h-100">
<h3>Kies een counter</h3>
<div class="h-50 overflow-y-scroll mb-5">
<div class="d-flex flex-column justify-content-center">
<?php
    foreach ($counters as $counter) {
        echo "<div class='counter-bar btn btn-primary d-flex flex-row m-1'>";
        echo "<a href='/counter?counter=$counter->id' class='counter-name w-75 h-100 m-2'>$counter->name</a>";
        echo "<div class='counter-value w-25 bg-white m-2'>$counter->amount</div>";
        echo "<a onclick='deleteCounterButton($counter->id)' class='btn btn-danger m-2'><i class='fs-6 fa fa-trash'></i></a>";
        echo "</div>";
    }
?>
</div>
</div>
<h3 class="mb-0">Maak een counter aan</h3>
<form action="/counters" method="get" class="form-group p-2">
    <label  class=" m-1 my-auto" for="countername">Voeg een counter toe:</label>
    <div class="d-flex">
        <input type="text" id="countername" name="countername" placeholder="type hier je naam" class='form-control m-2' minlength="1" maxlength="20">
        <input class='btn btn-primary m-2' type="submit" value="Toevoegen">
    </div>
</form>
</div>

<script>
    function deleteCounterButton(id) {
        if (confirm("Weet je zeker dat je deze counter wilt verwijderen?")) {
            window.location.href = "/counters?delete=" + id;
        }
    }
</script>