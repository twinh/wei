/**
 * @see http://www.iamued.com/demo/flashjs/
 */
function getFlashMovieObject(movieName)  
{
	if(window.document[movieName])
	{  
		return window.document[movieName];
	}  
	if(navigator.appName.indexOf("Microsoft Internet")==-1)
	{
		if (document.embeds && document.embeds[movieName])
			return document.embeds[movieName];
   } else // if (navigator.appName.indexOf("Microsoft Internet")!=-1) 
   {  
		return document.getElementById(movieName);  
	}  
} 
 
function StopFlashMovie(obj)
{
	var flashMovie=getFlashMovieObject(obj);
	flashMovie.StopPlay();
}

function PlayFlashMovie(obj)
{
	var flashMovie=getFlashMovieObject(obj);
	flashMovie.Play();
	//embed.nativeProperty.anotherNativeMethod(obj);
}

function RewindFlashMovie(obj)
{
	var flashMovie=getFlashMovieObject(obj);
	flashMovie.Rewind();
}

function NextFrameFlashMovie(obj)
{
	var flashMovie=getFlashMovieObject(obj);
	// 4 is the index of the property for _currentFrame
	var currentFrame=flashMovie.TGetProperty("/", 4);
	var nextFrame=parseInt(currentFrame);
	if (nextFrame>=9)
		nextFrame=0;
	flashMovie.GotoFrame(nextFrame);		
}


function ZoominFlashMovie(obj)
{
	var flashMovie=getFlashMovieObject(obj);
	flashMovie.Zoom(90);
}

function ZoomoutFlashMovie(obj)
{
	var flashMovie=getFlashMovieObject(obj);
	flashMovie.Zoom(110);
}


function SendDataToFlashMovie(obj)
{
	var flashMovie=getFlashMovieObject(obj);
	flashMovie.SetVariable("/:mytext", document.getElementById("data").value);
}

function ReceiveDataFromFlashMovie(obj)
{
	var flashMovie=getFlashMovieObject(obj);
	document.getElementById("data").value=flashMovie.GetVariable("/:mytext");
	//document.controller.Data.value=message;
}