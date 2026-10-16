(function ($) {
  "use strict";

  $(document).on("click", ".gia-menu-icon-upload", function (event) {
    event.preventDefault();

    var field = $(this).closest(".gia-menu-icon-field");
    var input = field.find(".edit-menu-item-gia-icon");
    var preview = field.find(".gia-menu-icon-preview");
    var removeButton = field.find(".gia-menu-icon-remove");
    var frame = wp.media({
      title: "Choose a menu icon",
      button: {
        text: "Use this image",
      },
      library: {
        type: "image",
      },
      multiple: false,
    });

    frame.on("select", function () {
      var attachment = frame.state().get("selection").first().toJSON();
      var imageUrl = attachment.sizes && attachment.sizes.thumbnail
        ? attachment.sizes.thumbnail.url
        : attachment.url;
      var image = $("<img>", {
        src: imageUrl,
        alt: "",
        width: 36,
        height: 36,
      });

      input.val(attachment.id);
      preview.empty().append(image).prop("hidden", false);
      removeButton.prop("hidden", false);
    });

    frame.open();
  });

  $(document).on("click", ".gia-menu-icon-remove", function (event) {
    event.preventDefault();

    var field = $(this).closest(".gia-menu-icon-field");
    field.find(".edit-menu-item-gia-icon").val("");
    field.find(".gia-menu-icon-preview").empty().prop("hidden", true);
    $(this).prop("hidden", true);
  });
})(jQuery);
