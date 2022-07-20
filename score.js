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

  hook: (score) => {
    // TODO sync with score service
    // TODO if over service hi-score, submit
    console.log('your score is', score);
  },

};
