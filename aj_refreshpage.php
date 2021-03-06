<?php
  /*
  Copyright 2014 Metraware
  
  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at
  
      http://www.apache.org/licenses/LICENSE-2.0
  
  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License.
  */

    include_once('functions.php');
    include_once('lang.php');
	session_start();
  
	$_SESSION['CurrentLanguage'] = isset($_SESSION['CurrentLanguage']) ? $_SESSION['CurrentLanguage'] : autoSelectLanguage(array('fr','en','sv'),'en');
	
    header('Content-type: text/html;charset=utf-8');

    $PHP_SELF = $_SERVER['PHP_SELF'];
    $link = ConnectToDB();


    $sid = ((isset($_GET['sid'])) ? $_GET['sid'] : "");
    $rcid = ((isset($_GET['rcid'])) ? $_GET['rcid'] : "");
    
    $sql = 'UPDATE resultscreen SET panel1lastredraw='.time().' WHERE rcid='.$rcid.' AND sid='.$sid;
    mysqli_query($link, $sql);

    $sql = "SELECT refresh FROM resultscreen WHERE rcid='$rcid' AND sid='$sid'";
    $res = mysqli_query($link, $sql);
    $cinfo = mysqli_fetch_array($res);
    $refresh = $cinfo['refresh'];
    $now = time();
    
    $sql = "SELECT rcid FROM resultconfig WHERE active=1";
    $res = mysqli_query($link, $sql);
    if (mysqli_num_rows($res) > 0)
    {
      $r = mysqli_fetch_array($res);
      $rcid_active = $r['rcid'];
      if($rcid_active != $rcid)
      {
        $refresh = $now + 1;
      }
    }
    
    print '['.$refresh.', '.$now.'];';

?>