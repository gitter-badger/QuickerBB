<?php
echo (str_replace(
array('{{HTMLLANG}}','{{TITLE}}','{{SUBTITLE}}','{{MENU}}','{{BREADCRUMB}}','{{CONTENTS}}'),
array($lang['htmllang'], $title, $subtitle, $menu, $breadcrumb, $contents),
file_get_contents('style/'.$style.'.template.php')));
exit();
?>
