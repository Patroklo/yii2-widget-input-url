function defineUriMask(alias, maxLength, separator, regex) {

    var obj = {};

    obj[alias] = {
        mask: 'j{1,' + maxLength + '}',
        placeholder: "",
        separator: "",
        insertMode: false,
        autoUnmask: false,
        definitions: {
            'j': {
                'validator': function (chrs, maskset, pos, strict, opts) {
                    if (chrs == ' ') {
                        return {
                            c: separator
                        };
                    }
                    var regObject = new RegExp(regex);

                    return regObject.test(chrs);
                },
                'cardinality': 1
            }
        }
    };

    $.extend($.inputmask.defaults.aliases,
        obj
    );
}

(function ($) {

    $.fn.uriParserButton = function (uriField, sourceField) {

        readSourceField();

        function readSourceField() {
            $(sourceField).on('keyup', function () {
                $(uriField).val($(sourceField).val());
            });
        }

        $(this).on('click', function () {

            $(this).toggleClass('btn-primary');

            if ($(this).hasClass('btn-primary') == false) {
                readSourceField();

                $(uriField).prop('readonly', true);
            }
            else {
                $(sourceField).off('keyup');
                $(uriField).prop('readonly', false);
            }
        });
    };


})
(window.jQuery);