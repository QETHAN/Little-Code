var editor = CodeMirror.fromTextArea(document.getElementById("editor"), {
	mode: 'text/html',
    lineNumbers: true,
    lineWrapping: true,
    autoCloseTags: true,
    autoCloseBrackets: true,
    styleActiveLine: true,
    matchBrackets: true,
    theme:'ambiance'
});
var action = {
	get: 'action.php?type=get&key=',
	post: 'action.php?type=save'
}
$(function(){
	var myURL = window.location.protocol+'//'+window.location.host+'/?';
	var reloadbody = $('#myDlg .modal-body p').html();
	$('[data-func="preview"]').click(function(){
		var win = window.open("", "", "");
		win.opener = null;
		win.document.write(editor.doc.getValue());
		win.document.close();
	});
	$('[data-func="share"]').click(function(){
		$('#myDlg').modal();
		
		$.post(action.post,{code:encodeURIComponent(editor.doc.getValue())},function(data) {
			$('#myDlg .modal-body p').html('当前代码地址为：<span class="share_link">'+myURL+data.id+'</span>');
			$('#copy-button').data('clipboard-text',myURL+data.id);
		});
	});
	$(document.body).on('hidden.bs.modal', function () {
		$(this).find('.modal-body p').html(reloadbody);
	});

	//clipboard
	var clip = new ZeroClipboard( document.getElementById('copy-button'), {
		moviePath: 'http://cdn.staticfile.org/zeroclipboard/1.1.7/ZeroClipboard.swf',
		trustedDomains: ['*'],
		trustedOrigins: [window.location.protocol + "//" + window.location.host],
		allowScriptAccess: "always"
	});
	clip.on('dataRequested', function(client, args) {
	  client.setText($('#copy-button').data('clipboard-text'));
	});
	clip.on('complete', function(client,args) {
		$('#myDlg .modal-body p').html('代码分享链接已复制到剪切板Y(^_^)Y');
	});
	clip.on('noflash', function (client,args) {
	  alert('You don\'t support flash!');
	});
	clip.on('wrongflash', function (client,args) {
	  alert('Your flash version'+args.flashVersion+' is too old!');
	});	
});
$(window).load(function(){
	//load sharing code
	var loca=window.location.href;
	var pattern=new RegExp(/[0-9]{10}$/);
	var requestkey=pattern.exec(loca);
	if(requestkey){
		$.get(action.get+requestkey,function(data){
			editor.doc.setValue(decodeURIComponent(data.code));
		});
	}
});