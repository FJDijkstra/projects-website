<nav class="p-2 navbar navbar-expand-md bg-dark " id="navbar" data-bs-theme="dark">
        <a class="navbar-brand" href="/"><?php echo $title; ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" role="button" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse " id="navbarNav">
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/">Home</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Maze
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="/maze">1 Player</a>
                <a class="dropdown-item" href="/maze?vs=1">2 Player</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/memory">(Meme)ory</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/teams">Buzzer</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/size">Size</a>
            </li>
            </ul>
        </div>
</nav>