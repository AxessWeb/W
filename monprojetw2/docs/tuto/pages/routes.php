<h2>Les routes</h2>
<h3>À quoi servent les routes ?</h3>
<p>Les routes permettent d'associer simplement des URL virtuelles à des pages spécifiques de votre site. Plus précisémment, elles vous permettent d'exécuter une méthode de contrôleur que vous avez choisie, en fonction d'un masque d'URL.</p>

<p>Par exemple, vous pouvez décider d'associer l'URL <span class="code">http://www.domain.com/services/</span> à la méthode de contrôleur <span class="code">services()</span>, et l'URL <span class="code">http://www.domain.com/a-propos/</span> à la méthode <span class="code">about()</span>. 

<h3>Comment créer une nouvelle route ?</h3>
<p>Toutes les routes doivent être définie dans le fichier <span class="code">app/routes.php</span>, dans le tableau <span class="code">$w_routes</span>.
<p>Chacune des routes, ou association entre une URL et une méthode de contrôleur, est définie par un tableau associatif de 4 éléments ayant ce format : </p>

<pre><code>[
	"GET", 				//la ou les méthodes HTTP de la requête
	"/services/",		//le masque d'URL à associer 
	"Default#services",	//le nom du contrôleur et le nom de la méthode à appeler
	"default_services"	//le nom de cette route-ci
]</code></pre>

<p>Cette route sera donc sélectionnée par le routeur si les conditions suivantes sont remplies : </p>
<ul>
	<li>La requête est réalisée précisémment à l'URL <span class="code">http://www.domain.com/services/</span></li>
	<li>La requête est réalisée en GET (et non en POST par exemple)</li>
</ul>
<p>Si cette route est sélectionnée, la méthode <span class="code">services()</span> du <span class="code">\Controller\DefaultController</span> sera exécutée.</p>

<p>Voyons chacun des ces éléments de définition de routes plus en détails.</p>


<h4>Élément #1 : les méthodes HTTP</h4>
<p>Le premier élément d'un tableau de route est la méthode HTTP requise afin que la route soit sélectionnée. Il est possible de spécifier plusieurs méthodes en les séparant par une barre verticale.</p>
<p>Ainsi, la route 
<pre><code>["POST", "/contact/", "Default#contact", "default_contact"],</code></pre>
sera sélectionnée que si la requête est réalisée en POST.</p>
<p>Pour que cette même route soit sélectionnée en GET <em>et/ou</em> en POST, comme par exemple si vous souhaitez afficher et traiter le formulaire dans la même méthode, utilisez la barre verticale : 
<pre><code>["GET|POST", "/contact/", "Default#contact", "default_contact"],</code></pre>
</p>


<h4>Élément #2: le masque d'URL</h4>
<p>Le masque d'URL indique à quoi doit ressembler l'URL afin que cette route soit choisie.</p>
<p>Le masque est donc une simple chaîne, commençant toujours par un <span class="code">/</span>, et dont le chemin est relatif au nom de domaine, ou au dossier `public/` si vous accédez à votre application dans un sous-dossier.</p>
<p>Ainsi, si votre site est accessible à <span class="code">http://localhost/projet/public/</span>, vos masques d'URL seront interprétés relativement au dernier <span class="code">/</span>.</p>
<p>Vous pouvez sans problème ajouter des <em>sous-dossiers virtuels</em> dans vos URLs. En fait, vous pouvez utiliser n'importe quel caractère d'URL valide : </p>
<pre><code>$w_routes = [
	["GET", "/services/", "Default#services", "default_service"],
	["GET", "/services/vente/", "Default#vente", "default_vente"],
	["GET", "/services/location-et-services/", "Default#location", "default_location"],
]</code></pre>
<p>Notez que le <span class="code">/</span> final n'est pas obligatoire, mais qu'il devra être présent dans vos URLs si vous l'avez indiqué dans vos routes.</p>

<h4>Élément #3 : la méthode du contrôleur associée</h4>
<p>La méthode de contrôleur qui sera appelée est définie sous la forme <span class="code">nomDuControleur#nomDeLaMethode</span>, soit le nom du contrôlleur (sans son suffixe <span class="code">Controller</span>), suivi d'un dièze comme séparateur, suivi du nom de la méthode à exécuter. Ainsi, si l'on souhaite appeler la méthode <span class="code">login</span> du <span class="code">\Controller\UserController</span>, nous utiliserons <span class="code">User#login</span>.</p>


<h4>Élément #4 : le nom de la route</h4>
<p>Le dernier élément de la définition d'une route est son nom. Le nom d'une route est utile pour y faire référence, par exemple lorsque l'on souhaite générer l'URL associée à cette route, ou si l'on souhaite faire une redirection vers une route spécifique.</p>
<p>Vous pouvez nommer vos routes comme bon vous semble, mais <em>chaque route doit avoir un nom unique</em>. Une technique efficace est donc de nommer les routes sous la forme <span class="code">nomducontroleur_nomdelamethode</span></p>
<p>Par convention, le nom des routes est écrit en minuscules et les mots sont séparés par des underscores.</p>


