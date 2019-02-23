<?php

require_once '../../config/DB.php';

class Follower
{
    private $db;
    public $id;
    public $follower_id;
    public $user_id;
    public $created_at;

    public function __construct()
    {
        $this->db = new DB;
    }

    public function create()
    {
        try {
            $db = $this->db->openConnection();
            $sql = 'INSERT INTO `followers`(`follower_id`,`user_id`,`created_at`) VALUES (:follower_id,:user_id,:created_at)';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'follower_id' => $this->follower_id,
                'user_id' => $this->user_id,
                'created_at' => date("Y-m-d H:i:s"),
            ]);
            $this->db->closeConnection();
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function checkFollower()
    {
        try {
            $db = $this->db->openConnection();
            $sql = 'SELECT * FROM `followers` WHERE user_id=:user_id AND follower_id=:follower_id Limit 1';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'follower_id' => $this->follower_id,
                'user_id' => $this->user_id,
            ]);
            $this->db->closeConnection();
            $result = $stmt->fetchObject('Follower');
            if (!$result) {
                return false;
            }
            return $result;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getUserStatistics($user_id)
    {
        try {
            $db = $this->db->openConnection();
            $sql = 'SELECT COUNT(*) FROM `followers` WHERE user_id=:user_id';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'user_id' => $user_id,
            ]);
            $followers = $stmt->fetchColumn();

            $sql = 'SELECT COUNT(*) FROM `followers` WHERE follower_id=:follower_id';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'follower_id' => $user_id,
            ]);
            $followings = $stmt->fetchColumn();
            $this->db->closeConnection();
            $statistics = [
                'followers' => $followers,
                'followings' => $followings,
            ];
            return $statistics;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $db = $this->db->openConnection();
            $sql = 'DELETE FROM `followers` WHERE `id`=:id';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'id' => $this->id,
            ]);
            $this->db->closeConnection();
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
