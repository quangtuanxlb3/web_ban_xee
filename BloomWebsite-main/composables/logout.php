<?php
session_start();
session_unset();
session_destroy();

header("Location: /BloomWebsite/tai-khoan/login?msg=logout_success");
exit;