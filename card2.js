$(function () {
  $('.input-file').on('change',function (e) {
    // ===================================================
    // function () {
//       if (!this.files.length) {
//           return;
//       }

//       var file = $(this).prop('files')[0];
//       var fr = new FileReader();
//       $('.prev-img').css('background-image', 'none');
//       fr.onload = function() {
//           $('.prev-img').css('background-image', 'url(' + fr.result + ')');
//       }
//       fr.readAsDataURL(file);
//       $(".prev-img").css('opacity', 1);
//   }
// );
    // ==================================================
  //   ファイルオブジェクトを取得する
  //     var reader = new FileReader();

  //     reader.onload = function (e) {
  //         $(".prev-img").attr('src', e.target.result).show();
  //     }
  //     reader.readAsDataURL(e.target.files[0]);
  // });

// =======================================================
  var file = e.target.files[0];

  // ファイルリーダー作成
  var fileReader = new FileReader();
  fileReader.onload = function() {
      // Data URIを取得
      var dataUri = this.result;

      // img要素に表示
      $('.prev-img').attr('src', dataUri);
      $('.prev-img').css('display', 'block');
  }

  // ファイルをData URIとして読み込む
  fileReader.readAsDataURL(file);
    //画像でない場合は処理終了
  });

// ===========================================================
    // if (!file || file.type.indexOf('image/') < 0) {
    //   continue;
    //   // return true;
    // }else{
    //   alert("画像ファイルを指定してください。");//なければアラート
    //   return false;
    // }
    // if (file.type.indexOf("image") < 0) { //imageの文字列を検索
    //   alert("画像ファイルを指定してください。");//なければアラート
    //   return false;
    // }

  //アップロードした画像を設定する ===============================
  //   reader.onload = (function (file) {
  //     return function (e) {
  //       $(".prev-img").attr("src", e.target.result);
  //       $(".prev-img").attr("title", file.name);
  //     };
  //   })(file);
  //   reader.readAsDataURL(file);
  });
