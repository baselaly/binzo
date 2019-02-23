<?php

require_once '../../config/DB.php';

class PostImage
{
    private $db;
    public $id;
    public $image;
    public $post_id;
    public $created_at;

    public function __construct()
    {
        $this->db = new DB;
    }

    public function create()
    {
        try {
            $db = $this->db->openConnection();
            $sql = 'INSERT INTO `post_images`(`image`,`post_id`,`created_at`) VALUES (:image,:post_id,:created_at)';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'image' => $this->image,
                'post_id' => $this->post_id,
                'created_at' => date("Y-m-d H:i:s"),
            ]);
            $this->db->closeConnection();
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getPostImage($post_id)
    {
        try {
            $db = $this->db->openConnection();
            $sql = 'SELECT * FROM `post_images` WHERE `post_id`=:post_id';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'post_id' => $post_id,
            ]);
            $this->db->closeConnection();
            $result = $stmt->fetchObject('PostImage');
            if (!$result) {
                return false;
            }
            return $result;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
