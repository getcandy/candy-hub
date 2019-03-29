/**
 * Digs out an attribute for an object
 * @param  {[type]} 'attribute'
 * @param  {Object}
 * @return {string}
 */
Vue.filter('attribute', (obj, attr, lang, name) => {

  var handle,
      defaultChannel,
      lang = lang ? lang : locale.current();

  var defaultLocale = languages.find(lang => {
    if (lang.default) {
      return true;
    }
  });

  channels.forEach(channel => {
    // Always have this handy.
    if (channel.default) {
      defaultChannel = channel.handle;
    }
    if (name && channel.handle == name) {
      handle = channel.handle;
    }
  });

  // Straight away if there is no handle, we use the default..
  if (!handle) {
    handle = defaultChannel;
  }

  // Determine whether there is a value for this channel at the language we wanted.
  var ref = attr + '.' + handle + '.' + lang,
      data = obj.attribute_data,
      str = '';

  if (!_.get(data, ref)) {
    // That didn't work, lets try with same channel, default lang...
    ref = ref.replace(lang, defaultLocale.lang);
    if (!_.get(data, ref)) {
      // Okay, that didn't work, just get the default channel and default language.
      ref = ref.replace(handle, defaultChannel);
    }
  }
  return _.get(data, ref);
});
