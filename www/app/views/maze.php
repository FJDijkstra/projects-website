
<?php
$height = isset($_GET['height']) ? max($_GET['height'], 10) : 15;
$width = isset($_GET['width']) ? max($_GET['width'], 10) : 20;
?>
<div id="mazebody" class="d-flex flex-column">
  <div id="maze-form">
    <form id="sizes" class="form-group d-flex p-4">
        <?php echo "<input type='hidden' id='vs' name='vs' value='$vs'/>"; ?>
        <label class="align-middle m-2 my-auto" for="height">Height:</label>
        <?php echo "<input class='form-control w-10 m-2' type='number' id='height' name='height' required min = '10' value='$height'/>"; ?>
        <label class="align-middle m-2 my-auto" for="width">Width:</label>
        <?php echo "<input class='form-control w-10 m-2' type='number' id='width' name='width' required min = '10' value='$width'/>"; ?>
        <input class="btn btn-secondary m-2" type="submit" value="Generate">
    </form>
  </div>

  <div id="mazediv" class="mx-4 p-2 mb-4">
    <div id="scrollmaze" class="w-100 h-100 overflow-auto">
      <div id="MazeGameCanvas" class="d-flex align-items-center">
        <canvas id="mazecanvas" class="mx-auto"></canvas>
      </div>
    </div>
  </div>

  <span id='maze-bottom-bar' class='d-flex w-100 mb-5 justify-content-between'>
    <?php if(!$vs) { ?>
    <span id='arrows' class='float-left px-4 '>
      <button class='maze-button btn btn-primary align-middle fs-3 ' onclick='move("left", "red")'>&larr;</button>
      <button class='maze-button btn btn-primary align-middle fs-3' onclick='move("up", "red")'>&uarr;</button>
      <button class='maze-button btn btn-primary align-middle fs-3 ' onclick='move("down", "red")'> &darr; </button>
      <button class='maze-button btn btn-primary align-middle fs-3 ' onclick='move("right", "red")'>&rarr;</button>
    </span>
    <?php } ?>
  <h2 class='float-right p-4' id='timer'>time: 0</h2>
  </span>
</div>

