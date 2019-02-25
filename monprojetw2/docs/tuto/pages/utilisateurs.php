<h2>Les utilisateurs et la sécurité</h2>

<p>Le framework W vous fournit quelques méthodes afin de faciliter la gestion de vos utilisateurs, la connexion et la déconnexion, ainsi que l'autorisation d'accès simple à vos ressources (pages).</p>

<p>Toutefois, puisque chaque application est différente : <em>il est toujours sous votre responsabilité de créer vos formulaires d'enregistrement, de connexion et d'oubli de mot de passe</em>.</p>

<p>L'utilisation du module de sécurité de W peut vous obliger à configurer quelques éléments supplémentaires, dans le fichier <span class="code">app/config.php</span>. Les voici : </p>
<pre><code>/* app/config.php */

//authentification, autorisation
'security_user_table' => 'users',				//nom de la table contenant les infos des utilisateurs
'security_id_property' => 'id',					//nom de la colonne pour la clé primaire
'security_username_property' => 'username',		//nom de la colonne pour le "pseudo"
'security_email_property' => 'email',			//nom de la colonne pour l'"email"
'security_password_property' => 'password',		//nom de la colonne pour le "mot de passe"
'security_role_property' => 'role',				//nom de la colonne pour le "role"

'security_login_route_name' => 'login',			//nom de la route affichant le formulaire de connexion
</code></pre>

<p>Toutes les valeurs affichées ci-dessus sont les valeurs par défaut. Si vous avez les mêmes, vous n'avez rien à configurer. Sinon, vous devez les spécifier.</p>
<p>Ainsi, W a besoin de connaître le nom de la table où vous stockez vos utilisateurs, et le nom de certaines colonnes importantes.</p>
<p>La clé de configuration <span class="code">security_login_route_name</span> fait référence au <em>nom de la route</em> où vos utilisateurs se connectent (formulaire de login). W redirigera les utilisateurs non-connectés à cette page s'ils tentent d'accéder à une ressource protégée.</p>

<h3>L'AuthentificationModel</h3>
<p>La classe <span class="code">\W\Security\AuthentificationModel</span> met à votre disposition quelques méthodes utiles pour votre système d'authentification. Voici les plus utiles en résumé : </p>
<pre><code>/* W\Security\AuthentificationModel.php */

// Vérifie qu'une combinaison d'email/username et mot de passe (en clair) sont présents en bdd et valides
// Vous devez avoir haché vos mdp avec la fonction password_hash() ou crypt() de votre côté !
// Retourne l'id de l'utilisateur si valide
public function isValidLoginInfo($usernameOrEmail, $plainPassword)

// Connecte un utilisateur
// L'argument à passer est un tableau contenant les données utilisateur
// Les données seront stockées sous la clé 'user' dans $_SESSION
public function logUserIn($user)

// Déconnecte un utilisateur
public function logUserOut()

// Utilise les données utilisateurs présentes en base pour mettre à jour les données en session
public function refreshUser()

// Créer un hash simple d'un mot de passe en utilisant l'algorithme par défaut
public function hashPassword($plainPassword)
</code></pre>

<h3>Sécuriser une page</h3>
<p>Afin de n'autoriser que certains utilisateurs à consulter une page, une méthode du <span class="code">\W\Controller\Controller</span> est à votre disposition. Ainsi, si vos souhaitez limiter l'accès à une page du back-office aux utilisateurs ayant le rôle <span class="code">admin</span> : 
<pre><code>/* app/VotreController.php */

//...
public function adminHome()
{
	$this->allowTo('admin');

	// reste du code accessible que pour les 'admin'...
}

</code></pre>

<p>La méthode <span class="code">allowTo()</span> peut recevoir un seul rôle en paramètre, ou un tableau en contenant plusieurs. La méthode utilise en interne la méthode <span class="code">isGranted()</span> de l'<span class="code">\W\Security\AuthorizationModel</span>.

<h3>Récupérer l'utilisateur connecté</h3>
<p>Si vous souhaitez gérer plus en finesse l'autorisation d'accès à une page, ou simplement consulter ou manipuler les données de l'utilisateur connecté, vous avez également accès une méthode de contrôleur simple : 
<pre><code>/* app/VotreController.php */

//...
public function adminHome()
{
	$loggedUser = $this->getUser();
	//debug($loggedUser);
}

</code></pre>

<p>Vous pouvez également accéder à l'utilisateur connecté automatiquement dans les fichiers de vue, avec la variable <span class="code">$w_user</span>.</p>

<h3>Générer une chaîne aléatoire sécuritaire</h3>
<p>Dans la classe <span class="code">\W\Security\StringUtils</span>, la méthode (statique) <span class="code">randomString($length)</span> vous permet de facilement générer une chaîne aléatoire cryptographiquement sécuritaire. Cette méthode simplifie uniquement la bibliothèque reconnue <a href="https://github.com/ircmaxell/RandomLib" title="ircmaxell RandomLib">RandomLib</a>, tout en générant des chaînes utilisables dans les URLs.</p>
<p>Utilisez l'argument <span class="code">$length</span> pour en définir la longueur : </p>
<pre><code>&lt;?php
	//génère une chaîne de 32 caractères
	$token = \W\Security\StringUtils::randomString(32);
</code></pre>