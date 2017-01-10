$('.tree-toggle').click(function () {
    $(this).parent().children('ul.tree').toggle(200);
});
$(function(){
    $('.tree-toggle').parent().children('ul.tree').toggle(200);
});
$('.like').hover(function () {
    $(this).css('color','#333');
    $(this).find('i').removeClass('glyphicon-heart-empty').addClass('glyphicon-heart');
}, function () {
    $(this).css('color','#999');
    $(this).find('i').removeClass('glyphicon-heart').addClass('glyphicon-heart-empty');
});
$('.like_post').hover(function () {
    $(this).css('color','#999');
    $(this).find('i').removeClass('glyphicon-heart').addClass('glyphicon-heart-empty');
}, function () {
    $(this).css('color','#333');
    $(this).find('i').removeClass('glyphicon-heart-empty').addClass('glyphicon-heart');
});