<script>
  const canvas = document.getElementById("mazecanvas");
  const ctx = canvas.getContext("2d");

  //colors
  const blue = "rgb(126, 131, 237)";
  const lblue = "rgb(126, 169, 237)";
  const llblue = "rgb(126, 199, 237)";
  const green = "rgb(198, 247, 160)";
  const red = "rgb(240, 120, 105)";
  const lred = "rgb(240, 150, 105)";
  const llred = "rgb(240, 175, 105)";


  // Stel canvasgrootte in
  let offsett = document.getElementById('maze-form').offsetHeight + document.getElementById('maze-bottom-bar').offsetHeight + document.getElementById('navbar').offsetHeight;
  viewwidth = document.getElementById('MazeGameCanvas').offsetWidth;
  viewheight = document.getElementById('MazeGameCanvas').offsetHeight;

  let vs = parseInt(document.getElementById("vs").value);

  // stel doolhof grootte in
  let height = parseInt(document.getElementById("height").value);
  let width = parseInt(document.getElementById("width").value);
  let xpadding = 0;
  let ypadding = 0;
  let retryX = 0;
  let retryY = 0;

  //blok grootte
  cell_width = Math.floor(Math.min((( viewwidth) / width), ((viewheight) / height)));
  if (!vs) {
    cell_width = Math.max(25, cell_width);
  }
  /*xpadding = Math.floor((canvas.width - width * cell_width) / 2);
  xpadding = xpadding < 0 ? 0 : xpadding;
  ypadding = Math.floor((canvas.height * cell_width) / 2);
  ypadding = ypadding < 0 ? 0 : ypadding;*/

  canvas.width = width * cell_width + 2 * xpadding;
  canvas.height = height * cell_width + 2 * ypadding;

  window.onload = () => {
    resize();
  };

  // variabelen voor genereren
  let next = [];
  let last_visited = [];
  let startw = Math.floor(Math.random() * width);
  let currentcell = {
    red: [startw, 0],
    blue: [startw, 0]
  };
  let last_direction = {
    red: "down",
    blue: "down"
  };
  let winner = "red";

  let endh = Math.floor(Math.random() * (height - 1));
  let endw = Math.floor(Math.random() * (width - 1));
  while ((endh - 0) * (endh - 0) + (endw - startw) * (endw - startw) < (height * 0.66) * (height * 0.66)) {
    endh = Math.floor(Math.random() * (height - 1));
    endw = Math.floor(Math.random() * (width - 1));
  }

  //winning
  let start_time = Date.now();
  let end_time = Date.now();
  let finished = false;

  // maak leeg doolhof
  let maze =  Array.from({ length: width }, () => Array.from({ length: height }, () => (
    {status:"unvisited", left:"closed", right:"closed", up:"closed", down:"closed", color1:"white", color2: "white"}
  )));

  //finish
  maze[endw][endh] = {status:"finish", left:"closed", right:"open", up:"closed", down:"open", color1: green, color2: green};
  maze[endw + 1][endh] = {status:"finish", left:"open", right:"closed", up:"closed", down:"open", color1: green, color2: green};
  maze[endw][endh + 1] = {status:"finish", left:"closed", right:"open", up:"open", down:"closed", color1: green, color2: green};
  maze[endw + 1][endh + 1] = {status:"finish", left:"open", right:"closed", up:"open", down:"closed", color1: green, color2: green};
  //start
  maze[startw][0] = {status:"start", left:"closed", right:"closed", up:"closed", down:"closed", color1: red, color2: blue};

  //images
  const mouseImages = {
   up: new Image(),
   down: new Image(),
   left: new Image(),
   right: new Image()
  };
  mouseImages.down.src = "/img/maze/mouse_down.png";
  mouseImages.up.src = "/img/maze/mouse_up.png";
  mouseImages.right.src = "/img/maze/mouse_right.png";
  mouseImages.left.src = "/img/maze/mouse_left.png";
  const cheeseImage = new Image();
  cheeseImage.src = "/img/maze/cheese.png";
  const holeImage = new Image();
  holeImage.src = "/img/maze/hole.png";
  const victoryImage = new Image();
  victoryImage.src = "/img/maze/victory.png";

  function resize() {
    console.log("windowsize: ", viewwidth, viewheight);
    cell_width = Math.floor(Math.min((( viewwidth) / width), ((viewheight) / height)));
    console.log("cellWidth: ", cell_width);
    if (!vs) {
      cell_width = Math.max(25, cell_width);
    }
    /*xpadding = Math.floor((canvas.width - width * cell_width) / 2);
    xpadding = xpadding < 0 ? 0 : xpadding;
    ypadding = Math.floor((canvas.height - height * cell_width) / 2);
    ypadding = ypadding < 0 ? 0 : ypadding;*/
    console.log("cellWidth: ", cell_width);

    canvas.width = width * cell_width + 2 * xpadding;
    canvas.height = height * cell_width + 2 * ypadding;
    console.log("windowsize: ", canvas.width, canvas.height);
  }

  function drawVictory() {
    let w = Math.min(viewwidth, canvas.width);
    let h = Math.min(viewheight, canvas.height);
    let scrollX = document.getElementById('scrollmaze').scrollLeft;
    let scrollY = document.getElementById('scrollmaze').scrollTop;
    ctx.fillStyle = "rgb(255,255,255,0.7)";
    ctx.fillRect(0 , 0, canvas.width, canvas.height);
    ctx.fillStyle = "black";
    ctx.font = "100px Arial";
    ctx.fillText("Victory", w / 2 - ctx.measureText("Victory").width / 2 + scrollX, 100 + scrollY);
    if (!vs) {
      let red_cells = countCells(lred);
      let score = Math.floor((100000 * red_cells) / Math.floor((end_time - start_time)));
      ctx.font = "50px Arial";
      let text = "score: " + score;
      ctx.fillText(text, w / 2 - ctx.measureText(text).width / 2 + scrollX, 160 + scrollY);
    } else {
      ctx.font = "50px Arial";
      let text = "player " + winner + " won!";
      ctx.fillText(text, w / 2 - ctx.measureText(text).width / 2 + scrollX, 160 + scrollY);
    }
    ctx.drawImage(
      victoryImage,
      w / 2 - h / 4 + scrollX, // Correct centreren van de afbeelding
      180 + scrollY,
      h / 2,
      h / 2 // Schaal de hoogte naar de breedte
    );
    retryX = w / 2 - ctx.measureText("try again").width / 2 - 25 + scrollX;
    retryY =  h - 125 - 25 + scrollY;
    ctx.fillRect(retryX, retryY, 250, 80);
    ctx.fillStyle = "white";
    ctx.fillRect(retryX + 5, retryY + 5, 240, 70);
    ctx.fillStyle = "black";
    ctx.fillText("try again", retryX + 25, retryY + 50);
  }

  function drawObjects() {
    ctx.drawImage(
      cheeseImage,
      endw * cell_width + xpadding, // Correct centreren van de afbeelding
      endh * cell_width + ypadding,
      cell_width * 2,
      cell_width * 2 // Schaal de hoogte naar de breedte
    );
    ctx.drawImage(
      holeImage,
      startw * cell_width + xpadding - 0.3 * cell_width, // Correct centreren van de afbeelding
      0 + ypadding - 0.3 * cell_width,
      cell_width * 1.5,
      cell_width * 1.5// Schaal de hoogte naar de breedte
    );
    if (vs) {
      ctx.drawImage(
        mouseImages[last_direction.blue],
        currentcell.blue[0] * cell_width + xpadding, // Correct centreren van de afbeelding
        currentcell.blue[1] * cell_width + ypadding,
        cell_width,
        cell_width // Schaal de hoogte naar de breedte
      );
    }
    ctx.drawImage(
      mouseImages[last_direction.red],
      currentcell.red[0] * cell_width + xpadding, // Correct centreren van de afbeelding
      currentcell.red[1] * cell_width + ypadding,
      cell_width,
      cell_width // Schaal de hoogte naar de breedte
    );
  }

  function updateGame() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = "white";
    ctx.fillRect(xpadding, ypadding, width * cell_width, height * cell_width);
    //draw maze
    for (let h = 0; h < height; h++) {
        for (let w = 0; w < width; w++) {
            ctx.fillStyle = maze[w][h].color1;
            ctx.fillRect(w * cell_width + xpadding, h * cell_width + ypadding, cell_width, cell_width);
            if (vs && maze[w][h].color2 != "white") {
              ctx.fillStyle = maze[w][h].color2;
              if (maze[w][h].color1 == "white") {
                ctx.fillRect(w * cell_width + xpadding, h * cell_width + ypadding, cell_width, cell_width);
              } else {
                ctx.beginPath();
                ctx.moveTo(w * cell_width + xpadding, h * cell_width + ypadding);
                ctx.lineTo(w * cell_width + xpadding + cell_width, h * cell_width + ypadding);
                ctx.lineTo(w * cell_width + xpadding + cell_width, h * cell_width + ypadding + cell_width);
                ctx.fill();
              }
            }
            ctx.strokeStyle = "black";
            ctx.lineWidth = 3;
            if (maze[w][h].left == "closed") {
                ctx.beginPath();
                ctx.moveTo(w * cell_width + xpadding, h * cell_width + ypadding);
                ctx.lineTo(w * cell_width + xpadding, (h + 1) * cell_width + ypadding);
                ctx.stroke();
            }
            if (maze[w][h].right == "closed") {
                ctx.beginPath();
                ctx.moveTo((w + 1) * cell_width + xpadding, h * cell_width + ypadding);
                ctx.lineTo((w + 1) * cell_width + xpadding, (h + 1)* cell_width + ypadding);
                ctx.stroke();
            }
            if (maze[w][h].down == "closed") {
                ctx.beginPath();
                ctx.moveTo(w * cell_width + xpadding, (h + 1) * cell_width + ypadding);
                ctx.lineTo((w + 1) * cell_width + xpadding, (h + 1) * cell_width + ypadding);
                ctx.stroke();
            }
            if (maze[w][h].up == "closed") {
                ctx.beginPath();
                ctx.moveTo(w * cell_width + xpadding, h * cell_width + ypadding);
                ctx.lineTo((w + 1) * cell_width + xpadding, h * cell_width + ypadding);
                ctx.stroke();
            }
        }
    }
    let text = "time: " + Math.floor((Date.now() - start_time) / 1000);
    document.getElementById('timer').innerText = text;
    //draw rest
    drawObjects();
    if (finished) {
      drawVictory();
    }
  }

  function countCells(color) {
    count = 0;
     for (let h = 0; h < height; h++) {
        for (let w = 0; w < width; w++) {
          if (maze[w][h].color1 == color || maze[w][h].color2 == color) {
            count = count + 1;
          }
        }
    }
    return count;
  }

  function visitCell(cellw, cellh, direction, recurse) {
    let new_cellh = cellh;
    let new_cellw = cellw;
    if(direction == "up") {
      new_cellh = new_cellh - 1;
      maze[cellw][cellh].up = "open";
      maze[new_cellw][new_cellh].down = "open";
    }
    if(direction == "down") {
      new_cellh = new_cellh + 1;
      maze[cellw][cellh].down = "open";
      maze[new_cellw][new_cellh].up = "open";
    }
    if(direction == "left") {
      new_cellw = new_cellw - 1;
      maze[cellw][cellh].left = "open";
      maze[new_cellw][new_cellh].right = "open";
    }
    if(direction == "right") {
      new_cellw = new_cellw + 1;
      maze[cellw][cellh].right = "open";
      maze[new_cellw][new_cellh].left = "open";
    }
    maze[new_cellw][new_cellh].status = "visited";
    if (recurse) {
      last_visited.push([new_cellw, new_cellh]);
    }
  }

  function makePaths(cellw, cellh){
    const path_chance = Math.random();
    let paths_to_make = path_chance < (0.02 ) ?  3 : ( path_chance < (0.2) ? 2 : 1 );
    let options = []
    if (cellh < height - 1) {
      if (maze[cellw][cellh + 1].status == "unvisited"){
        options.push("down");
      }
    }
    if (cellh > 0) {
      if (maze[cellw][cellh - 1].status == "unvisited"){
        options.push("up");
      }
    }
    if (cellw < width - 1) {
      if (maze[cellw + 1][cellh].status == "unvisited"){
        options.push("right");
      }
    }
    if (cellw > 0) {
      if (maze[cellw - 1][cellh].status == "unvisited"){
        options.push("left");
      }
    }
    while (options.length > 0 && paths_to_make > 0) {
      const op = Math.floor(Math.random() * options.length);
      visitCell(cellw, cellh, options[op], true)
      options.splice(op, 1);
      paths_to_make = paths_to_make - 1;
    }
  }

  function connect_to_path(cellw, cellh) {
    let options = []
    if (cellh < height - 1) {
      if (maze[cellw][cellh + 1].status == "visited"){
        options.push("down");
      }
    }
    if (cellh > 0) {
      if (maze[cellw][cellh - 1].status == "visited"){
        options.push("up");
      }
    }
    if (cellw < width - 1) {
      if (maze[cellw + 1][cellh].status == "visited"){
        options.push("right");
      }
    }
    if (cellw > 0) {
      if (maze[cellw - 1][cellh].status == "visited"){
        options.push("left");
      }
    }
    if (options.length == 0) {
      return false;
    }
    const op = Math.floor(Math.random() * options.length);
    visitCell(cellw, cellh, options[op], false);
    maze[cellw][cellh].status = "visited";
    next.push([cellw, cellh]);
    return true;
  }

  function getUnvisited(){
    let unvisited = [];
    for (let w = 0; w < width; w++) {
      for (let h = 0; h < height; h++) {
        if (maze[w][h].status == "unvisited") {
          if (maze[w][h].status == "unvisited"){
            unvisited.push([w,h]);
          }
        }
      }
    }
    let found = false;
    while (unvisited.length > 0 && !found) {
      let index = Math.floor(Math.random() * unvisited.length);
      not_found = connect_to_path(unvisited[index][0], unvisited[index][1]);
      unvisited.splice(index, 1);
    }
  }

  function openFinish() {
    let opening =  Math.floor(Math.random() * 8);
    switch(opening){
      case 0:
        if(endh == 0) {
          return false;
        }
        maze[endw][endh].up = "open";
        maze[endw][endh - 1].down = "open";
        break;
      case 1:
        if(endw == 0) {
          return false;
        }
        maze[endw][endh].left = "open";
        maze[endw - 1][endh].right = "open";
        break;
      case 2:
        if(endh == 0) {
          return false;
        }
        maze[endw + 1][endh].up = "open";
        maze[endw + 1][endh - 1].down = "open";
        break;
      case 3:
        if(endw == width - 2) {
          return false;
        }
        maze[endw + 1][endh].right = "open";
        maze[endw + 2][endh].left = "open";
        break;
      case 4:
        if(endh == height - 2) {
          return false;
        }
        maze[endw][endh + 1].down = "open";
        maze[endw][endh + 2].up = "open";
        break;
      case 5:
        if(endw == 0) {
          return false;
        }
        maze[endw][endh + 1].left= "open";
        maze[endw - 1][endh + 1].right = "open";
        break;
      case 6:
        if(endh == height - 2) {
          return false;
        }
        maze[endw + 1][endh + 1].down = "open";
        maze[endw + 1][endh + 2].up = "open";
        break;
      case 7:
        if(endw == width - 2) {
          return false;
        }
        maze[endw + 1][endh + 1].right = "open";
        maze[endw + 2][endh + 1].left = "open";
        break;
    }
    return true;
  }

  function makeMaze() {
    while (next.length > 0) {
        for (let i = 0; i < next.length; i++) {
          makePaths(next[i][0], next[i][1])
        }
        next = last_visited;
        last_visited = [];
        if (next.length == 0) {
          getUnvisited();
        }
    }
    let opened_end = false;
    while (!opened_end) {
      opened_end = openFinish();
    }
  }

  function move(direction, player){
    newW = currentcell[player][0];
    newH = currentcell[player][1];
    last_direction[player] = direction;
    switch (direction) {
      case "up":
        if( maze[currentcell[player][0]][currentcell[player][1]].up == "closed"){
         return;
        }
        newH  = newH - 1;
        break;
      case "down":
        if( maze[currentcell[player][0]][currentcell[player][1]].down == "closed"){
         return;
        }
        newH  = newH + 1;
        break;
      case "left":
        if( maze[currentcell[player][0]][currentcell[player][1]].left == "closed"){
         return;
        }
        newW  = newW - 1;
        break;
      case "right":
        if( maze[currentcell[player][0]][currentcell[player][1]].right == "closed"){
         return;
        }
        newW  = newW + 1;
        break;
      default:
        return; // Quit when this doesn't handle the key event.
    }
    if (player == "red") {
      if(maze[newW][newH].color1 == green){
        finished = true;
        winner = "red";
        end_time = Date.now();
        maze[currentcell[player][0]][currentcell[player][1]].color1 = lred;
        console.log("victory");
      } else if (maze[newW][newH].color1 == lred){
        maze[newW][newH].color1 = red;
        maze[currentcell[player][0]][currentcell[player][1]].color1 = llred;
      } else {
        maze[newW][newH].color1 = red;
        maze[currentcell[player][0]][currentcell[player][1]].color1 = lred;
      }
    } else {
      if(maze[newW][newH].color2 == green){
        finished = true;
        winner = "blue";
        end_time = Date.now();
        maze[currentcell[player][0]][currentcell[player][1]].color2 = lblue;
        console.log("victory");
      } else if (maze[newW][newH].color2 == lblue){
        maze[newW][newH].color2 = blue;
        maze[currentcell[player][0]][currentcell[player][1]].color2 = llblue;
      } else {
        maze[newW][newH].color2 = blue;
        maze[currentcell[player][0]][currentcell[player][1]].color2 = lblue;
      }
    }
    currentcell[player] = [newW, newH];
    updateGame();
  }

  window.addEventListener("keydown", function (event) {
  if (event.defaultPrevented || finished) {
    return; // Do nothing if the event was already processed
  }

  switch (event.key) {
      case "ArrowDown":
        move("down", "red");
        break;
      case "ArrowUp":
        move("up", "red");
        break;
      case "ArrowLeft":
        move("left", "red");
        break;
      case "ArrowRight":
        move("right", "red");
        break;
      case "s":
        if (vs) {
          move("down", "blue");
        }
        break;
      case "w":
        if (vs) {
          move("up", "blue");
        }
        break;
      case "a":
        if (vs) {
          move("left", "blue");
        }
        break;
      case "d":
        if (vs) {
          move("right", "blue");
        }
        break;
      default:
        return; // Quit when this doesn't handle the key event.
    }

    // Cancel the default action to avoid it being handled twice
    event.preventDefault();
  }, true);

  canvas.addEventListener("click", (event) => {
    const clickX = event.offsetX;
    const clickY = event.offsetY;
    //retry
    let again = false;
    if (finished) {
      again =
        clickX > retryX && 
        clickX < retryX + 250 &&
        clickY > retryY &&
        clickY < retryY + 80;
        if (again) {
          location.reload();
        }
    }
  });

  function runGame() {
    setTimeout(() => {
      updateGame();
      if (!finished) {
        runGame();
      }
    }, 500);
  }

  next.push([startw, 0]);
  makeMaze();
  start_time = Date.now();
  updateGame();
  runGame();


</script>
