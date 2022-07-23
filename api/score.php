<?php
  header('Content-Type: application/json');

  // (0) ------------------------------------------------------

  $DAT_PATH = './score.dat';
  $REAL_SECRET = 'donothackmebro';
  $API_METHOD = $_SERVER['REQUEST_METHOD'];
  $NOW = new DateTime();

  function load_dat() {
    global $DAT_PATH;
    $data = array();
    $raw = trim(file_get_contents($DAT_PATH));
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

  function save_dat($raw) {
    global $DAT_PATH;
    file_put_contents($DAT_PATH, $raw);
  }

  // (1) submit score -----------------------------------------

  if ($API_METHOD == 'POST') {
    $resp = array( 'success' => false, 'new_hiscore' => false );

    // extract json data
    $data = json_decode(file_get_contents('php://input'), true);
    $token_id = $data['token_id'] ?? 0;
    $score = $data['score'] ?? 0;
    $secret = $data['secret'] ?? '';

    // if new hiscore, submit
    if ($secret == $REAL_SECRET) {

      $data = load_dat();

      // update flag
      $resp['success'] = true;

      // new hi-score
      $token = $data[$token_id];
      if (($score > $token['score']) && ($score <= 99999)) {

        // update data
        $data[$token_id]['score'] = $score;
        $data[$token_id]['ts'] = $NOW->getTimestamp();

        // write file
        $raw = array_map(function($r) {
          if (empty($r['ts'])) return $r['score'];
          else                 return $r['score'].'|'.$r['ts'];
        }, $data);
        $raw = join(',', $raw);
        save_dat($raw);

        // update flag
        $resp['new_hiscore'] = true;
      }
    }

    // return
    echo json_encode($resp);
    exit();
  }

  // (2) list top 10 ------------------------------------------

  $data = load_dat();
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
