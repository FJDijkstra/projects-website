<script>
$(document).ready(function(){
    $("#buzzList").load("/api/buzzList.php");
    setInterval(() => {
        $("#buzzList").load("/api/buzzList.php");
    }, 2000);
});
</script>

<div class="w-100 h-100 d-flex flex-column align-items-center">
<h1 class="p-2">Recent Buzzes</h1>
<div id="buzzList" class="m-2 py-2 w-75 h-25 bg-white border rounded-2 container text-center overflow-auto "><p>Loading....</p></div>
<a class="btn btn-secondary" href="?deleteAllBuzzes=1">Clear Buzzes</a>
<h1 class="mt-4 p-2">Teams</h1>
<div id="teamList" class="m-2 py-3 w-75 h-25 bg-white border rounded-2 container text-center overflow-auto ">
<?php
if ($teams == []) {
    echo "<p class='text-muted'>Het wordt een rustig potje, er zijn geen teams</p>";
}
foreach ($teams as $key => $team) {
    echo "<li class='row align-items-center'>";
    echo "<span class='fw-bold col-5 text-start'>$team->name </span>";
    echo "<span class='col-5 text-end d-flex flex-row align-items-center'>";
    echo "<span class='flex-fill text-end float-start'><a class='m-1 btn btn-danger' href='?removePoint=$team->id'><i class='fs-6 fa fa-minus'></i></a></span>";
    echo "<span class='text-muted flex-fill'><form>";
    echo "<input type='hidden' value='$team->id' id='setPoints' name='setPoints'>";
    echo "<input class='form-control text-center' type='number' id='newPoints' name='newPoints' onChange='this.form.submit()' value='$team->points'>";
    echo "</form></span>";
    echo "<span class='flex-fill text-end float-end'><a class='m-1 btn btn-success' href='?addPoint=$team->id'><i class='fs-6 fa fa-plus'></i></a></span></span>";
    echo "<span class='col-2 text-end'><a class='m-1 btn btn-danger' href='?deleteTeam=$team->id'><i class='fs-6 fa fa-trash'></i></a></span>";
    echo "</li>";
    if ($key != array_key_last($teams)) {
        echo "<hr>";
    }
}
?>
</div>
<span>
<a class="btn btn-secondary" href="?clearPoints=1">Clear Scores</a>
<a class="btn btn-secondary" href="?deleteAllTeams=1">Remove All Teams</a>
</span>
</div>