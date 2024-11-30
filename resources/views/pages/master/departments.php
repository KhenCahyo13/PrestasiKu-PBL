<?php
    $title = 'PrestasiKu - Master Departments';
    $pageTitle = 'Departments';
ob_start();
?>
    
<?php
    $content = ob_get_clean();
    include layouts('main.php')
?>