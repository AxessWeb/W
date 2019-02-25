<h2>Les modèles</h2>
<h3>À quoi servent les modèles ?</h3>
<p>Les modèles (ou <span class="code">Model</span>) sont les classes responsables d'exécuter <em>les requêtes à votre base de données</em>.</p>
<p>Concrètement, chaque fois que vous souhaitez faire une requête à la base de données, vous devriez venir y créer une fonction qui s'en chargera (sauf si elle existe déjà dans les modèles de base du framework).</p>

<h3>Comment créer un nouveau modèle ?</h3> 
<p>Dans votre application, vous pourriez avoir un modèle par table MySQL (sans obligation). Chacune de ces classes devraient hériter de <span class="code">\W\Model\Model</span>, le modèle de base du framework, qui vous fera profiter de quelques méthodes utiles pour les requêtes simples à la base de données.</p>

<p>Par exemple, pour créer un modèle relié à une table commentaires nommée <span class="code">comment</span> : </p>
<pre><code>&lt;?php /* app/Model/CommentModel.php */
namespace Model;

class CommentModel extends \W\Model\Model 
{
	//Récupère les commentaires associés à un article
	public function findPostComments($postId)
	{
		//...
	}
}
</code></pre>

<p>Le nom de la table MySQL correspondante sera automatiquement définit en fonction du nom du modèle transformé en underscore_case (snake_case). Par exemple, si le modèle s'appelle <span class="code">CommentsBlogModel</span>, celui-ci cherchera une table nommée <span class="code">comments_blog</span>.</p>

<p>Il est toutefois possible de définir un nom de table manuellement à l'aide de la fonction <span class="code">$this->setTable('nom_de_table');</span> existante dans <span class="code">\W\Model\Model</span></p>


<br>

<h3>Les propriétés et méthodes héritées du Model</h3>
<p>Voici les propriétés et les méthodes les plus utiles, héritées du modèle de base. Vous devrez créer vos propres méthodes pour réaliser toutes les requêtes SQL plus complexes !</p>
<pre><code>/* W/Model/Model.php */

// Propriété contenant le nom de la table (deviné grâce au nom de votre modèle)
protected $table;

// Propriété contenant le nom de la clé primaire de la table (par défaut : 'id')
protected $primaryKey;

// Connexion à la base de données
protected $dbh;

// Définit le nom de la table (si le nom déduit ne convient pas)
public function setTable($table)

// Définit le nom de la clé primaire de la table (si ce n'est pas 'id')
public function setPrimaryKey($primaryKey)

// Récupère une ligne de la table en fonction d'un identifiant
public function find($id)

// Récupère toutes les lignes de la table
public function findAll($orderBy = "", $orderDir = "ASC", $limit = null, $offset = null)

// Effectue une recherche
// Le premier argument est un tableau associatif où la clé correspond à la colonne SQL
// Le second argument est l'opérateur OR ou AND pour la recherche
public function search(array $search, $operator = 'OR')

// Efface une ligne en fonction de son identifiant
public function delete($id)

// Ajoute une ligne
// Le premier argument est un tableau associatif de valeurs à insérer
// Retourne les données insérées (avec l'identifiant)
public function insert(array $data, $stripTags = true)

// Modifie une ligne en fonction d'un identifiant
// Le premier argument est un tableau associatif de valeurs à insérer
// Le second est l'identifiant de la ligne à modifier
// Retourne les données mises à jour
public function update(array $data, $id, $stripTags = true)

// Retourne l'identifiant de la dernière ligne insérée
public function lastInsertId()
</code></pre>

<br>

<h3>Le cas spécifique du UsersModel</h3>
<p>Puisque W a besoin lui-même d'un modèle d'utilisateur, pour les fonctionnalités de sécurité, et puisque <a href="?p=utilisateurs" title="La configuration du système d'authentification">W connait les détails de votre table d'utilisateurs</a>, vous pouvez avoir accès à quelques méthodes supplémentaires en faisant en sorte que votre modèle d'utilisateur hérite non pas du <span class="code">\W\Model\Model</span>, mais plutôt du <span class="code">\W\Model\UsersModel</span>. Cette classe hérite elle-même du modèle de base.</p>

<p>Voici les méthodes que vous fournit le <span class="code">\W\Model\UsersModel</span> : </p>
<pre><code>/* W/Model/Model.php */

// Hérite de toutes les méthodes du Model, plus : 

// Récupère un utilisateur en fonction de son email ou de son pseudo
public function getUserByUsernameOrEmail($usernameOrEmail)

// Teste si un email est présent en base de données
public function emailExists($email)

// Teste si un pseudo est présent en base de données
public function usernameExists($username)	
</code></pre>
