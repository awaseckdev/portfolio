<?php
$page_courante=basename($_SERVER['PHP_SELF']);
if($page_courante == "index.php"){
    echo '<header>
        <nav style="display: flex;justify-content: space-between;">
            <div class="logo">Dev<span>FS</span></div>
            <ul>
                <li><a href="index.php" class="active">Présentation</a></li>
                <li><a href="pages/competences.php">Compétences</a></li>
                <li><a href="pages/projets.php">Projets</a></li>
                <li><a href="pages/contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>';


}else if($page_courante == "projets.php"){
    echo '<header>
        <nav style="display: flex;justify-content: space-between;">
            <div class="logo">Dev<span>FS</span></div>
            <ul>
                <li><a href="../index.php">Présentation</a></li>
                <li><a href="competences.php">Compétences</a></li>
                <li><a href="projets.php" class="active">Projets</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>';
    
}else if($page_courante == "competences.php"){
    echo '<header>
        <nav style="display: flex;justify-content: space-between;">
            <div class="logo">Dev<span>FS</span></div>
            <ul>
                <li><a href="../index.php">Présentation</a></li>
                <li><a href="competences.php" class="active">Compétences</a></li>
                <li><a href="projets.php">Projets</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>';
}else if($page_courante == "contact.php"){
    echo '<header>
        <nav style="display: flex;justify-content: space-between;">
            <div class="logo">Dev<span>FS</span></div>
            <ul>
                <li><a href="../index.php">Présentation</a></li>
                <li><a href="competences.php">Compétences</a></li>
                <li><a href="projets.php">Projets</a></li>
                <li><a href="contact.php" class="active">Contact</a></li>
            </ul>
        </nav>
    </header>';
}
?>