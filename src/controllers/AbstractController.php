<?php

interface AbstractController
{
    public function get_all();

    // Suggestion: implement using get_all() and filtering by $id.
    public function get_by_id(int $id);

    public function insert($data);

    public function delete($id);

    public function update($data);
}

?>