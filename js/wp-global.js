$(document).ready(function() {
    var links = document.getElementsByTagName('a');
    var num_replies = ''.split(',');
    
    alert ("Links: " + links.length);
    
    for (var i = 0, j=0; i < links.length; i++) {
        if (links[i].indexOf('#disqus_thread') >= 0) {
            
            count = parseInt(num_replies[j]);
            if (count != undefined && !isNaN(count)) {
                if (count > 1) { links[i].innertHTML = count; }
                else if (!count) { links[i].innerHTML = "0"; }
                else { links[i].innerHTML = "1"; }
            }
            j++;
        }
    }
});

//alert("Loaded File");