let HI_SCORE = {

  KEY: 'APETI_DISTANCE',

  save: (distance) => {
    localStorage.setItem(HI_SCORE.KEY, distance);
    return distance;
  },

  load: _ => {
    let s = localStorage.getItem(HI_SCORE.KEY) || '0';
    return parseFloat(s);
  },

  hook: (token_id, score) => {
    console.log(`Apetimism #${token_id} got a score of ${score}`);
    dont_hack_me_bro(token_id, score);
  },

};
