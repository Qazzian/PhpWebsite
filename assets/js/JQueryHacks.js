<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js" type="text/javascript"/>

$('a').each(function(){
if (this.href.match(/LinkText/)){
  $(this).css('background-color', 'pink');
}
});