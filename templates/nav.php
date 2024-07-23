<nav id="menu">
    <img src="/alexcloud.png" />
    <div class="menu_entries">
        <a href="/index.php">Accueil</a>
    </div>
    <?php
    if ($__connected["ADMIN"] == 1) printf('<div class="menu_entries"><a href="/admin.php">Admin Page</a></div>'); ?>
    <div class="menu_entries">
        <a href="myprofile.php">My Profile</a>
    </div>
    <div class="menu_entries">
        <a href="/logout.php">Disconnect</a>
    </div>
</nav>