<?php
    $title = '404 - Not Found';
    ob_start();
?>
    <h1 class="my-0">404</h1>
    <p class="mb-0 mt-1">The page you are looking for is not found!</p>
<?php
    $content = ob_get_clean();
    include layouts('middleware.php')
?>