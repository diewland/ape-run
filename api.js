function ajax_json(type, url, data, success_fn, failure_fn) {
  let ajax_options = {
    type: type,
    url: url,
    dataType: 'json',
    contentType: 'application/json; charset=utf-8',
    data: JSON.stringify(data),
    success: function(resp){
      if (success_fn) success_fn(resp);
    },
    failure: function(resp) {
      if(failure_fn){
        failure_fn(resp);
      }
      else {
        alert('Something went wrong');
        console.error('<ERROR>', resp);
      }
    },
  };
  return $.ajax(ajax_options);
}

// API
const SCORE_API = './api/score.php';

// 1) list top 10
function list_top10(callback) {
  return ajax_json('GET', SCORE_API, null, callback);
}

// 2) submit score
function dont_hack_me_bro(token_id, score) {
  ajax_json('POST', SCORE_API, {
    token_id: token_id,
    score: score,
    secret: "donothackmebro", // 🥺
  });
}
