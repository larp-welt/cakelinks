<?php
    $links = $this->requestAction('/links/count');
    $users = $this->requestAction('/users/count');
    
    echo "<div class='stats'>Links: $links - User: $users</div>";
?>