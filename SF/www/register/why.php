<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id$

require($DOCUMENT_ROOT.'/include/pre.php');    // Initial db and session library, opens session

$LANG->loadLanguageMsg('register/register');

$HTML->header(array(title=>$LANG->getText('register_why','why_register')));

include(util_get_content('register/why'));

$HTML->footer(array());

?>

