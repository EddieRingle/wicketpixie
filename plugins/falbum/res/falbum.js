/*
Based in part on Flickr Gallery 0.7 by Ramon Darrow - http://www.worrad.com/
Based in part on DAlbum by Alexei Shamov, DeltaX Inc. - http://www.dalbum.org/

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

//-----------------------------------------------------------------------------

/*extern jQuery, nd */

if (typeof window.falbum === "undefined") {

    var falbum = function () {    
    
        var first_run = true;
    
        var prefetch_image;
        var prefetch_image_src;
        var remote_url;
        var url_root;
        
        var photo_id;
        var desc;
        var nodesc;
        var title;
        var post_value;    
            
        return {
        
            //Setters
            
            set_prefetch_image: function (v) {
                prefetch_image = v;
            },
            set_prefetch_image_src: function (v) {
                prefetch_image_src = v;
            },
            set_remote_url: function (v) {
                remote_url = v;
            },    
            set_url_root: function (v) {
                url_root = v;
            },    
            set_photo_id: function (v) {
                photo_id = v;
            },
            set_desc: function (v) {
                desc = v;
            },
            set_nodesc: function (v) {
                nodesc = v;
            },
            set_title: function (v) {
                title = v;
            },
            set_post_value: function (v) {
                post_value = v;
            },
        
        
            // Image Prefetch
        
            prefetch: function (imgsrc) {
                if (imgsrc.length > 0 && document.getElementById)    {
                    prefetch_image = new Image();
                    // Find flickr-photo object and start prefetching once its loaded
                    if (document.getElementById("flickr-photo")) {
                        prefetch_image_src = imgsrc;
                        
                        if (document.getElementById("flickr-photo").complete) {
                            prefetch_image.src = prefetch_image_src;
                        } else {
                            document.getElementById("flickr-photo").onload = (function (e) { 
                                prefetch_image.src = prefetch_image_src; 
                            });
                        }
                    }
                }
            },
            
            showExif: function (photo_id, secret, remote_url) {
                var url = remote_url + '?action=exif&photo_id=' + photo_id + '&secret=' + secret;    
                jQuery('#exif').html('Retrieving Data ...').load(url);    
            },
                        
            page_title: function (page_title) {
            
                page_title = page_title.replace(/&amp;/gi,'&');
                page_title = page_title.replace(/&lt;/gi,'<');
                page_title = page_title.replace(/&gt;/gi,'>');        
                page_title = page_title.replace(/&nbsp;/gi,' ');
                page_title = page_title.replace(/&quot;/gi,'"');
                page_title = page_title.replace(/&raquo;/gi,'»');               
                
                document.title = page_title;    
            },
            
            
            // Title / Description Edits
            makeEditable: function (id) {
                var e = jQuery('#' + id);                 
                e.click(function () {
                    falbum.edit(e);
                });             
                e.mouseover(function () {
                    falbum.showAsEditable(e);
                });             
                e.mouseout(function () {
                    falbum.showAsEditable(e, true);
                });
            },
            
            showAsEditable: function (e, clear) {
                var id = e.get(0).id;     
                if (!clear) {
                    e.addClass('falbum-editable');
                    if (id === 'falbum-photo-desc') {
                        if (nodesc !== '') {
                            e.html(nodesc);
                        }
                    }
                } else {
                    e.removeClass('falbum-editable');
                    if (id === 'falbum-photo-desc') {
                        if (nodesc !== '') {
                            e.html('&nbsp;');
                        }
                    }
                }
            },
            
            edit: function (e) {
                var textarea = '';
            
                e.hide();
                var id = e.get(0).id;
                
                if (id === 'falbum-photo-title') {
                    textarea = '<div id="' + id + '_editor"><input type="text" size="50" id="' + id + '_edit" name="' + id + '" value="' + e.html() + '" />';
                } else if (id === 'falbum-photo-desc') {
                    var t = e.html();
                    
                    if (t === nodesc) {
                        t = '';
                    }
                    
                    var re = /<br.*?>/gi;
                    
                    if (jQuery.browser === "msie") {
                        t = t.replace(re ,'\n');
                    } else {
                        t = t.replace(re ,'');
                    }
                     
                    textarea = '<div id="' + id + '_editor"><textarea id="' + id + '_edit" name="' + id + '" rows="4" cols="60">' + t + '</textarea>';
                }
                
                var button = '<br /><input id="' + id + '_save" type="button" value="SAVE" /> OR <input id="' + id + '_cancel" type="button" value="CANCEL" /></div>';
                
                e.after(textarea + button);
                       
                jQuery('#' + id + '_save').click(function () {
                    falbum.saveChanges(e);
                });
                
                jQuery('#' + id + '_cancel').click(function () {
                    falbum.cleanUp(e);
                });            
            },
            
            cleanUp: function (e, keepEditable) {
                jQuery('#' + e.get(0).id + '_editor').remove();     
                e.show();
                if (!keepEditable) {
                    falbum.showAsEditable(e, true);
                }        
            },
            
            saveChanges: function (e) {
                var id = e.get(0).id;
                var new_content = jQuery('#' + id + '_edit').val();
                 
                if (id === 'falbum-photo-desc') {
                    if (new_content !== nodesc) {
                        nodesc = '';
                    }
                }
                
                e.html("Saving...");
                falbum.cleanUp(e, true);
                
                var success = function (t) {
                    falbum.editComplete(t, e);
                };
                
                var failure = function (t) {
                    falbum.editFailed(t, e);
                };
                
                var pars = {
                    action: 'edit',
                    id: id,
                    content: new_content,
                    o_desc: desc,
                    o_title: title,
                    photo_id: photo_id
                };    
                 
                jQuery.ajax({
                    url: remote_url,
                    type: "POST",
                    data: jQuery.param(pars),
                    success: success,
                    error: failure
                });        
            },
            
            editComplete: function (t, e) {
                var id = e.get(0).id;
                     
                if (id === 'falbum-photo-desc') {
                    desc = t.responseText;
                } else {
                    title = t.responseText;
                } 
                
                e.html( t.responseText );
                falbum.showAsEditable(e, true);        
            },
            
            // Post Helper
    
            enable_post_helper: function () {
                jQuery('#falbum-post-helper-switch').click(function () {
                    falbum.show_post_helper_block();
                });
                 
                var x = document.getElementsByName("size");
                for (var i = 0; i < x.length; i++){
                    if (x[i].value === 's') {
                        x[i].checked = true;             
                    }    
                }            
            },
            
            show_post_helper_block: function () {
                jQuery('#falbum-post-helper-switch').hide();
                jQuery('#falbum-post-helper-block').show(); 
                  
                jQuery('#falbum-post-helper-block-rb').click(function () {
                    falbum.post_helper_update_value();
                });
                
                jQuery('#falbum-post-helper-block-close').click(function () {
                    falbum.show_post_helper_block_close();
                });
            },
            
            show_post_helper_block_close: function () {
                jQuery('#falbum-post-helper-switch').show();
                jQuery('#falbum-post-helper-block').hide();            
            },                                                
            
            post_helper_update_value: function () {
                var e = jQuery('#falbum-post-helper-value');
                var t = e.html();
                 
                var x = document.getElementsByName("size");
                var l = x.length;
                var size = 't';
                for (var i = 0; i < l; i++){
                    if (x[i].checked) {
                        size = x[i].value;
                        break;             
                    }    
                }
                
                x = document.getElementsByName("position");
                l = x.length; 
                var f = 'l';
                for (i = 0; i < l; i++){
                    if (x[i].checked) {
                        f = x[i].value;
                        break;             
                    }    
                }     
                
                x = document.getElementsByName("linkto");
                l = x.length; 
                var linkto = 'p';
                for (i = 0; i < l; i++){
                    if (x[i].checked) {
                        linkto = x[i].value;
                        break;             
                    }    
                }     
                     
                var re = new RegExp('(.*(j|justification)=)(l|left|r|right|c|center)(.*])', 'g');       
                t = t.replace(re, '$1' + f + '$4');
                
                re = new RegExp('(.*(s|size)=)(sq|t|s|m|l|o)(.*])', 'g');       
                t = t.replace(re, '$1' + size + '$4');
                
                re = new RegExp('(.*(l|linkto)=)(index|photo|i|p)(.*])', 'g');       
                t = t.replace(re, '$1' + linkto + '$4');
                
                e.html(t);        
            },
            
            ajax_init: function () {
                jQuery('a','#falbum').each(function (i){
                
                    if (this.href.indexOf(url_root) !== -1 && this.href.indexOf('#') !== this.href.length - 1) {
                                                
                        var fn = function () { 
                            var hash = this.href;                 
                            if (hash.indexOf("?") > -1) {                
                                hash = hash.replace(/^.*[#?]/, ''); 
                            } else {
                            
                                hash = hash.substr( hash.indexOf(url_root) + url_root.length );
                            
                                var re = new RegExp('([^/]*)?/?([^/]*)?/?([^/]*)?/?([^/]*)?/?([^/]*)?/?([^/]*)?/?([^/]*)?/?([^/]*)?/?$', '');       
                                hash = hash.replace(re, '$1=$2&$3=$4&$5=$6&$7=$8');                                
                                hash = hash.replace(/&=/g, ''); 
                                
                            }
                            jQuery.historyLoad(hash);    
                            return false;
                        };
                                    
                        jQuery(this).click(fn);                            
                            
                    }
                });        
            },
            
            ajax: function (_link) {
                jQuery("#falbum-wrapper").block('Please wait ...', { border: '1px solid #aaa', fontWeight: 'bold' });   
                jQuery("#falbum").css( {backgroundColor: '#fff', opacity: '0.5' } );
                
                //jQuery.blockUI('&nbsp;<br/>Just a moment ...<br/>&nbsp;', { backgroundColor: '#fff', border: '3px solid #aaa', fontWeight: 'bold', fontSize: '1.5em' });
              
                var reAlbum = /album[=\/]([^&]*)/; 
                var rePhoto = /photo[=\/]([^&]*)/;
                var rePage  = /page[=\/]([^&]*)/;
                var reTags  = /tags[=\/]([^&]*)/;
                var reShow  = /show[=\/]([^&]*)/;
                
                var album = reAlbum.exec(_link);
                var photo = rePhoto.exec(_link);
                var page  = rePage.exec(_link);
                var tags  = reTags.exec(_link);
                var show  = reShow.exec(_link);
                        
                //alert('1 _link -> ' + _link + '\n' + 
                //    'album -> ' + album + '\n' + 
                //    'photo -> ' + photo + '\n' + 
                //    'page  -> ' + page + '\n' + 
                //    'tags  -> ' + tags + '\n' + 
                //    'show  -> ' + show + '\n' );
                
                if (reAlbum.lastIndex > 0) {
                    album = album[1];
                }
                if (rePhoto.lastIndex > 0) {
                    photo = photo[1];
                }
                if (rePage.lastIndex > 0) {
                    page = page[1];
                }
                if (reTags.lastIndex > 0) {
                    tags = tags[1];
                }
                if (reShow.lastIndex > 0) {
                    show = show[1];
                }
                
                var complete = function (a1, a2) {
                    jQuery.unblockUI();
                };
                    
                //alert('2 _link -> ' + _link + '\n' + 
                //    'album -> ' + album + '\n' + 
                //    'photo -> ' + photo + '\n' + 
                //    'page  -> ' + page + '\n' + 
                //    'tags  -> ' + tags + '\n' + 
                //    'show  -> ' + show + '\n' );
                        
                var success = function (response){    
                
                    //jQuery("#falbum-wrapper").unblock();
                        
                    jQuery("#falbum_log").remove();            
                    var e = jQuery("#falbum-wrapper");        
                    e.attr("innerHTML", response).evalScripts();
                };
                
                var failure = function (t,t2,t3){
                    
                    //jQuery("#falbum-wrapper").unblock();
                     
                    alert('FAblum ERROR -> ' +
                           'line 1 -> ' + t2 + '\n' +
                           'line 2 -> ' + t3 + '\n'  );        
                    
                    jQuery("#falbum_log").remove();
                        
                    var e = jQuery("#falbum-wrapper");    
                    e.attr("innerHTML", t.responseText).evalScripts();
                };
                
                var pars = {
                    action: 'ajax',
                    album:  album,
                    photo:  photo,
                    page:   page,
                    show:   show,
                    tags:   tags
                };           
                  
                jQuery.ajax({
                    url:      remote_url,
                    type:     "GET",
                    data:     jQuery.param(pars),
                    dataType: "xhtml",
                    success:  success,
                    complete: complete,
                    error:    failure
                });        
            },
                                    
            pageload: function (hash) {
                //alert('hash->'+hash);
            
                // hash doesn't contain the first # character.
                if(hash) {
                    first_run = false;
                    falbum.ajax( hash );            
                } else {
                    if (first_run === false) {        
                        falbum.ajax('');
                    }            
                }        
            },
            
            
            /* Annotations */
            
            anno_init: function () {
                if (!document.getElementById ||
                !document.createElement ||
                !document.getElementsByTagName) {
                    return;
                }
                var anni = document.getElementsByTagName('img');
                for (var i=0;i<anni.length;i++) {
                    if ((anni[i].className.search(/\bannotated\b/) !== -1) &&
                    (anni[i].getAttribute('usemap') !== null)) {
                        falbum.anno_prepImage(anni[i]);
                    }
                }
            },
            
            anno_prepImage: function (img) {
                var mapName = img.getAttribute('usemap');
                var mapObj = document.getElementById('imgmap');
                var areas  = [];
                if (mapObj !== null) {
                    areas = mapObj.getElementsByTagName('area');
                }
                img.areas = [];
                for (var j=areas.length-1;j>=0;j--) {
                    if (areas[j].getAttribute('shape').toLowerCase() === 'rect') {
                        var coo = areas[j].getAttribute('coords').split(',');
                        if (coo.length !== 4) { break; }
                        var a = document.createElement('a');
                        a.associatedCoords = coo;
                        a.style.width = (parseInt(coo[2], 10) - parseInt(coo[0], 10)) + 'px';
                        a.style.height = (parseInt(coo[3], 10) - parseInt(coo[1], 10)) + 'px';
                        var thisAreaPosition = falbum.anno__getAreaPosition(img,coo);
                        a.style.left = thisAreaPosition[0] + 'px';
                        a.style.top = thisAreaPosition[1] + 'px';
                        a.className = 'annotation';
                        var href = areas[j].getAttribute('href');
                        if (href) {
                            a.href = href;
                        } else {
                            // set an explicit href, otherwise it doesn't count as a link
                            // for IE
                            //a.href = "#"+j;
                            a.href = "javascript:void(0);";
                        }
                        var s = document.createElement('span');
                        s.appendChild(document.createTextNode(''));
                        a.appendChild(s);
        
                        img.areas[img.areas.length] = a;
                        document.getElementsByTagName('body')[0].appendChild(a);
        
                        falbum.anno_addEvent(a,"mouseover",
                        function () {
                            clearTimeout(falbum.anno_hiderTimeout);
                        }
                        );
        
                        //eval("var fn"+j+" = function () {overlib( aI.getTitle("+j+"), STICKY, MOUSEOFF, BELOW, WRAP, CELLPAD, 5, FGCOLOR, '#FFFFCC', BGCOLOR, '#FFFF44', BORDER, 2, TEXTCOLOR, '#000000', TEXTSIZE, 2, TIMEOUT, 2000, DELAY, 50);}");
                        eval("var fn"+j+" = function () {overlib( falbum.anno_getTitle("+j+"), STICKY, MOUSEOFF, HAUTO, VAUTO, WRAP, CSSCLASS, TEXTFONTCLASS,'annotation-fontClass',FGCLASS,'annotation-fgClass', BGCLASS,'annotation-bgClass',CAPTIONFONTCLASS,'annotation-capfontClass', TIMEOUT, 2000, DELAY, 50);}");
        
                        falbum.anno_addEvent(a,"mouseover", eval("fn"+j));
                        falbum.anno_addEvent(a,"mouseout", function () {
                            nd();
                        });
                    }
                }
        
                falbum.anno_addEvent(img,"mouseover",falbum.anno_showAreas);
                falbum.anno_addEvent(img,"mouseout",falbum.anno_hideAreas);        
            },
            
            anno__getAreaPosition: function (img,coo) {
                var aleft = (img.offsetLeft + parseInt(coo[0], 10));
                var atop = (img.offsetTop + parseInt(coo[1], 10));
                var oo = img;
                while (oo.offsetParent) {
                    oo = oo.offsetParent;
                    aleft += oo.offsetLeft;
                    atop += oo.offsetTop;
                }
                return [aleft,atop];
            },
            
            anno__setAreas: function (t,disp) {
                if (!t || !t.areas) { return; }
                for (var i=0;i<t.areas.length;i++) {
                    t.areas[i].style.display = disp;
                }        
            },
            
            anno_showAreas: function (e) {
                clearTimeout(falbum.anno_hiderTimeout);
                var t = null;
                if (e && e.target) { t = e.target; }
                if (window.event && window.event.srcElement) { t = window.event.srcElement; }
                // Recalculate area positions
                for (var k=0;k<t.areas.length;k++) {
                    var thisAreaPosition = falbum.anno__getAreaPosition(t,t.areas[k].associatedCoords);
                    t.areas[k].style.left = thisAreaPosition[0] + 'px';
                    t.areas[k].style.top = thisAreaPosition[1] + 'px';
        
                }
                falbum.anno__setAreas(t,'block');        
            },
            
            anno_hideAreas: function (e) {
                var t = null;
                if (e && e.target) { t = e.target; }
                if (window.event && window.event.srcElement) { t = window.event.srcElement; }
                clearTimeout(falbum.anno_hiderTimeout);
                falbum.anno_hiderTimeout = setTimeout( function () { falbum.anno__setAreas(t,'none'); }, 300 );        
            },
            
            anno_addEvent: function (elm, evType, fn, useCapture) {
                // cross-browser event handling for IE5+, NS6 and Mozilla
                // By Scott Andrew
                if (elm.addEventListener){
                    elm.addEventListener(evType, fn, useCapture);
                    return true;
                } else if (elm.attachEvent){
                    var r = elm.attachEvent("on" + evType, fn);
                    return r;
                } else {
                    elm['on' + evType] = fn;
                }
                return false;        
            },                
            
            anno_getTitle: function (j) {
                var mapObj = document.getElementById('imgmap');
                var areas  = [];
                if (mapObj !== null) {
                    areas = mapObj.getElementsByTagName('area');
                }
                var t = areas[j].getAttribute('title');
                var re = /(\n|\r|\r\n)/gi;
                t=t.replace(re, "");
                
                t=t.replace(/&amp;/gi,'&');
                t=t.replace(/&lt;/gi,'<');
                t=t.replace(/&gt;/gi,'>');        
                t=t.replace(/&nbsp;/gi,' ');
                t=t.replace(/&quot;/gi,'"');
                
                return t;        
            }                        
            
        };
    }();

}

jQuery(document).ready(function (){
    jQuery.historyInit(falbum.pageload);
});
