<?php
require_once '../../config/DB.php';
class Comment
{
    private $db;
    public $id;
    public $post_id;
    public $user_id;
    public $comment;
    public $created_at;

    public function __construct()
    {
        $this->db = new DB;
    }

    public function create()
    {
        try {
            $sql = 'INSERT INTO `comments` (`post_id` , `user_id` , `comment`, `created_at`) VALUES (:post_id,:user_id,:comment,:created_at)';
            $db = $this->db->openConnection();
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'post_id' => $this->post_id,
                'user_id' => $this->user_id,
                'comment' => $this->comment,
                'created_at' => date("Y-m-d H:i:s"),
            ]);
            $this->db->closeConnection();
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $db = $this->db->openConnection();
            $sql = 'DELETE FROM `comments` WHERE `id`=:id';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'id' => $this->id,
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
            $sql = 'SELECT * FROM `comments` WHERE `id`=:id LIMIT 1';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'id' => $id,
            ]);
            $this->db->closeConnection();
            $result = $stmt->fetchObject('Comment');
            return $result;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getPostComments($post_id)
    {
        try {
            isset($_GET['page']) && filter_var($_GET['page'], FILTER_VALIDATE_INT) ? $page = $_GET['page'] : $page = 1;
            $page = (int) $page;
            $limit = 10; //per page
            $offset = 10 * ($page - 1);
            $db = $this->db->openConnection();
            $sql = 'SELECT * FROM `comments` WHERE `post_id`=:post_id ORDER BY `created_at` DESC LIMIT :offset,:limit';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'post_id' => $post_id,
                'limit' => $limit,
                'offset' => $offset,
            ]);
            $this->db->closeConnection();
            $comments = $stmt->fetchAll(PDO::FETCH_CLASS, 'Comment');
            $comments_count = $this->getCommentsCount($post_id);
            $result = [
                'data' => $comments,
                'current_page' => $page,
                'per_page' => $limit,
                'last_page' => ceil($comments_count / $limit),
                'total' => $comments_count,
            ];
            return $result;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getCommentsCount($post_id)
    {
        try {
            $db = $this->db->openConnection();

            $sql = 'SELECT COUNT(*) FROM `comments` WHERE post_id=:post_id';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'post_id' => $post_id,
            ]);
            $this->db->closeConnection();
            $number_of_comments = $stmt->fetchColumn();
            return $number_of_comments;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
