
//
//	Resize <iframe> if it exists.
//

function viewziResizeModal()
{
	if (viewzi_timer)
	{
		clearInterval(viewzi_timer);
	}

	var new_height, old_height, new_width, old_width;

	function doIt()
	{
		if (document.getElementById('viewzi_wrapper') && document.getElementById('viewzi_wrapper').style.display !== 'none')
		{
			// document.getElementById('viewzi_viewport').style.height = '';

			new_height = document.documentElement.offsetHeight;
			new_width = document.documentElement.offsetWidth;

			if (new_height === old_height && new_width === old_width)
			{
				return;
			}

			old_height = new_height;
			old_width = new_width;

			/* Sizing for Internet Explorer. */
			if (typeof ActiveXObject !== 'undefined')
			{
				document.getElementById('viewzi_wrapper').style.width = document.documentElement.offsetWidth + 'px';
				document.getElementById('viewzi_wrapper').style.height = document.documentElement.offsetHeight + 'px';

				document.getElementById('viewzi_viewport').style.height = document.getElementById('viewzi_modal').offsetHeight - 29 + 'px';
			}
			else
			{
				document.getElementById('viewzi_viewport').style.height = document.getElementById('viewzi_viewport').offsetHeight + 'px';
			}
		}
	}

	var viewzi_timer = setInterval(doIt, 500);
}

/* Event listener. */
if (window.addEventListener)
{
	window.addEventListener('resize', viewziResizeModal, false);
}
else
{
	window.attachEvent('onresize', viewziResizeModal);
}

//
//	Hide the modal window.
//

function viewziHideModal()
{
	document.getElementById('viewzi_wrapper').style.display = 'none';
	document.getElementById('viewzi_iframe').src = '';
	document.getElementsByTagName('html')[0].style.overflow = 'auto';
	document.getElementsByTagName('html')[0].style.padding = '0px 0px 1px 0px';
}

//
//	Show modal on submit.
//

function viewziShowModal()
{
	if (document.getElementById('viewzi_input').value)
	{
		if (document.getElementById('viewzi_wrapper'))
		{
			document.getElementById('viewzi_wrapper').style.display = 'block';
		}
		else
		{
			var	modal_html =	'<div id="viewzi_overlay"></div>';
				modal_html +=	'<table id="viewzi_modal" cellspacing="0">';
				modal_html +=		'<thead>';
				modal_html +=			'<tr>';
				modal_html +=				'<td>';
				modal_html +=					'<span id="viewzi_title"><a href="http://www.viewzi.com/"></a> Search KTSA DOT COM</span>';
				modal_html +=				'</td>';
				modal_html +=				'<td>';
				modal_html +=					'<span onclick="viewziHideModal()" id="viewzi_close">CLOSE</span>';
				modal_html +=				'</td>';
				modal_html +=			'</tr>';
				modal_html +=		'</thead>';
				modal_html +=		'<tbody>';
				modal_html +=			'<tr>';
				modal_html +=				'<td colspan="2" id="viewzi_viewport">';
				modal_html +=					'<iframe id="viewzi_iframe" scrolling="no" frameborder="0"></iframe>';
				modal_html +=				'</td>';
				modal_html +=			'</tr>';
				modal_html +=		'</tbody>';
				modal_html +=	'</table>';

			var	modal_wrap = document.createElement('div');
				modal_wrap.id = 'viewzi_wrapper';
				modal_wrap.innerHTML = modal_html;

			document.body.appendChild(modal_wrap);
		}
		
		var thisdate= new Date();
		var now= thisdate.getSeconds();
		
		document.getElementById('viewzi_iframe').src = 'http://www.viewzi.com/vfp.php?vfp_id=123456789101&t='+ now +'&v=blog_view&q=' + encodeURIComponent(document.getElementById('viewzi_input').value);
		document.getElementsByTagName('html')[0].style.overflow = 'hidden';

		/* Sizing for Internet Explorer. */
		if (typeof ActiveXObject !== 'undefined')
		{
			document.getElementById('viewzi_wrapper').style.width = document.documentElement.offsetWidth + 'px';
			document.getElementById('viewzi_wrapper').style.height = document.documentElement.offsetHeight + 'px';

			document.getElementById('viewzi_viewport').style.height = document.getElementById('viewzi_modal').offsetHeight - 29 + 'px';
		}
		else
		{
			document.getElementById('viewzi_viewport').style.height = document.getElementById('viewzi_viewport').offsetHeight + 'px';
		}
	}
		document.getElementsByTagName('html')[0].style.padding = '0px';
}

document.write('<style type="text/css">html{overflow:auto}body{overflow:hidden}#viewzi_form,#viewzi_form *,#viewzi_modal,#viewzi_modal *{border:0;margin:0;outline:0;padding:0}#viewzi_input{background:#fff;border:1px solid;border-color:#000 #666 #999 #333;color:#333;font:12px Arial,sans-serif;margin:0 5px 0 0;padding:1px 3px 0;vertical-align:middle;width:140px;height:17px}#viewzi_button{background:#eee;border:1px solid;border-color:#ccc #666 #333 #999;color:#333;font:11px Arial,sans-serif;vertical-align:middle;width:60px;height:20px}#viewzi_wrapper{margin:0 auto;position:fixed;top:0;left:0;right:0;bottom:0;width:100%;height:100%;z-index:999999999}* html #viewzi_wrapper{position:absolute}#viewzi_overlay{background:#000;opacity:0.6;filter:alpha(opacity = 60);position:absolute;top:0;left:0;width:100%;height:100%;z-index:999999999}#viewzi_modal{background:#fff;border:1px solid #555;color:#444;font:bold 11px Arial,sans-serif;position:absolute;top:2%;left:5%;width:90%;height:95%;z-index:999999999}#viewzi_modal a{text-decoration:none}#viewzi_modal td{vertical-align:top}#viewzi_modal thead td{background:#ccc url(http://s.viewzi.com/images/vfp/viewzi_sprite.jpg) repeat-x 0 -26px;border-bottom:1px solid #777;height:26px}#viewzi_title{background:url(http://s.viewzi.com/images/vfp/viewzi_sprite.jpg) no-repeat;display:inline;float:left;line-height:26px;position:relative;margin:0 0 0 10px;padding:0 0 0 62px;overflow:hidden;height:26px}#viewzi_title a{font-size:0;position:absolute;top:0;left:0;text-indent:-99999px;width:52px;height:26px}#viewzi_iframe{display:block;overflow:auto;width:100%;height:100%}#viewzi_close{font-weight:bold;cursor:pointer;display:inline;float:right;line-height:26px;margin:0 10px 0 0;overflow:hidden;height:26px}</style><form id="viewzi_form" onsubmit="viewziShowModal(); this.blur(); return false;"><div><input type="text" id="viewzi_input" /><input type="submit" id="viewzi_button" value="SEARCH" /></div></form>');
// www1:production(20081115025909.u) 
