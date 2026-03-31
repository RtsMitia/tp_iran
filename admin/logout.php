<?php
session_unset();
session_destroy();

header('Location: /admin/form.php');
exit;
