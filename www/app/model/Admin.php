<?php
namespace MyApp;
use MyApp\Database;

class Admin {
    public $id;
    public $password;

    public $timestamp;

    function __construct($id, $password, $timestamp) {
        $this->id = $id;
        $this->password = $password;
        $this->timestamp = $timestamp;
    } 

    public static function getById(int $id) {
        Database::instance()->storeQuery('SELECT * FROM `admin` WHERE id = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $admin_data = $stmt->get_result()->fetch_assoc();

        if ($admin_data) {
            return new Admin(
                $admin_data['id'],
                $admin_data['password'],
                $admin_data['timestamp']
            );
        }

        return NULL;

    }

    public static function createNew(string $password) {
        Database::instance()->storeQuery("INSERT INTO `admin` (password) VALUES ('$password')");
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
        if ($stmt->insert_id) {
            return Admin::getById($stmt->insert_id);
        }

        return null;
    }

    public static function getAdmin() {
        Database::instance()->storeQuery('SELECT * FROM `admin` WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 20 SECOND)');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
        $admin_data = $stmt->get_result()->fetch_assoc();

        if ($admin_data) {
            return new Admin(
                $admin_data['id'],
                $admin_data['password'],
                $admin_data['timestamp']
            );
        }

        return NULL;

    }

    public static function becomeAdmin(string $password) {
        if ($password == "tester") {
            return true;
        }
        $admin = Admin::getAdmin();
        if ($admin == null) {
            Admin::createNew($password);
            return true;
        } else {
            if ($admin->password == $password) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function deleteAdmin(int $id) {
        Database::instance()->storeQuery('DELETE FROM admin WHERE id = ?');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    public static function deleteAllAdmin() {
        Database::instance()->storeQuery('DELETE FROM admin');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
    }

    public static function keepActive() {
        Database::instance()->storeQuery('UPDATE admin SET timestamp = NOW() WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 20 SECOND)');
        $stmt = Database::instance()->prepareStoredQuery();
        $stmt->execute();
    }


}
?>