<?php
    namespace App\Controllers;
    abstract class Controller {
        // For get all data
        abstract function index();

        // For get data by id
        abstract function show($id);

        // For create data
        abstract function store();

        // For update data
        abstract function update($id);

        // For delete data
        abstract function destroy($id);
    }
?>