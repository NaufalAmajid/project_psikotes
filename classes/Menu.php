<?php

class Menu
{
    private $conn;

    public function __construct()
    {
        $this->conn = DB::getInstance()->connection();
    }

    public function read($role_id)
    {

        $query = "SELECT
                    men.direktori as dir_menu,
                    sub.direktori as dir_submenu,
                    men.*,
                    sub.*,
                    ha.*
                FROM
                    menu men
                LEFT JOIN submenu sub ON
                    men.id_menu = sub.menu_id
                LEFT JOIN hak_akses ha ON
                    men.id_menu = ha.menu_id
                WHERE 
                    ha.role_id = :role_id
                ORDER BY
                    men.id_menu ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->execute();

        $menu = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (is_null($row['id_submenu'])) {
                $menu[$row['id_menu']]['nama_menu'] = $row['nama_menu'];
                $menu[$row['id_menu']]['direktori'] = $row['dir_menu'];
                $menu[$row['id_menu']]['icon'] = $row['icon'];
            } else {
                $menu[$row['id_menu']]['nama_menu'] = $row['nama_menu'];
                $menu[$row['id_menu']]['icon'] = $row['icon'];
                $menu[$row['id_menu']]['direktori_head'] = $row['dir_menu'];
                $menu[$row['id_menu']]['submenu'][] = [
                    'nama_submenu' => $row['nama_submenu'],
                    'direktori' => $row['dir_submenu'],
                ];
            }
        }

        return $menu;
    }
}
