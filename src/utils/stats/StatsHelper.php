<?php

include_once __DIR__ . "/../../controllers/UserController.php";

class StatsHelper
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = (new Database())->get_handle();
    }

    public function add_view()
    {
        echo "lol";
        $query = $this->db->prepare("
            INSERT INTO page_views(ip)
            VALUES (?);
        ");
        $ip = $_SERVER['REMOTE_ADDR'];
        $query->bind_param("s", $ip);
        $query->execute();
        $query->close();
    }

    public function update_visits_count()
    {
        // Last visit was more than 30m ago => +1 visits
    }

    public function get_views_count_today()
    {
        // Each page view/refresh => +1 views
        $query = $this->db->prepare("
            SELECT COUNT(*)
            FROM page_views
            WHERE YEAR(date) = YEAR(CURRENT_DATE) AND MONTH(date) = MONTH(CURRENT_DATE) AND 
                  DAY(date) = DAY(CURRENT_DATE);
        ");
        $query->execute();

        $query->store_result();
        $query->bind_result($views_count);
        $query->fetch();

        $query->close();

        return $views_count;
    }

    public function get_unique_visitors_today()
    {
        // Unique IPs only
        $query = $this->db->prepare("
            SELECT COUNT(*)
            FROM (SELECT date
                  FROM page_views
                  WHERE YEAR(date) = YEAR(CURRENT_DATE) AND MONTH(date) = MONTH(CURRENT_DATE) AND 
                  DAY(date) = DAY(CURRENT_DATE)
                  GROUP BY ip) x;
        ");
        $query->execute();

        $query->store_result();
        $query->bind_result($views_count);
        $query->fetch();

        $query->close();

        return $views_count;
    }
}

?>