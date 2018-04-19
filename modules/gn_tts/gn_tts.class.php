<?php
/**
* Google-notifier TTS 
* @package project
* @author Dmitriy Sannikov sannikovdi@yandex.ru
* @version 0.1 (wizard, 00:00:10 [Mar 1, 2018])
*/
//
//
class gn_tts extends module {
/**
* gn_tts
*
* Module class constructor
*
* @access private
*/
function gn_tts() {
  $this->name="gn_tts";
  $this->title="Google-Notifier TTS";
  $this->module_category="<#LANG_SECTION_APPLICATIONS#>";
  $this->checkInstalled();
}
/**
* saveParams
*
* Saving module parameters
*
* @access public
*/
function saveParams($data=0) {
 $p=array();
 if (IsSet($this->id)) {
  $p["id"]=$this->id;
 }
 if (IsSet($this->view_mode)) {
  $p["view_mode"]=$this->view_mode;
 }
 if (IsSet($this->edit_mode)) {
  $p["edit_mode"]=$this->edit_mode;
 }
 if (IsSet($this->tab)) {
  $p["tab"]=$this->tab;
 }
 return parent::saveParams($p);
}
/**
* getParams
*
* Getting module parameters from query string
*
* @access public
*/
function getParams() {
  global $id;
  global $mode;
  global $view_mode;
  global $edit_mode;
  global $tab;
  if (isset($id)) {
   $this->id=$id;
  }
  if (isset($mode)) {
   $this->mode=$mode;
  }
  if (isset($view_mode)) {
   $this->view_mode=$view_mode;
  }
  if (isset($edit_mode)) {
   $this->edit_mode=$edit_mode;
  }
  if (isset($tab)) {
   $this->tab=$tab;
  }
}
/**
* Run
*
* Description
*
* @access public
*/
function run() {
 global $session;
  $out=array();
  if ($this->action=='admin') {
   $this->admin($out);
  } else {
   $this->usual($out);
  }
  if (IsSet($this->owner->action)) {
   $out['PARENT_ACTION']=$this->owner->action;
  }
  if (IsSet($this->owner->name)) {
   $out['PARENT_NAME']=$this->owner->name;
  }
  $out['VIEW_MODE']=$this->view_mode;
  $out['EDIT_MODE']=$this->edit_mode;
  $out['MODE']=$this->mode;
  $out['ACTION']=$this->action;
  $this->data=$out;
  $p=new parser(DIR_TEMPLATES.$this->name."/".$this->name.".html", $this->data, $this);
  $this->result=$p->result;
}
/**
* BackEnd
*
* Module backend
*
* @access public
*/
function admin(&$out) {
 $this->getConfig();
 $out['GNIP']=$this->config['GNIP'];

/*
 $out['SPEAKER']=$this->config['SPEAKER'];
 $out['EMOTION']=$this->config['EMOTION'];
*/

 $out['DISABLED']=$this->config['DISABLED'];
 if ($this->view_mode=='update_settings') {
//   global $access_key;
//   $this->config['ACCESS_KEY']=$access_key;
// 	global $speaker;
//   $this->config['SPEAKER']=$speaker;
global $gnip;
   $this->config['GNIP']=$gnip;
   global $disabled;
   $this->config['DISABLED']=$disabled;
   $this->saveConfig();
   $this->redirect("?ok=1");
 }

 if ($_GET['ok']) {
  $out['OK']=1;
 }
 
 global $clean;
 if ($clean) {

//��� ������ ������ ���� ����, �� ����� ����� ���� ����� �������
//� ��������� ������� �������� google-notofier
////    array_map("unlink", glob(ROOT . "cached/voice/*_yandex.mp3"));
shell_exec("node /home/pi/google-home-notifier/example.js");
    $this->redirect("?ok=1");
 } 

 global $test;
 if ($test) {

//��� ������ ������ ���� ����, �� ����� ����� ���� ����� �������
//� ��������� ������� �������� google-notofier
////    array_map("unlink", glob(ROOT . "cached/voice/*_yandex.mp3"));
say('Женщина пригласила к себе мужчину. Наготовила, накрыла на стол. После того, как мужчина все выпил и съел, прильнула к нему: - А теперь ты мой... - Ну нет, мой сама! ');
$this->redirect("?ok=1");
 } 

}

/**
* FrontEnd
*
* Module frontend
*
* @access public
*/
function usual(&$out) {
 $this->admin($out);
}
 function processSubscription($event, &$details) {
  $this->getConfig();
  if ($event=='SAY') {
    $level=$details['level'];
    $message=$details['message'];
    

$gnip=$this->config['GNIP'];
//$gnip='192.168.1.35:8091';
//	$speaker=$this->config['SPEAKER'];
//	$emotion=$this->config['EMOTION'];
    
    if ($level >= (int)getGlobal('minMsgLevel') && $accessKey!='')
    {
$cmd="curl -X POST -d 'text=$message'  $gnip/google-home-notifier";
           try
           {
		$contents=shell_exec($cmd);
           }
           catch (Exception $e)
           {
              registerError('gn_tts', get_class($e) . ', ' . $e->getMessage());
           }
   

  }
 }}
/**
* Install
*
* Module installation routine
*
* @access private
*/
 function install($data='') {
  subscribeToEvent($this->name, 'SAY', '', 10);
  parent::install();
 }
// --------------------------------------------------------------------
}
/*
*
* TW9kdWxlIGNyZWF0ZWQgTWFyIDEzLCAyMDE2IHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/