<h3>Les paramètres dynamiques</h3>
<p>Il est possible de définir des portions d'URLs dynamiques dans vos masques (éléments #2).</p>
<p>Par exemple, au lieu d'avoir des URLs de type <span class="code">http://www.domain.com/biens-en-vente/?id=456</span>, vous pourrez facilement avoir des URLs plus esthétiques, de type <span class="code">http://www.domain.com/biens-en-vente/456/</span>. Pour définir cette route : </p>

<pre><code>["GET", "/biens-en-vente/[:id]/", "Default#sellListing", "sell_listing"],</code></pre>

<p>Dans cette route, <span class="code">[:id]</span> est un paramètre d'URL dynamique. Le routeur considérera l'URL comme correspondant au masque d'URL si elle est de la forme <span class="code">/biens-en-vente/nimporte-quoi-ici/</span>.</p>

<p>Vous pouvez également définir plusieurs paramètres dynamiques. Vous devez simplement vous assurer que chacun possèdent un nom différent des autres, dans la même URL : </p>
<pre><code>["GET", "/biens-en-vente/[:country]/[:city]/[:id]/", "Default#sellListing", "sell_listing"],</code></pre>

<p>AltoRouteur permet également de spécifier une expression rationnelle à laquelle doit correspondre le paramètre dynamique pour être valide. Ainsi, si le paramètre dynamique de l'URL ne correspond pas à l'expression rationnelle spécifier dans la définition de route, la route ne sera pas exécutée. AltoRouter vous fournit en plus quelques raccourcis pour des expressions rationnelles courantes. N'hésitez pas à vous référer à la <a href="http://altorouter.com/usage/mapping-routes.html" title="Documentation d'AltoRouter sur la définition de routes">documentation d'AltoRouter</a> pour plus de détails, mais voici les expressions les plus utiles : </p>

<pre><code>[i:id] //un nombre entier, nommé 'id'
[i:postId] //un nombre entier, nommé postId
[a:country] //une chaîne alphanumérique, nommé country
[:slug] //n'importe quels caractères, nommé slug
[create|edit:action] //les chaînes 'create' ou 'edit', nommé action
[@[0-9]{4}:year] //un entier de 4 chiffres, nommé year (expression rationnelle perso, commence par un @)</code></pre>

<p>Notez que le nom des paramètres dynamiques est utile pour générer des URLs ou rediriger vers une route spécifique comprenant des paramètres. Voir les sections ci-dessous.</p>

<h4>Récupérer la valeur des paramètres dynamiques dans le contrôleur</h4>
<p>Si vous avez choisi de définir une route avec des paramètres dynamiques, il est certain que vous souhaiterez en récupérer la valeur dans votre contrôleur. Par exemple, vous souhaiterez être en mesure de récupérer l'article dont l'identifiant figure dans l'URL. Afin de rendre cette opération courante très simple, le routeur appelle vos méthodes de contrôleurs en vous passant les paramètres dynamiques en argument de votre méthode. Par exemple, pour cette route : </p>
<pre><code>["GET", "/biens-en-vente/[a:country]/[:city]/[i:id]/", "Default#sellListing", "sell_listing"],</code></pre>

<p>vous recevrez les 3 paramètres directement en argument de la méthode sellListing : </p>
<pre><code>/* app/Controller/DefaultController.php */

//..

public function sellListing($country, $city, $id)
{
	//récupérer en bdd le bien dont l'identifiant est $id...
	//...
}

</code></pre>

<h3>Générer des URLs, créer des liens et rediriger</h3>
<h4>Depuis le contrôleur</h4>
<p>Dans un contrôleur, quelques méthodes peuvent vous être utiles pour réaliser des <em>redirections</em> ou pour générer des URLs : </p>
<pre><code>/* app/Controller/DefaultController.php */

//...
class DefaultController extends \W\Controller\Controller
{
	public function test()
	{
		//du code...

		//une redirection vers l'accueil
		$this->redirectToRoute('default_home');
	}

	public function exemple()
	{
		//du code...

		//une redirection vers une route définie avec des paramètres dynamiques
		//il faut en effet spécifier la valeur des paramètres afin que le routeur puisse générer l'URL
		$this->redirectToRoute('default_details', ['id' => 35]);
	}

	public function yo()
	{
		//du code...

		//redirection vers un site externe
		$this->redirect('http://lesjoiesducode.com');
	}

	public function sendEmail()
	{
		//récupère uniquement l'URL associée à une route
		$url = $this->generateUrl('nom_de_la_route', ['slug' => 'un-slug-4949']);
	}
}
</code></pre>

<h4>Depuis les vues</h4>
<p>À partir d'un fichier de vue, vous pouvez <strong>(devriez)</strong> appeler la méthode <span class="code">$this->url()</span> pour générer une URL associée à une route : </p>

<pre><code>/* app/Views/default/home.php */
&lt;?php //... ?>

&lt;a href="&lt;?= $this->url('contact') ?>">Contact&lt;/a> | 
&lt;a href="&lt;?= $this->url('article_details', ['id' => $postId]) ?>">Détails de l'article&lt;/a>
</code></pre>