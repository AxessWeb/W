<h2>Créer une page</h2>
<h3>Le déroulement d'une requête</h3>
<ol>
	<li>Une requête est réalisée à une URI du serveur</li>
	<li>La requête est dirigée vers le contrôleur frontal, <span class="code">public/index.php</span></li>
	<li>Le contrôleur frontal instancie un objet <span class="code">W\App</span> et l'exécute</li>
	<li>Le routeur tente de trouver une route définie par l'application, correspondant à l'URI actuelle</li>
	<li>La méthode de contrôleur associée à cette URI est exécutée</li>
	<li>Au besoin, cette méthode de contrôleur fait appel aux gestionnaires afin de manipuler des données</li>
	<li>Cette méthode affiche finalement un fichier de vue, un template</li>
</ol>

<p>Voici schématiquement comment se déroule une requête (en omettant quelques éléments) :</p>
<img src="img/parcours-requete-simple.png" alt="Parcours d'une requête" />

<p>Pour créer une page simple avec W, vous avez besoin de définir vous-mêmes 3 éléments :</p>

<ul>
	<li>Une route</li>
	<li>Une méthode de contrôleur</li>
	<li>Un template</li>
</ul>

<h3><a id="user-content-definir-une-route" class="anchor" href="#definir-une-route" aria-hidden="true"></a>Définir une route</h3>

<p>Les routes permettent de faire le lien entre l'URL et une méthode spécifique de vos contrôleurs.</p>

<p>W utilise <a href="http://altorouter.com/" title="AltoRouter">AltoRouter</a>, un composant de routage. N'hésitez pas à en consulter <a href="http://altorouter.com/usage/install.html" title="Documentation d'AltoRouter">la documentation</a>.</p>

<p>Les routes sont définies dans le fichier <span class="code">app/routes.php</span>, dans le tableau <span class="code">$w_routes</span>.</p>

<pre><code>&lt;?php

$w_routes = array(
    ['GET|POST', '/contact/', 'Default#contact', 'contact'],
);
</code></pre>

<p>Chaque route est elle-même un tableau, contenant les données suivantes : </p>
<ol>
	<li>La ou les méthodes HTTP</li>
	<li>Le pattern d'URL</li>
	<li>Le contrôleur et la méthode à appeler</li>
	<li>Le nom de la route</li>
</ol>

<p>Ainsi, si un pattern d'URL (2) est reconnu et que la méthode HTTP (1) est la bonne, la méthode du contrôleur (3) sera automatiquement exécutée. Le nom de la route (4) est utile pour générer des URL pointant vers cette route.</p>

<p>Les méthodes HTTP sont séparées par des barres verticales <span class="code">|</span>, les patterns d'URL peuvent contenir des paramètres variables (entre crochets <span class="code">[]</span>), la méthode des contrôleurs est définie sous la forme <span class="code">NomDuContrôleur#méthode</span> et le nom de la route est un simple chaîne. </p>

<p>Plus de détails sur le <a href="?p=routes" title="Les routes">chapitre dédié aux routes</a>.</p>

<h3>Créer une méthode de contrôleur</h3>

<p>Les contrôleurs doivent suivre une certaine convention : </p>

<ol>
	<li>Ils se trouvent dans le dossier <span class="code">app/Controller/</span></li>
	<li>Le nom de la classe est suffixé par <span class="code">Controller</span></li>
	<li>Ils doivent normalement hériter de <span class="code">\W\Controller\Controller</span></li>
</ol>

<pre><code>&lt;?php   
namespace Controller;

class DefaultController extends \W\Controller\Controller
{

    public function contact()
    {
        //traiter le formulaire contact ici...

        $this-&gt;show('default/contact');
    }

    //...
</code></pre>

<p>Les méthodes des contrôleurs devraient, après avoir effectuer un éventuel traitement, soit effectuer une redirection, soit afficher un template avec la méthode <span class="code">show()</span>. Cette méthode accepte deux paramètres : </p>

<ol>
	<li>Le chemin et le nom du template, sans l'extension</li>
	<li>Un tableau de variable à rendre disponible au template</li>
</ol>

<h3>Créer un template</h3>

<p>W utilise <a href="http://platesphp.com/" title="Plates">Plates</a>, un moteur de template en PHP, inspiré de <a href="http://twig.sensiolabs.org/" title="Twig">Twig</a>.</p>

<p>Pour créer un nouveau template, il suffit créer un fichier php dans le dossier <span class="code">app/Views/</span>. Par convention, on place toutefois ces fichiers dans un sous-dossier portant le nom du contrôleur (ie. dossier <span class="code">Views/admin/</span> pour les templates du contrôleur <span class="code">AdminController</span>).</p>

<pre><code>&lt;?php 
//hérite du fichier layout.php à la racine de app/Views/
$this-&gt;layout('layout')
?&gt;

&lt;?php 
//début du bloc main_content
$this-&gt;start('main_content'); ?&gt;
&lt;h1&gt;Contactez-nous !&lt;/h1&gt;

&lt;?php 
//fin du bloc
$this-&gt;stop('main_content'); ?&gt;
</code></pre>

<p>Il est habituel de n'avoir que quelques layouts (voir un seul) pour vos applications, et que vos différentes pages "héritent" de celui-ci. Voir <a href="http://platesphp.com/templates/inheritance/" title="L'héritage dans Plates">la documentation de Plates à ce sujet</a>.</p>
