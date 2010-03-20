<!DOCTYPE html>
<html>
<head>
<title>Layout Test</title>
<style type="text/css">
html {
    text-align: center;
    width: 100%;
    background: transparent url(http://www.eddieringle.com/wp-content/themes/wicketpixie/images/backgrounds/solidwood-green.jpg) repeat-x fixed;
}
body {
    font-family: "Lucida Grande", Arial, sans-serif;
    text-align: left;
    width: 950px;
    min-width: 850px;
    margin: 0 auto;
}
h1, h2, h3, h4, h5, h6 {
    font-family: Georgia, "Times New Roman", serif;
    padding: 0;
    margin: 0;
}
#wrapper {
    margin: 0 12.5px;
    background-color: transparent;
}
#header {
}
#logo {
    float: left;
    color: #FFF;
    text-shadow: 2px 2px 2px #111;
}
#sideline {
    float: right;
    color: #FFF;
    text-shadow: 2px 2px 2px #111;
}
#body {
    clear: both;
    background-color: #000;
    padding: 0px 10px;
/*
    border-top-right-radius: 10px;
    border-top-left-radius: 0px;
    -moz-border-radius-topright: 10px;
    -moz-border-radius-topleft: 0px; */
    box-shadow: 0 0 10px #000;
    -moz-box-shadow: 0 0 10px 0 #000;
    -webkit-box-shadow: 0 0 10px 0 #000;
}
#page {
    background-color: #FFF;

}
#navigation {
    width: 100%;
    text-align: center;
    overflow: hidden;
}
#navigation ul {
    float: left;
    text-align: left;
    list-style: none;
    height: 42px;
    overflow: hidden;
    padding: 0;
    margin: 0;
}
#navigation ul li {
    float: left;
    padding: 0;
    margin: 0;
    height: 100%;
}
#navigation ul li a {
    display: block;
    color: #CCC;
    margin: 0;
    height: 100%;
    padding: 12px 10px;
    text-decoration: none;
    text-transform: uppercase;
}
#navigation ul li:hover {
    background-color: #333;
}
#navigation ul li:hover a {
    color: #FFF;
}
#page {
    background-color: #FFF;
    border-radius: 10px;
    border-top-left-radius: 0px;
    padding: 10px 15px;
}
#content {
    background-color: #fff;
    width: 55%;
    float: left;
    margin: 0;
    padding: 0;
}
.sidebar {
    max-width: 320px;
    min-width: 240px;
    padding: 0;
    margin: 0;
}
.sidebar1 {
    float: left;
}
.sidebar2 {
    float: right;
}
.left-border {
    border-left: 1px solid #999;
}
.right-border {
    border-right: 1px solid #999;
}
#footer-wrap {
    background-color: #000;
/*
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px; */
    box-shadow: 0 0 10px 0 #000;
    -moz-box-shadow: 0 0 10px 0 #000;
    -webkit-box-shadow: 0 0 10px 0 #000;
    clear: both;
}
#footer {
    color: #666;
    padding: 12.5px 10px;
    overflow: auto;
}
#footer a {
    color: #999;
    text-decoration: underline;
}
#footer a:hover {
    color: #CCC;
}
#footer .footer-left {
    float: left;
}
#footer .footer-right {
    float: right;
}
.clear {
    clear: both;
}
</style>
</head>
<body>
    <div id="wrapper">
        <div id="header-wrap">
            <div id="header">
                <div id="logo">
                    <h1>Layout Test (No Images! :D)</h1>
                </div>
                <div id="sideline">
                    <p>Bla blah blah bleh blee!</p>
                </div>
            </div>
        </div>
        <div id="body-wrap">
            <div id="body">
                <div id="navigation">
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About</a></li>
                    </ul>
                </div>
                <div id="page">
                    <div class="sidebar sidebar1">
                        <h3>Links</h3>
                        <ul>
                            <li>A</li>
                            <li>B</li>
                            <li>C</li>
                        </ul>
                    </div>
                    <div id="content">
                        <h2>This is a Post Title</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus molestie, arcu id fermentum pulvinar, elit libero gravida mauris, ac facilisis nisi ipsum sit amet tortor. Suspendisse tellus lacus, semper sit amet vestibulum tempor, aliquet ac arcu. Nullam ipsum justo, condimentum vel pulvinar nec, ullamcorper sed diam. Aenean vulputate tempor iaculis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In congue sem sem, ac sagittis nisl. Aenean ac erat quis nibh viverra lacinia sed id lectus. Nunc lacinia dapibus ultrices. Integer a dui vel quam aliquet commodo. Quisque nulla sapien, rutrum eu interdum eu, rhoncus in tellus. Cras sodales nulla a felis tincidunt vehicula. Proin lacus nulla, hendrerit ac laoreet suscipit, lobortis sit amet nulla. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Etiam blandit mauris eget lacus pulvinar scelerisque. Praesent eu arcu mauris. Donec porttitor, justo sed aliquet sagittis, felis urna tincidunt leo, id dignissim odio metus nec mi. Praesent elit nibh, suscipit nec varius vitae, tincidunt eget est.</p>
