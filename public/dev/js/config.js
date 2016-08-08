$(function () {
    'use strict';

    function verElem($elem) {
        var verifica = $.trim($elem.val());
        if (!verifica) {
            if (!$elem.closest('.form-group').hasClass('has-error')) {
                $elem.closest('.form-group').addClass('has-error');
                return false;
            }
        } else {
            if ($elem.closest('.form-group').hasClass('has-error')) {
                $elem.closest('.form-group').removeClass('has-error');
            }
            $elem.closest('.form-group').addClass('has-success');
            return true;
        }
    }
    function verElemLength($elem) {
        var verifica = $.trim($elem.val());
        if (verifica.length < 4) {
            if (!$elem.closest('.form-group').hasClass('has-error')) {
                $elem.closest('.form-group').addClass('has-error');
                return false;
            }
        } else {
            if ($elem.closest('.form-group').hasClass('has-error')) {
                $elem.closest('.form-group').removeClass('has-error');
            }
            $elem.closest('.form-group').addClass('has-success');
            return true;
        }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $filePreview.attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function webcamPreview() {
        Webcam.freeze();
        $webcamPreview.hide();
        $webcamCancelPreview.show();
        $webcamSave.show();
    }
    function webcamCancelPreview() {
        Webcam.unfreeze();
        $webcamPreview.show();
        $webcamCancelPreview.hide();
        $webcamSave.hide();
    }
    function webcamSave() {
        Webcam.snap( function(data_uri) {
            Webcam.on( 'uploadComplete', function(code, text) {
                if (code === 200) {
                    $('#profileAvatar').attr("src", href_public + '/imgs/avatar/'+text);
                    $('.user-avatar').attr("src", href_public + '/imgs/avatar/'+text);
                } else {
                    $avatarErro.show();
                }
                $webcamPreview.show();
                $webcamCancelPreview.hide();
                $webcamSave.hide();
                Webcam.reset();
                $webcamModal.modal('hide');
            } );
            var envia = href_auth + 'config/profile/avatar/';
            Webcam.upload( data_uri, envia );
        } );
    }

    var $infosjs = $('.infosjs');
    var href_auth = $infosjs.data('auth');
    var href_public = $infosjs.data('public');

    var $changeDetails = $('#changeDetails');
    var $avatarDiv = $('#avatarDiv');
    var $changePasswordForm = $('#changePasswordForm');

    if ($changeDetails.length > 0) {
        var $inputName = $('#inputName');
        $changeDetails.submit(function (e) {
            $(this).find('button').prop("disabled", true);
            if (!verElem($inputName)) {
                e.preventDefault();
                $(this).find('button').prop("disabled", false);
            }
        });
    }

    if ($avatarDiv.length > 0) {

        var $fileModal = $('#fileModal');
        var $fileForm = $('#fileForm');
        var $inputFile = $('#inputFile');
        var $filePreview = $('#filePreview');
        var $inputFileErr = $('#inputFileErr');
        var src = $('#profileAvatar').data('src');

        $inputFile.uniform({
            fileButtonClass: 'action btn btn-primary'
        });
        $inputFile.change(function () {
            var ext = $inputFile.val().split('.').pop().toLowerCase();
            if (($.inArray(ext, ['png', 'jpg', 'jpeg']) == -1) || (this.files[0].size > 2097152)) {
                $inputFileErr.removeClass('hidden').addClass('show');
            } else {
                $inputFileErr.removeClass('show').addClass('hidden');
                readURL(this);
            }
        });
        $fileModal.on('show.bs.modal', function () {
            $inputFile.val('');
            $.uniform.update();
            $filePreview.attr('src', src);
        });
            $fileForm.submit(function (e) {
            $(this).find('button').prop("disabled", true);
            if (!verElem($inputFile)) {
                e.preventDefault();
                $(this).find('button').prop("disabled", false);
            }
        });

        var $avatarErro = $('#avatarErro');
        var $webcamModal = $('#webcamModal');
        var $webcamPreview = $('#webcamPreview');
        var $webcamCancelPreview = $('#webcamCancelPreview');
        var $webcamSave = $('#webcamSave');

        $webcamModal.on('show.bs.modal', function () {
            $avatarErro.hide();
            $webcamCancelPreview.hide();
            $webcamSave.hide();
            $webcamPreview.show();
            Webcam.set({
                width: 320,
                height: 240,
                dest_width: 640,
                dest_height: 480,
                image_format: 'jpeg',
                jpeg_quality: 100
            });
            Webcam.attach( '#webcamShow' );
            $webcamPreview.click(function (e) {
                e.preventDefault();
                webcamPreview();
            });
            $webcamCancelPreview.click(function (e) {
                e.preventDefault();
                webcamCancelPreview();
            });
            $webcamSave.click(function (e) {
                e.preventDefault();
                webcamSave();
            });
        });
        $webcamModal.on('hide.bs.modal', function () {
            Webcam.reset();
        });

    }

    if ($changePasswordForm.length > 0) {
        $changePasswordForm.submit(function (e) {
            function validaFinal() {
                var a = verElemLength($('#password'));
                var b = verElemLength($('#newPassword'));
                var c = verElemLength($('#repeatPassword'));
                return a && b && c;
            }
            $(this).find('button').prop("disabled", true);
            if (!validaFinal()) {
                e.preventDefault();
                $(this).find('button').prop("disabled", false);
            }
        });
    }
});