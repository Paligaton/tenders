tinymce.init({
	selector: '.tinymce',
	plugins: 'advlist autolink lists link image charmap print preview paste',
	toolbar_mode: 'floating',
	toolbar: 'codesample | bold italic sizeselect fontselect fontsizeselect | hr alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | insertfile undo redo | forecolor backcolor emoticons | code',
	language: 'ru',
	paste_auto_cleanup_on_paste : true,
	paste_remove_styles: true,
	paste_remove_styles_if_webkit: true,
	paste_strip_class_attributes: true				
});	