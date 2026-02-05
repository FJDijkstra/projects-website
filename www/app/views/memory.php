<div class="h-100 overflow-auto">
    <canvas id="gameCanvas" class="overflow-x-hidden overflow-y-auto"></canvas>
</div>

<script>
    const canvas = document.getElementById("gameCanvas");
    const ctx = canvas.getContext("2d");
    console.log(canvas.width, canvas.height);
    canvas.width = window.innerWidth;

    let cardSize = 120;
    let spacing = 20;
    let cardsOnRow = Math.floor(canvas.width / (cardSize + spacing));
    let cardsOnCol = Math.floor(36 / cardsOnRow);
    if ((cardsOnRow * cardsOnCol) % 2 != 0) {
        cardsOnCol++;
    }
    let xOffset = (canvas.width - cardsOnRow *  (cardSize + spacing)) / 2;
    let yOffset = xOffset;

    let navset = document.getElementById("navbar").offsetHeight;
    canvas.height = Math.max(cardsOnCol * (cardSize + spacing) + yOffset * 4, window.innerHeight - navset);

    let cardAmount = cardsOnRow * cardsOnCol;

    //images
    const images =  Array.from({ length: 32 }, () => new Image());
    const sounds =  Array(0);
    const test = Array(0);
    const srcs = ["5_euro", "ah_wiep", "beleg", "biem", "bij_de_lidl", "bingo", "bizon", "zas", 
                  "broodje_sate", "cda", "dikke_bmw", "water", "even_dansen", "geen_neus", "haha_bier", "hemel",
                  "ik_wil_kaas", "jeroen", "keukenrol", "mand", "nee_denk_t_niet", "watermeloen", "nitraatje", "nou_nee",
                  "ok_lets_go", "onvoldoende", "piek", "pindakaas", "duivenmeneer", "niet_bellen", "wouter", "blikje"];

    for (let i = 0; i < 32; i++) {
        sounds.push(new Audio("/sound/memory/" + srcs[i] + ".mp3"));
        images[i].src = "/img/memory/" + srcs[i] + ".jpg";
        test.push(new Image("/img/memory/" + srcs[i] + ".jpg"));
    };
    let currentAudio = sounds[0];

    //cards
    let card1 = {state: 0, color: "pink", id: 0};
    let card2 = {state: 0, color: "red", id: 1};
    let card3 = {state: 0, color: "green", id: 2};
    let card4 = {state: 0, color: "blue", id: 3};
    let card5 = {state: 0, color: "yellow", id: 4};
    let card6 = {state: 0, color: "orange", id: 5};
    let card7 = {state: 0, color: "black", id: 6};
    let card8 = {state: 0, color: "blueviolet", id: 7};
    let card9 = {state: 0, color: "cyan", id: 8};
    let card10 = {state: 0, color: "brown", id: 9};
    let card11 = {state: 0, color: "darkcyan", id: 10};
    let card12 = {state: 0, color: "darkgreen", id: 11};
    let card13 = {state: 0, color: "darkgray", id: 12};
    let card14 = {state: 0, color: "darkred", id: 13};
    let card15 = {state: 0, color: "deeppink", id: 14};
    let card16 = {state: 0, color: "darksalmon", id: 15};
    let card17 = {state: 0, color: "goldenrod", id: 16};
    let card18 = {state: 0, color: "grey", id: 17};
    let card19 = {state: 0, color: "indigo", id: 18};
    let card20 = {state: 0, color: "lawngreen", id: 19};
    let card21= {state: 0, color: "lightblue", id: 20};
    let card22= {state: 0, color: "lime", id: 21};
    let card23 = {state: 0, color: "olive", id: 22};
    let card24 = {state: 0, color: "orchid", id: 23};
    let card25 = {state: 0, color: "peru", id: 24};
    let card26 = {state: 0, color: "pink", id: 25};
    let card27 = {state: 0, color: "blue", id: 26};
    let card28 = {state: 0, color: "royalblue", id: 27};
    let card29 = {state: 0, color: "tan", id: 28};
    let card30 = {state: 0, color:  "teal", id: 29};
    let card31 = {state: 0, color: "yellowgreen", id: 30};
    let card32 = {state: 0, color: "lightgrey", id: 31};

    let allCards = [card1, card2, card3, card4, card5, card6, card7, card8, card9, card10, card11, card12, card13, card14, card15, card16,
                    card17, card18, card19, card20, card21, card22, card23, card24, card25, card26, card27, card28, card29, card30, card31, card32
    ];

    let idxs = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31];

    for (let idx = 0; idx < 32; idx++) {
        let rand = Math.floor(Math.random() * 32);
        let tmp = idxs[idx];
        idxs[idx] = idxs[rand];
        idxs[rand] = tmp;
    }

    let cards = Array(0);

    for (let i = 0; i < 32; i++) {
        cards.push(Object.create(allCards[idxs[i]]));
        cards.push(Object.create(allCards[idxs[i]]));
    }

    for (let idx = 0; idx < cardAmount; idx++) {
        let rand = Math.floor(Math.random() * cardAmount);
        let tmp = cards[idx];
        cards[idx] = cards[rand];
        cards[rand] = tmp;
    }

    let select1 = -1;
    let select2 = -1;
    let wait = false;
    let totalFound = 0;
    let turns = 0;

    function updateGame() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        ctx.fillStyle = "black";
        ctx.font = "30px Arial";
        ctx.fillText("you took " + turns + " turns!", canvas.width / 2 - ctx.measureText("you took " + turns + " turns!").width / 2, cardSize * cardsOnCol + spacing * cardsOnCol + 0.5 * spacing + yOffset * 2 );
        ctx.font = "100px Arial";
        //victory
        if (totalFound == cardAmount) {
            ctx.fillRect(canvas.width / 2 - ctx.measureText("try again").width / 2 - 25, yOffset,  ctx.measureText("try again").width + 50, 110);
            ctx.fillStyle = "white";
            ctx.fillRect(canvas.width / 2 - ctx.measureText("try again").width / 2 - 20, yOffset + 5, ctx.measureText("try again").width + 40, 100);
            ctx.fillStyle = "black";
            ctx.fillText("try again", canvas.width / 2 - ctx.measureText("try again").width / 2, yOffset + 80);
        }

        let idx = 0;
        for (let x = 0; x < cardsOnRow; x++) {
            for (let y = 0; y < cardsOnCol; y++) {
                if (cards[idx].state != 2) {
                    let xval = cardSize * x + spacing * x + 0.5 * spacing + xOffset;
                    let yval = cardSize * y + spacing * y + 0.5 * spacing + yOffset;
                    ctx.fillStyle = "black";
                    ctx.fillRect(xval, yval, cardSize, cardSize);
                    ctx.fillStyle = "white";
                    ctx.fillRect(xval + 3, yval + 3, cardSize - 6, cardSize - 6);
                    if (cards[idx].state == 1) {
                        ctx.fillStyle = cards[idx].color;
                        ctx.fillRect(xval + 10, yval + 10, cardSize - 20, cardSize - 20);
                        ctx.drawImage(
                            images[cards[idx].id],
                            xval + 10,
                            yval + 10,
                            cardSize - 20,
                            cardSize - 20
                        );
                    }
                }
                idx++;
            }
        }
    }

    function playSound(idx) {
        if (sounds.length > idx) {
            currentAudio.pause();
            currentAudio.currentTime = 0;
            currentAudio = sounds[idx];
            currentAudio.play();
        }
    }

    canvas.addEventListener("click", (event) => {
        const clickX = event.offsetX;
        const clickY = event.offsetY;
        if (totalFound == cardAmount){
            if (clickX > canvas.width / 2 - ctx.measureText("try again").width / 2 - 25 &&
                clickX < canvas.width / 2 - ctx.measureText("try again").width / 2 - 25 + ctx.measureText("try again").width + 50 &&
                clickY >  yOffset && clickY < yOffset + 110) {
                    location.reload();
            }
        }
        if (!wait ) {
            let x = Math.floor((clickX - xOffset)/ (cardSize + spacing));
            let y = Math.floor((clickY - yOffset)/ (cardSize + spacing));
            if (x >= 0 && x < cardsOnRow && y >= 0 && y < cardsOnCol) {
                console.log(x, y);
                let idx = x * cardsOnCol + y;
                if (cards[idx].state == 0){
                    if (select1 == -1) {
                        select1 = idx;
                        cards[idx].state = 1;
                    } else if (select2 == -1) {
                        turns++;
                        select2 = idx;
                        cards[idx].state = 1;
                    }
                    updateGame();
                }
                if (select1 != -1 && select2 != -1) {
                    wait = true;
                    if(cards[select1].color == cards[select2].color) {
                        cards[select1].state = 2;
                        cards[select2].state = 2;
                        playSound(cards[select1].id);
                        totalFound += 2;
                    } else {
                        cards[select1].state = 0;
                        cards[select2].state = 0;
                    }
                    setTimeout(() => {
                        select1 = -1;
                        select2 = -1;
                        updateGame();
                        wait = false;
                    }, 2000);
                }
            }
        } 
    });

    updateGame();






</script>
</html>