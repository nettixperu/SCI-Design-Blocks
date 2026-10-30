(function (wp, settings) {
 if (!wp || !wp.hooks || !settings || !settings.hideTitles) return;
 wp.hooks.addFilter('editor.PostTitle', 'sci-design-blocks/hide-page-title', function () { return null; });
})(window.wp, window.sciDesignBlocksTitleSettings);
