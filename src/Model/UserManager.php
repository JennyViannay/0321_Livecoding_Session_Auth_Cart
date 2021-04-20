<?php

/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

/**
 *
 */
class UserManager extends AbstractManager
{
    public const TABLE = 'user';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @param array $user
     * @return int
     */
    public function insert(array $user): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`username`, `password`) VALUES (:username, :password)");
        $statement->bindValue('username', $user['username'], \PDO::PARAM_STR);
        $statement->bindValue('password', $user['password'], \PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    public function searchByUsername(string $username)
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE username = :username");
        $statement->bindValue('username', $username, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }
}
