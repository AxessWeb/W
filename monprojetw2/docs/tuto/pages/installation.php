<h2>Installation</h2>
<h3>Prérequis</h3>
<ul>
	<li>PHP >= 5.5.*</li>
	<li>Composer</li>
	<li>MySQL avec PDO</li>
</ul>
<h3>Étapes pour l'installation</h3>
<p>Pour installer le framework :</p>
<p>1. Dans un terminal, naviguez vers votre dossier contenant vos projets web (htdocs/ ou www/).</p>
<pre><code>cd C:/xampp/htdocs/</code></pre>
<p>2. Cloner le repo :</p>
<pre><code>git clone https://github.com/AxessWeb/W.git votre_nouveau_dossier/</code></pre>
<p>3. Installer les dépendances avec Composer :</p>
<pre><code>cd votre_nouveau_dossier/
composer install</code></pre>
<p>4. Créez une copie de <span class="code">app/config.dist.php</span> et nommez-la <span class="code">app/config.php</span></p>
<p>5. <a href="?p=configuration" title="Référence des configurations">Configurez votre application</a> dans <span class="code">app/config.php</span> et <span class="code">app/routes.php</span></p>
<h3>Tester l'installation</h3>
<p>Naviguez vers <span class="code">http://localhost/votre_nouveau_dossier/public/<b>check.php</b></span></p>