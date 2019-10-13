<?php
session_start();
echo "Logged out successfully";
session_destroy();
header("Location: http://dijkstra.cs.ttu.ee/~krjunu/prax4/");
