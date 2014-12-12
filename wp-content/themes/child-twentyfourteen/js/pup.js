jQuery(function ($) {

        $('.assistive-text').click(function() {
            if($(document.body).hasClass('singular')){
                if(!$(this).hasClass('hilite')) {
                    $(this).addClass("hilite");
                    $('#menu-main').show();
                    $('#page').animate({'top':'200px'});
                    $('.entry-content').animate({'top':'200px'});
                } else {
                    $(this).removeClass("hilite");
                    $('#page').animate({'top':'0px'});
                    $('.entry-content').animate({'top':'0px'});
                }
            } else {
                if(!$(this).hasClass('hilite')) {
                    $(this).addClass("hilite");
                    $('#menu-main').show();
                    $('#page').animate({'top':'200px'});
                } else {
                    $(this).removeClass("hilite");
                    $('#page').animate({'top':'0px'});
                }
            }
    });
});
$(function () {
  $.getJSON(
     'http://ajax.googleapis.com/ajax/services/feed/load?callback=?',
     {
          q: 'http://www.facebook.com/feeds/page.php?id=211200032252559&format=rss20',
          v: '1.0',
          num: 1
     },
     function (data) {
            // facebookフィードから画像データを取得しサイズ変更
            var pattern = /<img(.+?)>/;
            var feeditem = data.responseData.feed.entries[0];
            var imgtag = feeditem.content.match(pattern);
            imgtag[0] = imgtag[0].replace("_s.", "_n.");

            // facebookフィードの出力
            $('#fbdata div#fbtxt').append('<a href="https://www.facebook.com/Pup.jp" target="_blank">' + imgtag[0] + '</a><p>' + feeditem.title + '</p>');

            // closeボタンで閉じる
            $("#close").click(function(e){
                 $("#fbdata").hide();
            });
     }
  );

  // ミスドの表示
  $("#fbdata").animate({ opacity: "1"});

});