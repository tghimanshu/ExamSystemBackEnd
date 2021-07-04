<?php

if (isset($_GET['task'])) {
	echo $_GET['task'] . 'started';
} else {
	echo $_GET['id'];
}
