<h2>Les vues</h2>

<h3>À quoi servent les vues ? </h3>
<p>Les <em>vues</em> ou <em>templates</em> permettent de séparer le code de présentation du reste de la logique (contrôleur) ou des données (modèles). On y retrouve donc essentiellement des balises HTML et des <span class="code">echo</span> de variables PHP.</p>
<p>W utilise un moteur de template nommé <a href="http://platesphp.com/" title="Documentation de Plates">Plates</a>. Plates n'est pas très connu, mais possède un avantage non-négligeable : il utilise PHP comme langage. En effet, la plupart des autres moteurs de templates (comme Twig ou Smarty) impose l'apprentissage d'un nouveau langage. Plates est d'ailleurs fortement inspiré de Twig, le passage de l'un à l'autre se fait assez aisément.</p>

<h3>Comment créer un nouveau fichier de vue ? </h3>
<h4>Où placer ses fichiers de vues ?</h4>
<p>Donnée importante à connaître : W vous impose de placer vos fichiers de vues sous le dossier <span class="code">app/templates/</span>. Outre cette règle, vous êtes libre de faire comme bon vous semble.</p>
<p>Ceci étant, la plupart des pages de votre application devrait avoir un fichier de vue propre. Ainsi, il devrait y avoir à peu près autant de routes que de méthodes de contrôleur que de fichiers de vue dans votre application. Il est donc important de les classer un minimum, afin de s'y retrouver. Pour cette raison, W vous suggère de placer vos fichiers de vue dans des répertoires portant le même nom que son contrôleur (sans le suffixe Controller, et en minuscule). Ainsi, si vous avez un <span class="code">PostController</span> et un <span class="code">UserController</span> dans votre application, vous devriez avoir un dossier de vues nommé <span class="code">app/templates/post/</span> et un autre nommé <span class="code">app/templates/user/</span>. Ce n'est toutefois qu'une convention suggérée.</p>
<p>Les fichiers de vue doivent avoir l'extension <span class="code">.php</span>.</p>
<h4>Que contient un fichier de vue ?</h4>
<p>Au plus simple, un fichier de vue ne doit contenir qu'une page HTML complète. Lorsque votre contrôleur déclenchera l'affichage de votre fichier de vue, il enverra le contenu de celui-ci en réponse au client.</p>
<p>Le problème avec cette approche est bien sûr la duplication de code d'une page à l'autre, pour tous les nombreux éléments communs entre toutes les pages. Nous verrons plus loin comment Plates résoud d'une manière fort élégante ce problème.</p>

<h3>Les CSS, les JS et les images</h3>
<p>Tous les fichiers publics de votre application (<em>publics</em> dans le sens que vous considérez qu'un internaute doit pouvoir l'afficher directement dans son navigateur) doivent se trouver dans le dossier <span class="code">web/</span>. Autrement, le navigateur n'y aura tout simplement pas accès. Ainsi, vos fichiers .css, .js et vos images (souvent nommés <em>assets</em>) devront nécessairement y être placés.</p>
<p>En fait, W prend pour acquis que ces fichiers seront dans le sous-répertoire <span class="code">web/assets/</span>, afin de ranger plus finement ce dossier <span class="code">web/</span>.</p>
<p>À l'exception de cette convention, vous faites vos styles et votre JavaScript comme dans toutes applications classiques : W est un framework back-end, et ne se préoccuppe pas de ce que vous faites côté client.</p>

<h3>Les méthodes PHP disponibles</h3>
<p>Puisque vos fichiers de vue sont des fichiers PHP, toutes les fonctions et structures de contrôles habituelles sont disponibles.</p>
<p>Mais Plates et W ajoutent quelques méthodes utiles par défaut :</p>
<pre><code>/* app/templates/default/home.php */
&lt;!DOCTYPE html>
&lt;html lang="en">
&lt;head>
	&lt;meta charset="UTF-8">
	&lt;title>Document&lt;/title>
&lt;/head>
&lt;body>
	&lt;?php
		//alias de htmlspecialchars(), pour la protection des attaques XSS
		echo $this->e("une chaîne à échapper");

		//génère et retourne l'URL d'une route nommée
		//à utiliser pour tous les liens internes
		echo $this->url("nom_de_la_route", ["nom_du_param" => "value_du_param"]);

		//génère et retourne l'URL absolue d'un fichier présent dans le dossier web/assets/
		//le chemin à passer en argument est toujours relatif au dossier web/assets/
		echo $this->assetUrl('img/logo.png');

		//joli print_r sur la variable toujours disponible $w_user
		//elle contiendra les données sur l'utilisateur connecté, ou sera null sinon
		debug($w_user);

		// affichera le nom de la route actuelle
		echo $w_current_route;

		// affichera le nom du site défini dans le fichier de configuration
		echo $w_site_name;

	?>
&lt;/body>
&lt;/html>
</code></pre>
<h3>Passer des données à la vue</h3>
<p>Si vous avez créer des variables dans votre contrôleur auxquelles vous souhaitez accéder dans le fichier de vue, vous devez explicitement les y rendre disponible, <em>les passer à la vue</em>. Pour ce faire, dans le contrôleur, vous devez les spécifier dans un tableau, dans le second argument de la méthode <span class="code">show()</span>. Voir <a href="?p=controleurs" title="Les contrôleurs">le chapitre sur les contrôleurs</a> pour plus de détails.</p>
<pre><code> /* app/Controller/DefaultController.php */

//...
public function demo()
{
	//affiche un template, tout en rendant des données disponibles dans celui-ci
	$this->show('default/demo', ['username' => 'will']);
}
//...
</code></pre>

Et dans la vue, la variable <span class="code">$username</span> est automatiquement disponible : 
<pre><code>/* app/templates/default/demo.php */

