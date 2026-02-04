
<div class="p-2 h-100 text-center mx-auto">
  <h1>Hoe groot ben ik?</h1>
  <p>Ik ben <span id="hoogte" class="fw-bold">?</span> pixels hoog en <span id="breedte" class="fw-bold">?</span> pixels breed.</p>
  <p> Ik heb dus een oppervlakte van <span id="oppervlakte" class="fw-bold"> ? </span> pixels</p>
  <div class="mx-auto w-50 h-50 d-flex flex-column">
    <div class="h-50 d-flex flex-row">
      <div class="text-white bg-black w-100 h-100 text-center"><span id="oppervlakte2" class="w-100 h-100 d-flex align-items-center justify-content-center fw-bold"> ? </span></div>
      <div class="text-center h-100"><span id="hoogte2" class="h-100 d-flex align-items-center fw-bold">?</span></div>
    </div>
    <div><span id="breedte2" class=" fw-bold"> ? </span></div>
  </div>
</div>
<script>
  $hoogte = window.innerHeight;
  $breedte = window.innerWidth;
  document.getElementById("hoogte").innerHTML = $hoogte;
  document.getElementById("breedte").innerHTML = $breedte;
  document.getElementById("oppervlakte").innerHTML = $hoogte * $breedte;
  document.getElementById("hoogte2").innerHTML = $hoogte;
  document.getElementById("breedte2").innerHTML = $breedte;
  document.getElementById("oppervlakte2").innerHTML = $hoogte * $breedte;
</script>