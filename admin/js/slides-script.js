slideshow_jquery_image_gallery_script = function()
{
    var $    = jQuery,
        self = { };

    /**
     * Init
     */
    self.init = function()
    {
        self.$templateMetaBox      = $('#template');
        self.$templateRadioButtons = self.$templateMetaBox.find('input[name="_template"]');

        self.$textTemplate  = self.$templateMetaBox.find('.template-text');
        self.$videoTemplate = self.$templateMetaBox.find('.template-video');

        // Opens the options of the selected template
        self.onTemplateRadioButtonsChange();

        self.$templateRadioButtons.on('change', function(){ self.onTemplateRadioButtonsChange(); });
    };

    /**
     * @param event
     */
    self.onTemplateRadioButtonsChange = function()
    {
        self.$templateMetaBox.find('.template-options').hide();

        self.$templateMetaBox.find('.template-' + self.$templateRadioButtons.filter(':checked').val()).show();
    };

    $(document).ready(function()
    {
        self.init();
    });

    return self;
}();