&lt;!-- reste du code -->
&lt;p>Pseudo : &lt;?= $username ?>&lt;/p>
</code></pre>
<h3>Les layouts et l'héritage</h3>
<p>Plates fournit un excellente système pour éviter la répétition des codes HTML communs à toutes les pages de votre application. Ce système est inspiré de Twig.</p>
<h4>Le layout</h4>
<p>Tout d'abord, vous créez un fichier contenant votre squelette HTML, qui sera commun à toutes les pages de votre application. On appelle souvent ce fichier le <em>layout</em>. Il peut être sauvegardé à la racine du dossier <span class="code">app/templates/</span>, vue son importance.</p>
<pre><code>&lt;!DOCTYPE html>
&lt;html lang="fr">
&lt;head>
	&lt;meta charset="UTF-8">
	&lt;title>&lt;/title>
	&lt;link rel="stylesheet" href="&lt;?= $this->assetUrl('css/reset.css') ?>">
&lt;/head>
&lt;body>
	&lt;div class="container">
		&lt;header>
			&lt;h1>W&lt;/h1>
		&lt;/header>
		&lt;section>
			&lt;!-- CONTENU SPÉCIFIQUE À CHAQUE PAGE ICI -->
			&lt;?= $this->section('main_content') ?>
		&lt;/section>
		&lt;footer>&lt;?= date('Y') ?> W&lt;/footer>
	&lt;/div>
&lt;/body>
&lt;/html>
</code></pre>

<p>Ainsi, toutes les pages du site pourront avoir cette structure HTML. Il suffit maintenant de placer le contenu spécifique à chaque page dans cette balise <span class="code">section</span>. C'est là où opère la magie de Plates, en permettant à toutes nos pages d'<em>hériter</em> de cette structure, tout en redéfinissant le contenu des <em>sections</em>.</p>

<h4>Hériter du layout</h4>
<p>Pour qu'une page hérite de ce layout, il suffit de le spécifier au haut de la page, avec la méthode <span class="code">layout()</span> de Plates.</p>
<pre><code>/* app/templates/default/home.php */
&lt;?php $this->layout('layout'); ?>
</code></pre>
<p>Cet appel à la méthode <span class="code">layout()</span> spécifie au moteur de template que nous souhaitons hériter du contenu du fichier <span class="code">layout.php</span> (le paramètre passé à la méthode).</p>
<p>À la manière d'une classe PHP qui hérite des propriétés et des méthodes d'une classe parente, notre fichier <span class="code">home.php</span> hérite du contenu du fichier <span class="code">layout.php</span>. Mais si nous le souhaitons, nous pouvons écraser, ou redéfinir, le contenu de toutes les sections qui ont été définies par la méthode <span class="code">section()</span> du fichier parent. Dans notre exemple, la section définie dans le layout se nomme <span class="code">main_content</span>.</p>
<p>Pour délimiter le contenu que l'on souhaite placer dans cette section, on utilise les méthodes <span class="code">start()</span> et <span class="code">stop()</span>.</p>
<pre><code>/* app/templates/default/home.php */
&lt;?php $this->layout('layout'); ?>

&lt;?php $this->start('main_content') ?>
	&lt;h2>Ce texte remplace la section 'main_content' du layout !.&lt;/h2>
&lt;?php $this->stop('main_content') ?>
</code></pre>

<p>Et voilà !</p>


<h4>Définir plusieurs sections</h4>
<p>Vous l'aurez peut-être deviné : il est possible de définir autant de sections que vous le souhaitez dans le layout, et de remplacer seulement les sections que vous avez besoin de redéfinir à partir des fichiers de vue enfants.</p>
<p>Par exemple, nous pouvons créer une section qui permettrait d'ajouter des fichiers css et des fichiers js facilement, à partir de n'importe quelle page du site : </p>
<pre><code>&lt;!DOCTYPE html>
&lt;html lang="fr">
&lt;head>
	&lt;meta charset="UTF-8">
	&lt;title>&lt;/title>
	&lt;link rel="stylesheet" href="&lt;?= $this->assetUrl('css/reset.css') ?>">

	&lt;!-- PERMET D'AJOUTER DES CSS ICI -->
	&lt;?= $this->section('css') ?>
&lt;/head>
&lt;body>
	&lt;div class="container">
		&lt;header>
			&lt;h1>W&lt;/h1>
		&lt;/header>
		&lt;section>
			&lt;!-- CONTENU SPÉCIFIQUE À CHAQUE PAGE ICI -->
			&lt;?= $this->section('main_content') ?>
		&lt;/section>
		&lt;footer>&lt;?= date('Y') ?> W&lt;/footer>
	&lt;/div>

	&lt;!-- PERMET D'AJOUTER DES JS ICI -->
	&lt;?= $this->section('js') ?>
&lt;/body>
&lt;/html>
</code></pre>

<p>Et à partir de n'importe quelle page du site : </p>
<pre><code>/* app/templates/default/home.php */
&lt;?php $this->layout('layout'); ?>

&lt;?php $this->start('main_content') ?>
	&lt;h2>Ce texte remplace la section 'main_content' du layout !.&lt;/h2>
&lt;?php $this->stop('main_content') ?>

&lt;!-- Ajoute un javascript pour cette page seulement -->
&lt;?php $this->start('js') ?>
	&lt;script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js">&lt;/script>
&lt;?php $this->stop('js') ?>

&lt;!-- Ajoute un css pour cette page seulement -->
&lt;?php $this->start('css') ?>
	&lt;link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" />
&lt;?php $this->stop('css') ?>
</code></pre>

<p>Pour plus d'infos, rendez-vous sur <a href="http://platesphp.com/" title="La documentation de Plates">la documentation de Plates</a>.</p>
