<?php
    function renderComponent(mixed $component, array $props = []) {
        extract($props);
        include components($component . '.php');
    }
?>