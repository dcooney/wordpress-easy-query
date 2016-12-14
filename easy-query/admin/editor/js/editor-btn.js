(function () {
  tinymce.create('tinymce.plugins.easyquery', {
    init: function (editor, url) {
      // Register commands
      var w = document.body.clientWidth / 1.3,
          h = document.body.clientHeight / 1.3;
      if(w > 900) w = 900;
      if(h > 600) h = 600;
      editor.addCommand('easy_query_mcebutton', function () {
        editor.windowManager.open({
          title: "Easy Query: Query Builder",
          file: ajaxurl + '?action=ewpq', // file that contains HTML for our modal window
          width: w, // size of our window
          height: h , // size of our window
          inline: 1
        }, 
        {
          plugin_url: url
        });
      });
      // Register Shortcode Button
      editor.addButton('ewpq_shortcode_button', {
        title: 'Insert Easy Query',
        cmd: 'easy_query_mcebutton',
        classes: 'widget btn easy-query-btn',
        image: url + '/../../img/add.png'
      });

    }
  });

  // Register plugin
  tinymce.PluginManager.add('ewpq_shortcode_button', tinymce.plugins.easyquery);

})();