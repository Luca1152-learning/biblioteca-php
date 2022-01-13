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
        $query = $this->db->prepare("
            INSERT INTO page_views(ip)
            VALUES (?);
        ");
        $ip = $_SERVER['REMOTE_ADDR'];
        $query->bind_param("s", $ip);
        $query->execute();
        $query->close();
    }

    public function add_visit_if_necessary()
    {
        // Last visit was more than 30m ago => +1 visits

        // Get user's last visit time, if today & at least 30 minutes ago
        $query = $this->db->prepare("
            SELECT date
            FROM visits
            WHERE YEAR(date) = YEAR(CURRENT_DATE) AND MONTH(date) = MONTH(CURRENT_DATE) AND 
                  DAY(date) = DAY(CURRENT_DATE) AND
                  ip = ? AND
                  TIMESTAMPDIFF(MINUTE, date, CURRENT_TIMESTAMP) <= 30
            ORDER BY date DESC
            LIMIT 1;
        ");
        $ip = $_SERVER['REMOTE_ADDR'];
        $query->bind_param("s", $ip);
        $query->execute();
        $query->store_result();

        if ($query->num_rows > 0) {
            // There's a visit in the last 30 minutes => update the visit's date
            $query->bind_result($last_visit_date);
            $query->fetch();
            $query->close();

            // Update the visit
            $query = $this->db->prepare("
                UPDATE visits
                SET date=CURRENT_TIMESTAMP
                WHERE ip = ? AND date = ?;
            ");
            $query->bind_param("ss", $ip, $last_visit_date);
            $query->execute();
            $query->close();
        } else {
            // No visit in the last 30 minutes => add a new visit
            $query = $this->db->prepare("
                INSERT INTO visits(ip)
                VALUES (?);
            ");
            $query->bind_param("s", $ip);
            $query->execute();
            $query->close();
        }
    }

    public function get_visits_count_today()
    {
        $query = $this->db->prepare("
            SELECT COUNT(*)
            FROM visits
            WHERE YEAR(date) = YEAR(CURRENT_DATE) AND MONTH(date) = MONTH(CURRENT_DATE) AND 
                  DAY(date) = DAY(CURRENT_DATE);
        ");
        $query->execute();

        $query->store_result();
        $query->bind_result($visits_count);
        $query->fetch();

        $query->close();

        return $visits_count;
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