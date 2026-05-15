 <?php
$page_courante=basename($_SERVER['PHP_SELF']);
 if($page_courante == "index.php"){
    echo '<footer>
        <p>&copy; 2026 - Portfolio Awa SECK</p>
    </footer>';
 }else if($page_courante == "projets.php"){
    echo '<footer>
        <div class="footer-content">
            <p>&copy; 2026 - Awa Seck | Portfolio Étudiant</p>
            <div class="footer-links">
                <a href="https://github.com/ton-username" target="_blank">GitHub</a>
                <a href="../Mon_CV_professionnel_.pdf" target="_blank" class="btn-cv">
                    📄 Télécharger mon CV (PDF)
                </a>
            </div>
        </div>
    </footer>';
 }else if($page_courante == "competences.php"){
    echo  '<footer>
        <p>&copy; 2026 - Portfolio de Développeur</p>
    </footer>';
 }else if($page_courante == "contact.php"){
    echo '<footer>
        <div class="footer-content">
            <p>&copy; 2026 - Awa Seck | Portfolio Étudiant</p>
            <div class="footer-links">
                <a href="https://github.com/ton-username" target="_blank">GitHub</a>
                <a href="../Mon_CV_professionnel_.pdf" target="_blank" class="btn-cv">
                    📄 Télécharger mon CV (PDF)
                </a>
            </div>
        </div>
    </footer>';
    }
    ?>

