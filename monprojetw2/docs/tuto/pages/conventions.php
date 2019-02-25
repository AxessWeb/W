<h2>Conventions du framework W.</h2>

<h3>Conventions obligatoires</h3>
<ul>
	<li>La variable contenant l'instance de l'applicaiton doit être nommée <span class="code">$app</span></li>
	<li>Les contrôleurs doivent être dans l'espace de nom <span class="code">\Controller\</span>, donc dans le dossier <span class="code">app/Controller/</span></li>
	<li>Le nom des contrôleurs doit être suffixé par le mot <span class="code">Controller</span> (exemple <span class="code">PostController</span>)</li>
	<li>Les modèles doivent être dans l'espace de nom <span class="code">\Model\</span>, donc dans le dossier <span class="code">app/Model/</span></li>
	<li>Le nom des modèles doit être suffixé par le mot <span class="code">Model</span> (exemple <span class="code">PostsModel</span>)</li>
</ul>

<h3>Conventions d'usage</h3>
<ul>
	<li>Le nom des routes et des fichiers de vue sont écrit en minuscules, les mots séparés par des underscore (<span class="code">_</span>)</li>
	<li>Le nom des tables MySQL est deviné automatiquement en fonction du nom du modèle, les mots séparés par des underscore, et la clé primaire est toujours `id`</li>
	<li>Le fichier de vue associé à une méthode de contrôleur devrait être placé dans un dossier portant le même nom que le contrôleur, sans le suffixe <span class="code">Controller</span> (exemple le fichier de vue de <span class="code">PostController->showAll()</span> est <span class="code">app/Views/post/show_all.php</span>)</li>
	<li>Le fichier de vue devrait être nommé exactement comme la méthode de contrôleur y menant, en minuscules et underscores.</li>
</ul>