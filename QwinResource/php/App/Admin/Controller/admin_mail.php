<?php
 /**
 * Mail操作
 *
 * 使用 phpmailer发送邮件
 *
 * Copyright (c) 2009 Twin. All rights reserved.
 * 
 * LICENSE:
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2009-11-21 13:14
 * @since     2009-11-21 13:14
 * @todo      将文件操作分离开至 JQuery 插件中,或者分出接口
 */

class Controller_Admin_Mail extends QW_Controller
{
	function actionDefault()
	{
		e();
		// 加载邮件类
		qw('-ini')->loadClass(array('class.phpmailer', 'phpmailer', 'resource'));
		// 加载碎片类
		qw('-ini')->loadClass(array('clip', 'data', 'common'));

		$mail = new PHPMailer();
		// set mailer to use SMTP
		$mail->IsSMTP();
		// 设置为中文
		$mail->SetLanguage('zh_cn', RESOURCE_PATH . DS . 'php/phpmailer/language' . DS);
		// specify main and backup server
		$mail->Host = C('smtp_host');
		// turn on SMTP authentication
		$mail->SMTPAuth = true;
		// SMTP username
		$mail->Username = C('smtp_username');
		// SMTP password
		$mail->Password = C('smtp_password');
		
		$mail->From = C('smtp_from');
		$mail->FromName = C('smtp_from_name');
		$mail->AddAddress(C('smtp_to'), C('smtp_to_name'));
		// name is optional
		//$mail->AddAddress("ellen@example.com");
		$mail->AddReplyTo(C('smtp_from'), C('smtp_from_name'));
		// set word wrap to 50 characters
		$mail->WordWrap = 50;
		// add attachments
		//$mail->AddAttachment("/var/tmp/file.tar.gz");         
		// optional name
		//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");
		// set email format to HTML
		$mail->IsHTML(true);
		
		$mail->Subject = "邮件的标题";
		$mail->Body    = "邮件的内容";
		//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
		if(!$mail->Send())
		{
		   echo "Message could not be sent. <p>";
		   echo "Mailer Error: " . $mail->ErrorInfo;
		   exit;
		}
		
		echo "Message has been sent";
	}
}
