/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
// qwin 表单的默认配置
CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	config.language = 'zh-cn';
	// EnterMode 外部定义无效?
	//config.EnterMode = 2;
	// 1 表示 p, 2 表示 br
	//config.shiftEnterMode = 1;
	config.plugins = 'basicstyles,blockquote,button,clipboard,colorbutton,colordialog,contextmenu,elementspath,enterkey,entities,filebrowser,find,flash,font,format,horizontalrule,htmldataprocessor,image,indent,justify,keystrokes,link,list,maximize,newpage,pagebreak,pastefromword,pastetext,popup,preview,removeformat,save,showblocks,sourcearea,stylescombo,table,tabletools,specialchar,tab,templates,toolbar,undo,wysiwygarea,wsc';
	config.toolbar = 'Full';
	config.toolbar_Full=[
		['Source'],
		['PasteText','PasteFromWord','-','Print','Scayt'],
		['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Link','Unlink'],
		['TextColor','BGColor'],
		['Format','Font','FontSize'],
		['Maximize', 'colordialog'],
	];
};