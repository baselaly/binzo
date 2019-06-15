<?php
use \Firebase\JWT\JWT;

require_once '../../config/DB.php';
require_once '../../config/JWT.php';

class User
{

    private $db;
    public $id;
    public $email;
    public $first_name;
    public $last_name;
    public $country;
    public $city;
    public $image;
    public $password;
    public $created_at;

    public function __construct()
    {
        $this->db = new DB;
    }

    public function create()
    {
        try {
            $db = $this->db->openConnection();
            $sql = 'INSERT INTO `users`(`email`,`password`,`first_name`,`last_name`,`image`,`country`,`city`,`created_at`) VALUES (:email,:password,:first_name,:last_name,:image,:country,:city,:created_at)';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'email' => $this->email,
                'password' => $this->password,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'image' => $this->image,
                'country' => $this->country,
                'city' => $this->city,
                'status'=>1,
                'created_at' => date("Y-m-d H:i:s"),
            ]);
            $this->db->closeConnection();
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function emailExists($email, $id = null)
    {
        try {
            $db = $this->db->openConnection();
            isset($id) ? $id_sql = ' AND `id`!=:id' : $id_sql = '';
            $sql = 'SELECT `email` FROM `users` WHERE `email` LIKE :email' . $id_sql;
            $stmt = $db->prepare($sql);
            $params = ['email' => $email];
            if (isset($id)) {
                $params['id'] = $id;
            }
            $stmt->execute($params);
            $this->db->closeConnection();
            $result = $stmt->fetchObject('User');
            if (!$result) {
                return false;
            }
            return true;
        } catch (PDOEXCEPTION $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function login($credentials)
    {
        try {
            $db = $this->db->openConnection();
            $sql = 'SELECT * FROM `users` WHERE `email` LIKE :email LIMIT 1';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'email' => $credentials['email'],
            ]);
            $this->db->closeConnection();
            $user = $stmt->fetchObject('User');
            if ($user && password_verify($credentials['password'], $user->password)) {
                $paylod = [
                    'iat' => time(),
                    'iss' => 'localhost',
                    'exp' => time() + 86400,
                    'userId' => $user->id,
                ];

                $jwt_token = JWT::encode($paylod);
                $user->jwt_token = $jwt_token;
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getLoggedUser()
    {
        try {
            $token = JWT::getBearerToken();
            $payload = JWT::decode($token);
            $id = $payload->userId;
            $user = $this->selectById($id);
            return $user;
        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update()
    {
        try {
            $db = $this->db->openConnection();
            $image_edit = isset($this->image) ? ',`image`=:image' : '';
            $sql = 'UPDATE `users` SET `email`=:email,`first_name`=:first_name,`last_name`=:last_name' . $image_edit . ',`country`=:country,`city`=:city WHERE id=:id';
            $stmt = $db->prepare($sql);
            $params = [
                'id' => $this->id,
                'email' => $this->email,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'country' => $this->country,
                'city' => $this->city,
            ];
            if (isset($this->image)) {
                $params['image'] = $this->image;
            }
            $stmt->execute($params);
            $this->db->closeConnection();
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update_password()
    {
        try {
            $db = $this->db->openConnection();
            $sql = 'UPDATE `users` SET `password`=:password WHERE id=:id';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'id' => $this->id,
                'password' => $this->password,
            ]);
            $this->db->closeConnection();
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function selectById($id)
    {
        try {
            $db = $this->db->openConnection();
            $sql = 'SELECT * FROM `users` WHERE id=:id LIMIT 1';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'id' => $id,
            ]);
            $this->db->closeConnection();
            $result = $stmt->fetchObject('User');
            if (!$result) {
                return false;
            }
            return $result;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getHomePosts($user_id)
    {
        try {
            isset($_GET['page']) && filter_var($_GET['page'], FILTER_VALIDATE_INT) ? $page = $_GET['page'] : $page = 1;
            $page = (int) $page;
            $limit = 10; //per page
            $offset = 10 * ($page - 1);
            $db = $this->db->openConnection();
            $sql = 'SELECT posts.created_at,posts.id,posts.body,posts.user_id,
            CONCAT(users.first_name," ",users.last_name) AS fullname, users.image FROM `users`
            INNER JOIN `posts` ON users.id=posts.user_id
            WHERE posts.user_id=:user_id
            OR posts.user_id=(SELECT `user_id` FROM `followers` WHERE `follower_id`=:userID)
            ORDER BY posts.created_at DESC LIMIT :offset,:limit';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'user_id' => $user_id,
                'userID' => $user_id,
                'limit' => $limit,
                'offset' => $offset,
            ]);
            $this->db->closeConnection();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $result;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getSimilarUsers($user){
        try {
            $db = $this->db->openConnection();
            $sql = 'SELECT * FROM `users` WHERE `id`!=:userId AND `id` NOT IN (SELECT `user_id` FROM `followers` WHERE `follower_id`=:user_id) AND (`country` LIKE :country OR `city` LIKE :city) ORDER BY `created_at` DESC LIMIT 5';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'country' => $user->country,
                'city' => $user->city,
                'userId'=>$user->id,
                'user_id'=>$user->id
            ]);
            $this->db->closeConnection();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $result;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
