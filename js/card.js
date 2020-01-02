$(function(){

  // スタイル変更 ==============================================
  var href1 = "css/mycard.css";
  var href2 = "css/card.css";
  $('.js-change1').on('click', function () {
    $('#js-change-css').attr('href', href1);
  });
  $('.js-change2').on('click', function () {
    $('#js-change-css').attr('href', href2);
  });

  // スタイル変更-2 =============================================
  $('.js-change3').on('click', function () {
    $('.card-img').toggleClass("height");
  });

  // function setHref( $href ) {
  //   $( '#js-change-css' ).attr( 'href', $href );
  //   console.log($href);
  // }
  // dragover:ファイルをドラッグして対象領域の上に来た時に発生するイベント

  // 削除ボタン ==============================================
  $('.js-delete_btn').on('click', function () {
    $('.js-modal').fadeIn(500);
  })

  // メッセージ表示 ==========================================
  var $jsShowMsg = $('#js-show-msg');
  var msg = $jsShowMsg.text();
  // debugger; // こうするとここで処理が止まる-----若しくはこれを別ファイルに書いて読み込んだほうがいい
  
  if(msg.replace(/^[\s　]+|[\s　]+$/g, "").length){
  $jsShowMsg.slideToggle('slow');
  console.info($jsShowMsg); //値をのポイントとなるものなど、logとは分けたいとき
  // console.table($xxx) --------- 配列を出したいときなど、logより見やすい
  // console.trace (); ---------  関数の呼び出し元を見たいとき
  setTimeout(function(){ $jsShowMsg.slideToggle('slow'); }, 2000);
}

  // 背景変更 =========================================
  // 透明にしたい
     $(".js-color").on("change", function(){
         $('.card').css("background-color", $('.js-color').val());
        //  $('.m-card').css("background-color", $('.js-color').val())
        // var rgb = $(this).text($('.js-color').val());
        // console.log(rgb);
        // var opa = $('.js-opacity').val().toString();
        // console.log(opa);
        //  $('.card').css("background-color", rgb+opa);
        //  $('.btn').text($('.js-color').val());//textはvalueの値を出す
        
        //  $(this).nextAll('.js-color').val(rgb+'0.5');
        });
      
      // $(".js-opacity").on("change",function(){
        // $('.card').css("opacity",$('.js-opacity').val());
      // });
});