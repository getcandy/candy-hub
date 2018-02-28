Vue.filter('formatDate', function(value, passedFormat) {
  if (value) {
    var format = 'YYYY/MM/DD hh:mma';

    if (passedFormat) {
      format = passedFormat;
    }

    return moment(String(value)).format(format);
  }
});