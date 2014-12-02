<?php
//
// Made by        db0
// Contact        db0company@gmail.com
// Website        http://db0.fr/
//

// **********************************************************************
// CONFIGURATION
// **********************************************************************

$conf = array(
              // Logo showed on top on the page.
              // External URL or local path allowed. May be empty.
              'logo' => 'https://raw.githubusercontent.com/db0company/HenTie/master/img/logo.png',

              // Title of the HTML page also showed on top of the page.
              'title' => 'Hen Tie Browser',

              // Can't be outside of the current folder (ie .. or / are forbidden).
              'path' => '.',

              // You may disable HTML headers if you wish to include
              // the browser within an other page.
              // PHP: include_once('browser.php');
              // AJAX JQuery: $.ajax('browser.php').done(function(html) {
              //                  $('#browser').html(html);
              //               });
              'html_headers' => true,

	      // On true, title and logo will be showed on top the files.
	      'show_title' => true,

              // Relative file of the file to browse.
              // On true, a login form will block access to the content.
              'auth_required' => false,

              // When authentication is required, this is the login string.
              // May be left empty to ask for a password only.
              'auth_login' => '',

              // When authentication is required, the password will be
              // checked using this hash function compared with the
              // password already hashed below.
              'auth_hash_function' => md5,
              'auth_password_hashed' => 'fc5e038d38a57032085441e7fe7010b0',

              // Will show files that start with a '.'.
              // '.' and '..' will never show.
              'show_hidden_files' => false,

              // Icons showed near file names.
              // External URLs or local paths allowed.
              'dir_icon' => 'https://raw.githubusercontent.com/db0company/HenTie/master/img/folder.png',
              'file_icon' => 'https://raw.githubusercontent.com/db0company/HenTie/master/img/file.png',
              'back_icon' => 'https://raw.githubusercontent.com/db0company/HenTie/master/img/back.png',

              // On true, will only show files that have an extension in
              // the 'allowed_extensions' array
              'restrict_extensions' => false,
              'allowed_extensions' => array('mp4', 'avi', 'mkv'),

              // Files that have this extension will never show, regardless
              // the 'restrict_extensions' above.
              'forbidden_extensions' => array('php'),

              // The files with those extensions will show the picture
              // instead of the 'file_icon' above.
              'picture_extensions' => array('png', 'jpg', 'jpeg', 'gif', 'bmp'),

              // Will show PHP Errors.
              'debug' => false,
              );

// **********************************************************************

if ($conf['debug'] === true) {
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
 } else {
  error_reporting(0);
 }
if ($conf['auth_required'] === true) {
  session_start();
 }

if ($conf['html_headers'] === true) {
  echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>'.$conf['title'].'</title><link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" type="text/css" media="screen" title="Normal" /></head><body><main class="container">';
 }

if ($conf['auth_required'] === true) {
  check_logout();
  $err = check_login();
  if (!isset($_SESSION['HenTie_login'])) {
  show_login_form($err);
  } else {
    browser();
  }
 } else {
  browser();
 }

if ($conf['html_headers'] === true) {
  echo '</main></body></html>';
 }

// **********************************************************************

function show_login_form($err) {
  global $conf;
  echo '<form method="post" class="form-signin" role="form" style="width: 40%; margin: auto; margin-top: 10%; text-align: center;">'.(empty($conf['logo']) ? '' : '<img src="'.$conf['logo'].'" style="max-width: 70%;" alt="logo">').'<h2 class="form-signin-heading">'.$conf['title'].'</h2>';
  if ($err === false) {
    echo '<div class="alert alert-danger">Check again!</div>';
  }
  if (!empty($conf['auth_login'])) {
    echo '<label for="inputLogin" class="sr-only">Login</label><input type="login" id="inputLogin" name="inputLogin" class="form-control input-lg" placeholder="Login" required autofocus><br>';
  }
  echo '<label for="inputPassword" class="sr-only">Password</label><input type="password" id="inputPassword" name="inputPassword" class="form-control input-lg" placeholder="Password" required><br><button class="btn btn-lg btn-primary btn-block" type="submit">Enter</button></form>';
}

