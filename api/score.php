<?php
  header('Content-Type: application/json');

  // (0) ------------------------------------------------------
  $Q_MONTHLY = 'month';
  $Q_ALL_TIME = 'all';
  $DAT_MONTHLY = './month.dat';
  $DAT_ALL_TIME = './score.dat';
  $REAL_SECRET = 'donothackmebro';
  $API_METHOD = $_SERVER['REQUEST_METHOD'];

  function load_dat($src_path) {
    $data = array();
    $raw = trim(file_get_contents($src_path));
    foreach(explode(',', $raw) as $token_id => $row) {
      $rr = explode('|', $row);
      array_push($data, array(
        'token_id' => $token_id,
        'score' => $rr[0] + 0,
        'ts' => $rr[1] ?? null,
      ));
    }
    return $data;
  }

  function save_dat($src_path, $raw) {
    file_put_contents($src_path, $raw);
  }

  // (1) submit score -----------------------------------------

  function update_hi_score($token_id, $score, $src_path) {
    $now = new DateTime();
    $data = load_dat($src_path);

    // new hi-score
    $token = $data[$token_id];
    if (($score > $token['score']) && ($score <= 99999)) {

      // update data
      $data[$token_id]['score'] = $score;
      $data[$token_id]['ts'] = $now->getTimestamp();

      // write file
      $raw = array_map(function($r) {
        if (empty($r['ts'])) return $r['score'];
        else                 return $r['score'].'|'.$r['ts'];
      }, $data);
      $raw = join(',', $raw);
      save_dat($src_path, $raw);
    }
  }

  if ($API_METHOD == 'POST') {
    $resp = array( 'success' => false );

    // extract json data
    $data = json_decode(file_get_contents('php://input'), true);
    $token_id = $data['token_id'] ?? 0;
    $score = $data['score'] ?? 0;
    $secret = $data['secret'] ?? '';

    // if new hiscore, submit
    if ($secret == $REAL_SECRET) {

      // update flag
      $resp['success'] = true;

      // update hi-score
      update_hi_score($token_id, $score, $DAT_MONTHLY);
      update_hi_score($token_id, $score, $DAT_ALL_TIME);
    }

    // return
    echo json_encode($resp);
    exit();
  }

  // (2) list top 10 ------------------------------------------

  $query_path = $DAT_MONTHLY;
  $q = $_GET['q'] ?? $Q_MONTHLY;
  if ($q == $Q_ALL_TIME) $query_path = $DAT_ALL_TIME;
  $data = load_dat($query_path);
  $data = array_filter($data, function($r) { return $r['token_id'] >= 90; }); // 90-3998

  // sort by score
  array_multisort(array_column($data, 'score'), SORT_DESC, $data);

  // pick top 10
  $top10 = array_slice($data, 0, 10);

  // return
  echo json_encode(array(
    'success' => true,
    'data' => $top10,
  ));

?>
