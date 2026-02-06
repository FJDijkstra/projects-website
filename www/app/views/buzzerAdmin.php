<script>
$(document).ready(function(){
    $("#buzzList").load("/api/buzzList.php");
    setInterval(() => {
        $("#buzzList").load("/api/buzzList.php");
    }, 2000);
});
</script>

<div class="overflow-auto w-100 h-100 d-flex flex-column align-items-center">
<h1 class="p-2">Recent Buzzes</h1>
<div id="buzzList" class="m-2 w-75 h-25 bg-white border rounded-2 container text-center"><p>Loading....</p></div>
<a class="btn btn-secondary" href="?deleteAll=1">Clear Buzzes</a>
<h1 class="mt-4 p-2">Teams</h1>
<div id="teamList" class="m-2 w-75 h-25 bg-white border rounded-2 container text-center">
<?php
foreach ($teams as $team) {
    echo "<li class='m-2 border-bottom row align-items-center'>";
    echo "<span class='fw-bold col text-start'>$team->name </span>";
    echo "<span class='text-muted small col'> $team->points</span>";
    echo "<span class='col text-end'><a class='m-1 btn btn-danger' href='?deleteTeam=$team->id'><i class='fs-6 fa fa-trash'></i></a></span>";
    echo "</li>";
}
?>
</div>
</div>