function check_logout() {
  if (isset($_POST['inputLogout'])) {
    unset($_SESSION['HenTie_login']);
  }
}

function check_login() {
  global $conf;
  if (!empty($_POST['inputPassword'])) {
    if ((empty($conf['auth_login']) || ($conf['auth_login'] == $_POST['inputLogin']))
        && $conf['auth_hash_function']($_POST['inputPassword']) == $conf['auth_password_hashed']) {
      $_SESSION['HenTie_login'] = true;
      return true;
    }
    return false;
  }
  return true;
}

function get_extension($filename) {
  return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

function is_allowed($filename) {
  global $conf;
  $ext = get_extension($filename);
    return (!in_array($ext, $conf['forbidden_extensions'])
          && ($conf['restrict_extensions'] == false
              || in_array($ext, $conf['allowed_extensions'])));
}

function human_filesize($bytes, $decimals = 2) {
  if (!$bytes) {
    return '';
  }
  $sz = 'BKMGTP';
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

function show_file_line($icon, $path, $filename, $realpath, $target = true) {
  global $conf;
  $link = '<a href="'.$path.$filename.'" '.($target ? 'target="_blank"' : '').'>';
  echo '<tr><td>'.$link.'<img src="'.$icon.
    '" style="max-width: 30px;" alt="icon"></a></td><td>'.$link.$filename.
    '</a></td><td>'.filetype($realpath).'</td><td>',
    human_filesize(filesize($realpath)).'</td></tr>';
}

function show_dir($path = '') {
  global $conf;
  if (!($dir = opendir($conf['path'].'/'.$path))) {
    echo '<div class="alert alert-danger">Couldn\'t load directory.</div>';
  }
  while($filename = readdir($dir)) {
    $files[] = $filename;
  }
  closedir($dir);

  sort($files);

  echo ($conf['auth_required'] === true ? '<form role="logout" method="post" class="pull-right"><br><input type="submit" class="btn btn-primary" name="inputLogout" value="Logout"></form>' : '');
  if ($conf['show_title'] === true) {
    echo '<h1> '.(empty($conf['logo']) ? '' : '<img src="'.$conf['logo'].'" alt="logo" width="70">').' '.$conf['title'].'</h1>';
  }
  echo '<table style="width: 100%;" class="table table-striped table-bordered table-hover">';
  echo '<tr><th width="35"></th><th>Filename</th><th>Filetype</th><th>Filesize</th></tr>';

  $parentpath = dirname($path);
  if (!empty($path)) {
    show_file_line($conf['back_icon'],
                   ($parentpath == '.' ? '?' : '?path='.$parentpath),
                   '', '', false);
  }
  foreach ($files as $filename) {
    if (($conf['show_hidden_files'] && $filename !== '..' && $filename !== '.')
        || $filename[0] != '.') {
      $realpath = $conf['path'].'/'.(empty($path) ? '' : $path.'/').$filename;
      if (is_dir($realpath)) {
        show_file_line($conf['dir_icon'], '?path='.(empty($path) ? '' : $path.'/'), $filename, $realpath, false);
      } else if (is_allowed($realpath)) {
        show_file_line((in_array(get_extension($filename), $conf['picture_extensions']) ? $conf['path'].'/'.$path.'/'.$filename : $conf['file_icon']), $conf['path'].'/'.$path.'/', $filename, $realpath, true);
      }
    }
  }
  print("</table>\n");
}

function browser() {
  global $conf;
  if (isset($_GET['path']) &&
      !empty($_GET['path']) &&
      is_dir(getcwd().'/'.$conf['path'].'/'.$_GET['path']) &&
      $_GET['path'][0] != '.' &&
      strpos($_GET['path'], '..') === false) {
    show_dir($_GET['path']);
  } else {
    show_dir();
  }
}
