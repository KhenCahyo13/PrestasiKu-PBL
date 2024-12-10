<?php
    $title = '401 - Unauthorized';
    ob_start();
?>
    <h1 class="my-0">401</h1>
    <p class="mb-0 mt-1">You don't have permission to access this page!</p>
<?php
    $content = ob_get_clean();
    include layouts('middleware.php')
?>