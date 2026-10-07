/* Billboard Pro — admin meta box tabs + gallery picker. */
(function ($) {
	'use strict';

	$(document).on('click', '.bbp-mb__tab', function () {
		var tab = $(this).data('tab');
		var box = $(this).closest('.bbp-mb');
		box.find('.bbp-mb__tab').removeClass('is-active');
		$(this).addClass('is-active');
		box.find('.bbp-mb__panel').removeClass('is-active');
		box.find('.bbp-mb__panel[data-panel="' + tab + '"]').addClass('is-active');
	});

	function sync(gallery) {
		var ids = gallery.find('li').map(function () { return $(this).data('id'); }).get();
		$('#' + gallery.data('target')).val(ids.join(','));
	}

	$(document).on('click', '.bbp-gallery__add', function (e) {
		e.preventDefault();
		var gallery = $(this).closest('.bbp-gallery');
		var input = $('#' + gallery.data('target'));
		var frame = wp.media({
			title: 'Select gallery images',
			button: { text: 'Use these images' },
			library: { type: 'image' },
			multiple: 'add'
		});
		frame.on('open', function () {
			var sel = frame.state().get('selection');
			(input.val() || '').split(',').filter(Boolean).forEach(function (id) {
				var att = wp.media.attachment(id);
				att.fetch();
				sel.add(att);
			});
		});
		frame.on('select', function () {
			var list = gallery.find('.bbp-gallery__list').empty();
			frame.state().get('selection').each(function (att) {
				var a = att.toJSON();
				var src = (a.sizes && a.sizes.thumbnail) ? a.sizes.thumbnail.url : a.url;
				list.append('<li data-id="' + a.id + '"><img src="' + src + '" alt=""><button type="button" class="bbp-gallery__remove" aria-label="Remove image">×</button></li>');
			});
			sync(gallery);
		});
		frame.open();
	});

	$(document).on('click', '.bbp-gallery__remove', function () {
		var gallery = $(this).closest('.bbp-gallery');
		$(this).closest('li').remove();
		sync(gallery);
	});
})(jQuery);
