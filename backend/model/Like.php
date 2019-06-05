<?php
require_once '../../config/DB.php';
class Like
{
    private $db;
    public $id;
    public $post_id;
    public $user_id;
    public $created_at;

    public function __construct()
    {
        $this->db = new DB;
    }

    public function create()
    {
        try {
            $sql = 'INSERT INTO `likes`(`post_id`, `user_id`, `created_at`) VALUES (:post_id,:user_id,:created_at)';
            $db = $this->db->openConnection();
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'post_id' => $this->post_id,
                'user_id' => $this->user_id,
                'created_at' => date("Y-m-d H:i:s"),
            ]);
            $this->db->closeConnection();
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function checkLike()
    {
        try {
            $sql = 'SELECT * FROM `likes` WHERE `user_id`=:user_id AND `post_id`=:post_id LIMIT 1';
            $db = $this->db->openConnection();
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'post_id' => $this->post_id,
                'user_id' => $this->user_id,
            ]);
            $result = $stmt->fetchObject('Like');
            if (!$result) {
                return false;
            }
            return $result;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $db = $this->db->openConnection();
            $sql = 'DELETE FROM `likes` WHERE `id`=:id';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'id' => $this->id,
            ]);
            $this->db->closeConnection();
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getPostLikes($post_id)
    {
        try {
            isset($_GET['page']) && filter_var($_GET['page'], FILTER_VALIDATE_INT) ? $page = $_GET['page'] : $page = 1;
            $page = (int) $page;
            $limit = 1; //per page
            $offset = 1 * ($page - 1);
            $db = $this->db->openConnection();
            $sql = 'SELECT * FROM `likes` WHERE `post_id`=:post_id LIMIT :offset,:limit';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'post_id' => $post_id,
                'limit' => $limit,
                'offset' => $offset,
            ]);
            $this->db->closeConnection();
            $likes = $stmt->fetchAll(PDO::FETCH_CLASS, 'Like');
            $likes_count = $this->getLikesCount($post_id);
            $result = [
                'data' => $likes,
                'current_page' => $page,
                'per_page' => $limit,
                'last_page' => ceil($likes_count / $limit),
                'total' => $likes_count,
            ];
            return $result;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getLikesCount($post_id)
    {
        try {
            $db = $this->db->openConnection();

            $sql = 'SELECT COUNT(*) FROM `likes` WHERE post_id=:post_id';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'post_id' => $post_id,
            ]);
            $this->db->closeConnection();
            $number_of_likes = $stmt->fetchColumn();
            return $number_of_likes;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
