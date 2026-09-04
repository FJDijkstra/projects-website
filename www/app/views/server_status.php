<script>
$(document).ready(async function(){
    $query = await fetch("https://api.mcsrvstat.us/3/wikel.tjirk.nl:25565");
    $status = await $query.json();
    $("#online").text($status["online"]);
    if ($status["online"]){
        $("#players").text($status["players"]["online"] + " / " + $status["players"]["max"]);
        $("#motd").text("");
        $("#motd").append($status["motd"]["html"]);
        if ($status["players"]["online"] > 0){
            let players = $status["players"]["list"];
            let playerList = "<ul style='list-style-type:none; padding:0;'>";
            for (let i = 0; i < players.length; i++){
                playerList += "<li>- " + players[i]["name"] + "</li>";
            }
            playerList += "</ul>";
            $("#players").append(playerList);
        }
    } else {
        $("#players").text("0 / 0");
    }
    setInterval(async () => {
        $query = await fetch("https://api.mcsrvstat.us/3/wikel.tjirk.nl:25565");
        $status = await $query.json();
        $("#online").text($status["online"]);
        if ($status["online"]){
            $("#players").text($status["players"]["online"] + " / " + $status["players"]["max"]);
            $("#motd").text("");
            $("#motd").append($status["motd"]["html"]);
            if ($status["players"]["online"] > 0){
                let players = $status["players"]["list"];
                let playerList = "<ul style='list-style-type:none; padding:0;'>";
                for (let i = 0; i < players.length; i++){
                    playerList += "<li>- " + players[i]["name"] + "</li>";
                }
                playerList += "</ul>";
                $("#players").append(playerList);
        }
        } else {
            $("#players").text("0 / 0");
        }
    }, 20000);
});
</script>
<div class="text-center display-block">
    <h1>Kiwilands Status</h1>
    <img src="https://api.mcsrvstat.us/icon/wikel.tjirk.nl:25565" alt="Server Icon" class="mb-2" width="128" height="128">
    <div id="motd"></div>
    <h3>Online Status</h3>
    <p id="online">loading ...</p>
    <h3>Players</h3>
    <p class="mx-auto" id="players">loading ...</p>
</div>