<?php

require_once '../../config/DB.php';

class Post
{
    private $db;
    public $id;
    public $body;
    public $user_id;
    public $no_of_views;
    public $created_at;

    public function __construct()
    {
        $this->db = new DB;
    }

    public function create($image = null)
    {
        try {
            $db = $this->db->openConnection();
            $db->beginTransaction();

            $sql = 'INSERT INTO `posts`(`body`,`user_id`,`created_at`) VALUES (:body,:user_id,:created_at)';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'body' => $this->body,
                'user_id' => $this->user_id,
                'created_at' => date("Y-m-d H:i:s"),
            ]);

            $this->id = $db->lastInsertId();
            if (isset($image)) {
                $sql = 'INSERT INTO `post_images`(`image`,`post_id`,`created_at`) VALUES (:image,:post_id,:created_at)';
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    'image' => $image,
                    'post_id' => $this->id,
                    'created_at' => date("Y-m-d H:i:s"),
                ]);
            }
            $db->commit();
            $this->db->closeConnection();
        } catch (PDOException $e) {
            $db->rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function getUserPosts($user_id)
    {
        try {
            isset($_GET['page']) && filter_var($_GET['page'], FILTER_VALIDATE_INT) ? $page = $_GET['page'] : $page = 1;
            $page = (int) $page;
            $limit = 10; //per page
            $offset = 10 * ($page - 1);

            $db = $this->db->openConnection();
            $sql = 'SELECT * FROM `posts` WHERE user_id=:user_id LIMIT :offset,:limit';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'user_id' => $user_id,
                'offset' => $offset,
                'limit' => $limit,
            ]);
            $posts = $stmt->fetchAll(PDO::FETCH_CLASS, 'Post');

            $sql = 'SELECT COUNT(*) FROM posts WHERE user_id=:user_id';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'user_id' => $user_id,
            ]);
            $number_of_posts = $stmt->fetchColumn();

            $this->db->closeConnection();
            $result = [
                'data' => $posts,
                'current_page' => $page,
                'per_page' => $limit,
                'last_page' => ceil($number_of_posts / $limit),
                'total' => $number_of_posts,
            ];
            return $result;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function selectById($post_id)
    {
        try {
            $db = $this->db->openConnection();
            $sql = 'SELECT * FROM `posts` WHERE id=:post_id LIMIT 1';
            $stmt = $db->prepare($sql);
            $stmt->execute(['post_id' => $post_id]);
            $this->db->closeConnection();
            $result = $stmt->fetchObject('Post');
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
            $db->beginTransaction();

            $sql = 'DELETE FROM `post_images` WHERE post_id=:post_id';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'post_id' => $this->id,
            ]);

            $sql = 'DELETE FROM `likes` WHERE post_id=:id';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'id' => $this->id,
            ]);

            $sql = 'DELETE FROM `comments` WHERE post_id=:id';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'id' => $this->id,
            ]);

            $sql = 'DELETE FROM `posts` WHERE id=:id';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'id' => $this->id,
            ]);

            $db->commit();
            $this->db->closeConnection();
        } catch (PDOException $e) {
            $db->rollBack();
            throw new \Exception($e->getMessage());
        }
    }

}
