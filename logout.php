<?php

include('inc/config.php');
session_destroy();
header('Location: login?i=1');
