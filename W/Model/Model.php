<?php

namespace W\Model;

/**
 * Le modèle de base à étendre
 */
abstract class Model 
{

	/** @var string $table Le nom de la table */
	protected $table;

	/** @var int $primaryKey Le nom de la clef primaire (défaut id) */
	protected $primaryKey = 'id';

	/** @var \PDO $dbh Connexion à la base de données */
	protected $dbh;

	/**
	 * Constructeur
	 */
	public function __construct()
	{
		$this->setTableFromClassName();
		$this->dbh = ConnectionModel::getDbh();
	}

	/**
	 * Déduit le nom de la table en fonction du nom du modèle enfant
	 * @return W\Model $this
	 */
	private function setTableFromClassName()
	{
		$app = getApp();

		if(empty($this->table)){
			// Nom de la class enfant
			$className = (new \ReflectionClass($this))->getShortName();

			// Retire le Model et converti en underscore_case (snake_case)
			$tableName = str_replace('Model', '', $className);
			$tableName = ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $tableName)), '_');
		}
		else {
			$tableName = $this->table;
		}

		$this->table = $app->getConfig('db_table_prefix') . $tableName;

		return $this;
	}

	/**
	 * Définit le nom de la table (si le nom déduit ne convient pas)
	 * @param string $table Nom de la table
	 * @return W\Model $this
	 */
	public function setTable($table)
	{
		$this->table = $table;
		return $this;
	}

	/**
	 * Retourne le nom de la table associée à ce gestionnaire
	 * @return string Le nom de la table
	 */
	public function getTable()
	{
		return $this->table;
	}

	/**
	 * Définit le nom de la clef primaire
	 * @param string $primaryKey Nom de la clef primaire de la table
	 * @return W\Model $this
	 */
	public function setPrimaryKey($primaryKey)
	{
		$this->primaryKey = $primaryKey;
		return $this;
	}

	/**
	 * Retourne le nom de la clef primaire
	 * @return string Le nom de la clef primaire
	 */
	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}

	/**
	 * Récupère une ligne de la table en fonction d'un identifiant
	 * @param  integer Identifiant
	 * @return mixed Les données sous forme de tableau associatif
	 */
	public function find($id)
	{
		if (!is_numeric($id)){
			return false;
		}

		$sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->primaryKey .'  = :id LIMIT 1';
		$sth = $this->dbh->prepare($sql);
		$sth->bindValue(':id', $id);
		$sth->execute();

		return $sth->fetch();
	}

    /**
     * Récupère la ligne suivante de celle de l'identifiant
     * @param integer Identifiant
     * @return mixed Les données sous forme de tableau associatif
     */
    public function findNext($id){
        if (!is_numeric($id)){
            return false;
        }

        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->primaryKey .'  = (SELECT MIN(id) FROM ' . $this->table . ' WHERE id > :id ) LIMIT 1';
        $sth = $this->dbh->prepare($sql);
        $sth->bindValue(':id', $id);
        $sth->execute();

        $result = $sth->fetch();
        if(!$result) {
            $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->primaryKey .'  = (SELECT MIN(id) FROM ' . $this->table . ') LIMIT 1';
            $sth = $this->dbh->prepare($sql);
            $sth->execute();
            $result = $sth->fetch();
        }

        return $result;
    }

    /**
     * Récupère la ligne précédente de celle de l'identifiant
     * @param integer Identifiant
     * @return mixed Les données sous forme de tableau associatif
     */
    public function findPrevious($id){
        if (!is_numeric($id)){
            return false;
        }

        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->primaryKey .'  = (SELECT MAX(id) FROM ' . $this->table . ' WHERE id < :id ) LIMIT 1';
        $sth = $this->dbh->prepare($sql);
        $sth->bindValue(':id', $id);
        $sth->execute();

        $result = $sth->fetch();
        if(!$result) {
            $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->primaryKey .'  = (SELECT MAX(id) FROM ' . $this->table . ') LIMIT 1';
            $sth = $this->dbh->prepare($sql);
            $sth->execute();
            $result = $sth->fetch();
        }

        return $result;
    }

	/**
	 * Récupère toutes les lignes de la table
	 * @param $orderBy La colonne en fonction de laquelle trier
	 * @param $orderDir La direction du tri, ASC ou DESC
	 * @param $limit Le nombre maximum de résultat à récupérer
	 * @param $offset La position à partir de laquelle récupérer les résultats
	 * @return array Les données sous forme de tableau multidimensionnel
	 */
	public function findAll($orderBy = '', $orderDir = 'ASC', $limit = null, $offset = null)
	{

		$sql = 'SELECT * FROM ' . $this->table;
		if (!empty($orderBy)){

			//sécurisation des paramètres, pour éviter les injections SQL
			if(!preg_match('#^[a-zA-Z0-9_$]+$#', $orderBy)){
				die('Error: invalid orderBy param');
			}
			$orderDir = strtoupper($orderDir);
			if($orderDir != 'ASC' && $orderDir != 'DESC'){
				die('Error: invalid orderDir param');
			}
			if ($limit && !is_int($limit)){
				die('Error: invalid limit param');
			}
			if ($offset && !is_int($offset)){
				die('Error: invalid offset param');
			}

			$sql .= ' ORDER BY '.$orderBy.' '.$orderDir;
		}
        if($limit){
            $sql .= ' LIMIT '.$limit;
            if($offset){
                $sql .= ' OFFSET '.$offset;
            }
        }
		$sth = $this->dbh->prepare($sql);
		$sth->execute();

		return $sth->fetchAll();
	}

	/**
	 * Effectue une recherche
	 * @param array $data Un tableau associatif des valeurs à rechercher
	 * @param string $operator La direction du tri, AND ou OR
	 * @param boolean $stripTags Active le strip_tags automatique sur toutes les valeurs
	 * @return mixed false si erreur, le résultat de la recherche sinon
	 */
	public function search(array $search, $operator = 'OR', $stripTags = true){

		// Sécurisation de l'opérateur
		$operator = strtoupper($operator);
		if($operator != 'OR' && $operator != 'AND'){
			die('Error: invalid operator param');
		}

        $sql = 'SELECT * FROM ' . $this->table.' WHERE';
                
		foreach($search as $key => $value){
			$sql .= " `$key` LIKE :$key ";
			$sql .= $operator;
		}
		// Supprime les caractères superflus en fin de requète
		if($operator == 'OR') {
			$sql = substr($sql, 0, -3);
		}
		elseif($operator == 'AND') {
			$sql = substr($sql, 0, -4);
		}

		$sth = $this->dbh->prepare($sql);

		foreach($search as $key => $value){
			$value = ($stripTags) ? strip_tags($value) : $value;
			$sth->bindValue(':'.$key, '%'.$value.'%');
		}
		if(!$sth->execute()){
			return false;
		}
        return $sth->fetchAll();
	}

	/**
	 * Efface une ligne en fonction de son identifiant
	 * @param mixed $id L'identifiant de la ligne à effacer
	 * @return mixed La valeur de retour de la méthode execute()
	 */
	public function delete($id)
	{
		if (!is_numeric($id)){
			return false;
		}

		$sql = 'DELETE FROM ' . $this->table . ' WHERE ' . $this->primaryKey .' = :id LIMIT 1';
		$sth = $this->dbh->prepare($sql);
		$sth->bindValue(':id', $id);
		return $sth->execute();
	}

	/**
	 * Ajoute une ligne
	 * @param array $data Un tableau associatif de valeurs à insérer
	 * @param boolean $stripTags Active le strip_tags automatique sur toutes les valeurs
	 * @return mixed false si erreur, les données insérées mise à jour sinon
	 */
	public function insert(array $data, $stripTags = true)
	{

		$colNames = array_keys($data);
		$colNamesEscapes = $this->escapeKeys($colNames);
		$colNamesString = implode(', ', $colNamesEscapes);

		$sql = 'INSERT INTO ' . $this->table . ' (' . $colNamesString . ') VALUES (';
		foreach($data as $key => $value){
			$sql .= ":$key, ";
		}
		// Supprime les caractères superflus en fin de requète
		$sql = substr($sql, 0, -2);
		$sql .= ')';

		$sth = $this->dbh->prepare($sql);
		foreach($data as $key => $value){
			$value = ($stripTags) ? strip_tags($value) : $value;
			$sth->bindValue(':'.$key, $value);
		}

		if (!$sth->execute()){
			return false;
		}
		return $this->find($this->lastInsertId());
	}

	/**
	 * Modifie une ligne en fonction d'un identifiant
	 * @param array $data Un tableau associatif de valeurs à insérer
	 * @param mixed $id L'identifiant de la ligne à modifier
	 * @param boolean $stripTags Active le strip_tags automatique sur toutes les valeurs
	 * @return mixed false si erreur, les données mises à jour sinon
	 */
	public function update(array $data, $id, $stripTags = true)
	{
		if (!is_numeric($id)){
			return false;
		}
		
		$sql = 'UPDATE ' . $this->table . ' SET ';
		foreach($data as $key => $value){
			$sql .= "`$key` = :$key, ";
		}
		// Supprime les caractères superflus en fin de requète
		$sql = substr($sql, 0, -2);
		$sql .= ' WHERE ' . $this->primaryKey .' = :id';

		$sth = $this->dbh->prepare($sql);
		foreach($data as $key => $value){
			$value = ($stripTags) ? strip_tags($value) : $value;
			$sth->bindValue(':'.$key, $value);
		}
		$sth->bindValue(':id', $id);

		if(!$sth->execute()){
			return false;
		}
		return $this->find($id);
	}

	/**
	 * Retourne l'identifiant de la dernière ligne insérée
	 * @return int L'identifiant
	 */
	public function lastInsertId()
	{
		return $this->dbh->lastInsertId();
	}

	/**
	 * Echappe les clés d'un tableau pour les mots clés réservés par SQL
	 * @param array $datas Une tableau de clé
	 * @return Les clés échappées
	 */
	private function escapeKeys($datas)
	{
		return array_map(function($val){
			return '`'.$val.'`';
		}, $datas);
	}	
}