<p>Vivamus eu enim nisl, non condimentum urna. Phasellus ullamcorper porta accumsan. Donec ultrices, risus non ultrices venenatis, felis mauris semper mi, sit amet varius eros quam non nisl. Vestibulum in metus sit amet ipsum suscipit gravida. Maecenas bibendum semper sodales. Ut at ligula nec mauris tincidunt eleifend. Pellentesque nulla nibh, auctor et blandit quis, rhoncus vitae tellus. Curabitur vitae dui ante. Aenean eget lacus at ligula viverra scelerisque vel non neque. Vivamus in arcu in orci egestas ultricies sit amet eget ante. Nunc elementum turpis felis, sed mollis justo. Ut et tortor magna. Proin mollis, velit vel fringilla ultrices, odio augue adipiscing nulla, sed tempus sem arcu et enim. Sed ac laoreet libero. Integer a odio ac dolor pharetra semper id ut quam. Sed rhoncus odio ligula.</p>
<p>Sed nunc elit, mattis at sodales at, luctus eu neque. Praesent fringilla mi eu risus condimentum elementum. Integer vestibulum venenatis consectetur. Morbi at massa et turpis suscipit pretium eu nec massa. Aenean aliquet purus et neque dapibus laoreet tempus eget enim. Integer et condimentum felis. Aenean dapibus dui at eros porta eu laoreet nisl posuere. Aliquam erat volutpat. Proin quis risus elit, quis pellentesque nulla. Aenean quis molestie est. Fusce auctor, mauris vel pretium dapibus, nisi metus condimentum sem, sit amet tristique lacus lacus ut leo. Aliquam urna ipsum, convallis at viverra non, varius id justo. Nulla facilisi. Praesent eu dolor purus. Morbi et sapien porttitor lorem tempor dictum. In adipiscing fermentum nisi sed iaculis.</p>
<p>Proin in arcu vel turpis gravida hendrerit eget nec mauris. Donec tellus elit, pretium ac fermentum et, gravida vitae ante. Proin sit amet odio at enim facilisis convallis a non velit. In at turpis vel magna rutrum molestie. In nunc lacus, ornare at volutpat sit amet, volutpat vitae mi. Integer eu nulla sem, in venenatis metus. Nunc non risus non augue pellentesque dictum ac ac nunc. Sed eu facilisis odio. Etiam lectus ipsum, adipiscing tincidunt varius quis, volutpat sed magna. Maecenas purus mi, consequat a pharetra vel, condimentum vel nulla.</p>
<p>Nunc id fringilla eros. In hac habitasse platea dictumst. Pellentesque non purus sem, id tincidunt orci. Aliquam erat volutpat. Mauris eget sapien sem, interdum gravida est. Sed tincidunt ligula facilisis nulla ultrices hendrerit. Sed justo quam, volutpat vel placerat ut, fermentum vel nulla. Maecenas consectetur, nisi eu fermentum aliquet, nulla nulla commodo neque, id aliquam arcu lacus non leo. Duis feugiat porta mi nec tempor. Suspendisse varius dui id odio hendrerit vel bibendum nisl molestie. Nunc eget felis vitae odio ultricies pretium. Nam sed nulla ut orci ullamcorper adipiscing eu lobortis nulla. Curabitur ut interdum est. Sed vitae risus eget purus venenatis aliquet.</p>
                    </div>
                    <div id="sidebar sidebar2">
                        <h3>Box time!</h3>
                        <p>Sidebar on the Right y'all!</p>
                    </div>
		    <div class="clear"></div>
                </div>
            </div>
        </div>
        <div id="footer-wrap">
            <div id="footer">
                <span class="footer-left">
                    Copyright &copy; 2009 idlesoft labs
                </span>
                <span class="footer-right">
                    Powered by <a href="http://chris.pirillo.com/wicketpixie">WicketPixie</a>.
                </span>
            </div>
        </div>
    </div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="http://github.com/malsup/corner/raw/master/jquery.corner.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#body").corner("tr 10px");
    $("#footer-wrap").corner("bottom");
  });
</script>
</body>
</html>
