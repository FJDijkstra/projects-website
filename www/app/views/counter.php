<script>
$(document).ready(function(){
    $("#amount").load("/api/counterAmount.php?counter=<?= $counterid ?>");
    setInterval(() => {
        $("#amount").load("/api/counterAmount.php?counter=<?= $counterid ?> ");
    }, 1000);
});
</script>
<div id="counter" class="w-100 p-5 d-flex flex-column align-items-center">
    <?php echo "<h1>Counter: $countername</h1>"; ?>
    <span class="flex-fill d-flex flex-row w-100">
        <a class="green-dark btn btn-success align-content-center fs-2 m-1 w-100" href="?counter=<?= $counterid ?>&increment=10">+10</a>
        <a class="green-dark btn btn-success align-content-center fs-2 m-1 w-100" href="?counter=<?= $counterid ?>&increment=50">+50</a>
    </span>
    <a class="green-medium flex-fill btn btn-success align-content-center fs-2 m-1 w-100" href="?counter=<?= $counterid ?>&increment=5">+5</a>
    <a class="green-light flex-fill btn btn-success align-content-center fs-2 m-1 w-100" href="?counter=<?= $counterid ?>&increment=1">+1</a>
    <div id="amount" class="flex-fill display-1 align-content-center border w-100 bg-white text-center m-1">Loading...</div>
    <a class="red-light flex-fill btn btn-danger align-content-center fs-2 m-1 w-100" href="?counter=<?= $counterid ?>&decrement=1">-1</a>
    <a class="red-medium flex-fill btn btn-danger align-content-center fs-2 m-1 w-100" href="?counter=<?= $counterid ?>&decrement=5">-5</a>
    <span class="flex-fill d-flex flex-row w-100">
        <a class="red-dark btn btn-danger align-content-center fs-2 m-1 w-100" href="?counter=<?= $counterid ?>&decrement=10">-10</a>
        <a class="red-dark btn btn-danger align-content-center fs-2 m-1 w-100" href="?counter=<?= $counterid ?>&decrement=50">-50</a>
    </span>
</div>