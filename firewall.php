<?php
$HACKING = 0; $COUNT_BAD = 0;

if($_SERVER['REQUEST_METHOD'] == 'TRACE') { $HACKING = 1; }
if(isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) { $HACKING = 1; }
if(!is_array($GLOBALS)) { $HACKING = 1; }

$BAD_SYMBOL = array("\x27","\x22","\x60","\t",'\n','\r','\\',"'","¬","#",";","~","[","]","{","}","=","+",")","(","*","&","^","%","$","<",">","?","!",".pl",".php",'"');
$BAD_DATA   = array("UNION","OUTFILE","FROM","SELECT","WHERE","SHUTDOWN","UPDATE","DELETE","CHANGE","MODIFY","RENAME","RELOAD","ALTER","GRANT","DROP","INSERT","CONCAT","cmd","exec","--",
                    "\([^>]*\"?[^)]*\)","<[^>]*body*\"?[^>]*>","<[^>]*script*\"?[^>]*>","<[^>]*object*\"?[^>]*>","<[^>]*iframe*\"?[^>]*>","<[^>]*img*\"?[^>]*>","<[^>]*frame*\"?[^>]*>","<[^>]*applet*\"?[^>]*>","<[^>]*meta*\"?[^>]*>","<[^>]*style*\"?[^>]*>","<[^>]*form*\"?[^>]*>","<[^>]*div*\"?[^>]*>");

if(!isset($_REQUEST)) return;

function X_Tags($SRC) {
    $COUNT_BAD = 0;
    foreach($SRC as $key => $value){
        if(is_array($value)){ $COUNT_BAD = X_Tags($value); }
        else { if($value != strip_tags($value)){ $COUNT_BAD = 1;  break; } }
    } return $COUNT_BAD;
}

function X_BadData($SRC,$ROW) {
    $COUNT_BAD = 0;
    foreach($SRC as $key => $value){
        if(is_array($value)){ $COUNT_BAD = X_BadData($SRC[$key],$ROW); }
        else {
            foreach($ROW as $badkey => $badvalue){
                if(stristr($value, $badvalue) == TRUE){ $COUNT_BAD = 1;  break; }
            }
            if($COUNT_BAD == 1){ break; }
        }
    } return $COUNT_BAD;
}

function X_Clear($ROW,$CL,$SRC) {
    $SRC_T = array();
    foreach($SRC as $key => $value){
        if(is_array($value)){ $SRC_T[$key] = X_Clear($ROW,$CL,$SRC[$key]); }
        else { $SRC_T[$key] = str_replace($ROW,$CL,$SRC[$key]); }
    } return $SRC_T;
}

$COUNT_BAD = X_Tags($_GET);     if($COUNT_BAD == 1){ $HACKING = 1; }
$COUNT_BAD = X_Tags($_POST);    if($COUNT_BAD == 1){ $HACKING = 1; }
$COUNT_BAD = X_Tags($_SESSION); if($COUNT_BAD == 1){ $HACKING = 1; }
$COUNT_BAD = X_Tags($_COOKIE);  if($COUNT_BAD == 1){ $HACKING = 1; }
$COUNT_BAD = X_Tags($_ENV);     if($COUNT_BAD == 1){ $HACKING = 1; }
$COUNT_BAD = X_Tags($_FILES);   if($COUNT_BAD == 1){ $HACKING = 1; }
$COUNT_BAD = X_Tags($_REQUEST); if($COUNT_BAD == 1){ $HACKING = 1; }

$COUNT_BAD = X_BadData($_GET,$BAD_SYMBOL);     if($COUNT_BAD == 1){ $HACKING = 1; }
$COUNT_BAD = X_BadData($_POST,$BAD_SYMBOL);    if($COUNT_BAD == 1){ $HACKING = 1; }
$COUNT_BAD = X_BadData($_SESSION,$BAD_SYMBOL); if($COUNT_BAD == 1){ $HACKING = 1; }
$COUNT_BAD = X_BadData($_COOKIE,$BAD_SYMBOL);  if($COUNT_BAD == 1){ $HACKING = 1; }
$COUNT_BAD = X_BadData($_ENV,$BAD_SYMBOL);     if($COUNT_BAD == 1){ $HACKING = 1; }
$COUNT_BAD = X_BadData($_FILES,$BAD_SYMBOL);   if($COUNT_BAD == 1){ $HACKING = 1; }
$COUNT_BAD = X_BadData($_REQUEST,$BAD_SYMBOL); if($COUNT_BAD == 1){ $HACKING = 1; }

$COUNT_BAD = X_BadData($_GET,$BAD_DATA);     if($COUNT_BAD == 1){ $HACKING = 1; }
$COUNT_BAD = X_BadData($_POST,$BAD_DATA);    if($COUNT_BAD == 1){ $HACKING = 1; }
$COUNT_BAD = X_BadData($_SESSION,$BAD_DATA); if($COUNT_BAD == 1){ $HACKING = 1; }
$COUNT_BAD = X_BadData($_COOKIE,$BAD_DATA);  if($COUNT_BAD == 1){ $HACKING = 1; }
$COUNT_BAD = X_BadData($_ENV,$BAD_DATA);     if($COUNT_BAD == 1){ $HACKING = 1; }
$COUNT_BAD = X_BadData($_FILES,$BAD_DATA);   if($COUNT_BAD == 1){ $HACKING = 1; }
$COUNT_BAD = X_BadData($_REQUEST,$BAD_DATA); if($COUNT_BAD == 1){ $HACKING = 1; }

$_GET     = X_Clear($BAD_DATA,'',$_GET);
$_POST    = X_Clear($BAD_DATA,'',$_POST);
$_SESSION = X_Clear($BAD_DATA,'',$_SESSION);
$_COOKIE  = X_Clear($BAD_DATA,'',$_COOKIE);
$_ENV     = X_Clear($BAD_DATA,'',$_ENV);
$_FILES   = X_Clear($BAD_DATA,'',$_FILES);
$_REQUEST = X_Clear($BAD_DATA,'',$_REQUEST);
$_SERVER  = X_Clear($BAD_DATA,'',$_SERVER);

$_GET     = X_Clear($BAD_SYMBOL,'',$_GET);
$_POST    = X_Clear($BAD_SYMBOL,'',$_POST);
$_SESSION = X_Clear($BAD_SYMBOL,'',$_SESSION);
$_COOKIE  = X_Clear($BAD_SYMBOL,'',$_COOKIE);
$_ENV     = X_Clear($BAD_SYMBOL,'',$_ENV);
$_FILES   = X_Clear($BAD_SYMBOL,'',$_FILES);
$_REQUEST = X_Clear($BAD_SYMBOL,'',$_REQUEST);
$_SERVER  = X_Clear($BAD_SYMBOL,'',$_SERVER);
?>
