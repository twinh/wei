<?php
/*
 CKFinder QWin 接口 Session 版
 
 TODO 验证时效
*/
class Qwin_CKFinder_Interface
{
	private $_upload_path;
	private $_is_checked;
	
	function __construct()
	{
		!isset($_SESSION) && session_start();
	}
	
	public function setInterface($upload_path, $is_checked)
	{
		// 设置上传路径
		$this->_upload_path = $upload_path;
		$_SESSION['_ckfinder']['upload_path'] = $upload_path;
		
		// 设置验证方法
		$this->_is_checked = $is_checked;
		$_SESSION['_ckfinder']['_is_checked'] = $is_checked;
	}
		
	public function getUploadPath()
	{
		if($this->_upload_path != '')
		{
			return $this->_upload_path;
		}
		return $_SESSION['_ckfinder']['upload_path'];
	}
	
	public function isChecked()
	{
		return $_SESSION['_ckfinder']['_is_checked'];
	}
}
?>