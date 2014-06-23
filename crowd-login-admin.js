function selectRole(checkedRole, selectName) {
  function checked(role) {
    return (role === checkedRole) ? 'checked="checked"' : "" ;
  }
  var selectNameHtml = (null == selectName) ? "crowd_account_type" : selectName;
  var html = ' <select class="additional-input" name="' + selectNameHtml + '"> ' +
          ' <option ' + checked("Administrator") + ' value="Administrator">Administrator</option> ' +
          ' <option ' + checked("Editor") + ' value="Editor">Editor</option> ' +
          ' <option ' + checked("Author") + ' value="Author">Author</option> ' +
          ' <option ' + checked("Contributor") + ' value="Contributor">Contributor</option> ' +
          ' <option ' + checked("Subscriber") + ' value="Subscriber">Subscriber</option> ' +
        ' </select> ';
  return html;
}
function groupInput(group) {
  return '<div class="additional-input"><label for="cl-crowd-group">Crowd group:</label><input id="cl-crowd-group" name="crowd_group" type="text" value="' + group + '" size="12"/>';
}
function mapGroup() {
  var positions = ["Administrator", "Editor", "Author", "Contributor", "Subscriber"];
  function oneLineHtml(position) {
    var html = '<div><input type="text" name="cl-mapping-crowd-group-' + position + '" />' + position + "</div>";
    return html;
  }
  var html = "<div>" + _.chain(positions).map(oneLineHtml).reduce(function(acc,b) { return acc + b}, "").value() + "</div>";
  return html;
}

(function($){
  $(document).ready(function(){

    $(".cl-mode").click(function(){
      $(".additional-input").remove();
      var id = $(this).attr("id");
      if (id === "cl-mode-create-all") {
        var html = selectRole(crowdAccountType);
        $("#cl-mode-create-all").parent().append(html);
      } else if (id === "cl-mode-create-group") {
        var html = selectRole(crowdAccountType);
        var parent = $("#cl-mode-create-group").parent()
        parent.append(html);
        parent.append(groupInput(crowdGroup));
      } else if (id === "cl-mode-map-group") {
        $("#cl-mode-map-group").parent().append(mapGroup());
      }
    });

    $(".cl-mode[checked='checked']").click();

  });
})(jQuery);
