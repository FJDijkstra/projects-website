<div class="w-100 h-100 d-flex flex-column">
    <div class="mx-auto w-75">
        <h2 class="text-center mt-4">Geef Feedback</h2>
        <form method="post">
            <textarea id="feedback" name="feedback" class="form form-control mb-2" maxlength="200" placeholder="Geef hier je feedback..."></textarea>
            <button type="submit" class="btn btn-primary mb-2">Submit feedback</button>
        </form>
    </div>

    <h2 class="text-center">Huidige Feedback</h2>
    <div class="mx-auto w-75 h-50 bg-white border rounded-2 container text-center p-2 overflow-auto">
    <?php
        if ($feedback == []) {
            echo "<p class='text-muted'>De website is perfect, er is geen feedback</p>";
        }
        foreach ($feedback as $key => $fb) {
            echo "<div class='m-1 row'><span class='col'>$fb->feedback</span>";
            echo "<span class='col-4 float-end text-muted small'>$fb->timestamp</span></div>";
            if ($key != array_key_last($feedback)) {
                echo "<hr>";
            }
        }
    ?>
    </div>
</div>