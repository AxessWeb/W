<h2>Les contrôleurs</h2>
<h3>À quoi servent les contrôleurs ?</h3>
<p>Les contrôleurs sont au coeur de vos applications. Ce sont eux qui traitent les requêtes et les formulaires, font appel au "modèle" pour manipuler les données, exécutent la logique applicative, et finalement, retournent des réponses (habituellement une vue, des données brutes ou une redirection) au client.</p>

<h3>Comment créer un contrôleur ?</h3>
<p>Vous pouvez (devriez) créer autant de contrôleurs que vous le souhaitez. Pour vous donner une idée, il est fréquent d'avoir autant de contrôleurs que vous avez de table dans votre base de données (bien que ce ne soit nullement une règle à appliquer strictement). Ainsi, pour un blog, il y aurait probablement, a minima, un <span class="code">PostController</span>, un <span class="code">CommentController</span> et un <span class="code">UserController</span>.</p>
<p>Toutes vos classes devraient être sous l'espace de nom <span class="code">\Controller</span> et hériter (directement ou non) de la classe <span class="code">\W\Controller\Controller</span>, afin de bénéficier des méthodes fournies.</p>

<h4>La classe W\Controller\Controller</h4>
<p>Il est essentiel de parcourir vous-même la classe <span class="code">W\Controller\Controller</span>, afin d'avoir un portrait juste de tout ce qu'elle vous offre.</p>
<p>En quelques mots, sachez qu'elle vous permet de gérer les redirections et les urls, l'envoi de réponses de vue, de pages d'erreurs et de JSON et permet également de gérer l'utilisateur connecté et l'autorisation d'accès aux pages.</p>

<h3>Créer une action</h3>
<p>Pour chacune des "pages" de vos applications, une méthode de contrôleur devrait être définie. C'est notamment pour cette raison que vous ressentirez le besoin de créer plusieurs contrôleurs, afin de "classer" vos méthodes, qui deviendront rapidement nombreuses.</p>
<p>Ces méthodes doivent être de visibilité <span class="code">public</span>, et devrait normalement se terminer par l'une des actions suivantes : </p>
<pre><code>/* app/Controller/DefaultController.php */

public function demo()
{

	//autre logique ici, puis...

	/** 
	 * Méthodes habituellement utilisées en fin de traitement du contrôleur 
	 */

	//affiche un template
	$this->show('default/demo');

	//affiche un template, tout en rendant des données disponibles dans celui-ci
	$this->show('default/demo', ['username' => 'will']);

	//redirige vers une autre page interne
	$this->redirectToRoute('nom_de_la_route');

	//redirige vers un site externe
	$this->redirect('http://lesjoiesducode.com');

	//retourne une réponse JSON
	//... $data = [];
	$this->showJson($data);

	//retourne une erreur 404
	$this->showNotFound();

	//stocke un message flash dans la session avec le type default, info, success, danger, warning
	$this->flash('Mon message', 'danger');
}

</code></pre>

<h3>Recevoir les paramètres dynamiques d'URL</h3>
<p>Si vos routes définissent des paramètres dynamiques d'URL, vous les recevrez en arguments de vos méthodes de contrôleurs. Consultez  <a href="?p=routes" title="Les routes">le chapitre sur les routes</a> pour plus de détails.</p>

<h3>Le contrôleur et la sécurité</h3>
<p>Si vous souhaitez récupérer l'utilisateur connecté depuis le contrôleur, vous pouvez le faire facilement avec la méthode <span class="code">getUser()</span>. Par exemple, dans un contrôleur fictif de back-office : </p>
<pre><code>/* app/Controller/AdminController.php */

//...

public function viewAllPosts()
{
	$connectedAdmin = $this->getUser();
	debug($connectedAdmin);

	//faire ce que vous avez à faire avec ces données utilisateurs
}
</code></pre>

<p>Si toutefois vous souhaitez restreindre l'accès à une page en fonction du rôle d'un utilisateur, utilisez plutôt la méthode <span class="code">allowTo()</span> : </p>

<pre><code>/* app/Controller/AdminController.php */

//...

public function createPost()
{
	//autorise l'accès à cette page aux utilisateurs ayant le rôle 'admin' ou 'superadmin'
	//doit normalement être placé à la première ligne de chaque méthode à protéger
	$this->allowTo(['admin', 'superadmin']);

	//reste du code...
}

public function registerAdmin()
{
	//autorise l'accès à cette page seulement aux 'superadmin'
	//le tableau n'est pas nécessaire lorsqu'il n'y a qu'un rôle à spécifier
	$this->allowTo('superadmin');

	//reste du code...
}
</code></pre>