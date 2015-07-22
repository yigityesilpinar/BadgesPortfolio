jQuery(document).ready(function ($) {

    $('#prev_langs').find('a').click(function (e) {
        $a = $(this);
        e.preventDefault();
        var code = $a.attr('href');
        var lang = $("#read_lang_select").val();
        $('#langsAutocomp').val($a.html() + ' (' + code.toUpperCase() + ')');
        $.ajax({
            type: 'POST',
            url: 'http://localhost/wordpress/wp-content/plugins/ygt_form/ygt_ajax.php',
            data: {
                'lang': lang,
                'learn_lang': code
            },
            dataType: 'json',
            success: function (data1) {
                var data = data1[0];
                var ans = data1[1];
                var level = data1[2];    
                var learn_lang = $('#langsAutocomp').val();
                if (level) {
                    var newTxt = learn_lang.split('(');
                    for (var i = 1; i < newTxt.length; i++) {

                        if (newTxt[i].split(')')[0].length == 3) {
                            learn_lang = newTxt[i].split(')')[0];
                        }
                    }
                    var result = '<div id="delete_record_div"><a id="delete_record_a" href="' + learn_lang + '">Delete this Record</a></div>';
                    result += '<div id="selected_lang_div"> Your current level: ' + level + '</div>';
                }
                else {
                    var result = '';
                }
                var checked = '';
                var count = 0;
                result += '<table style="width:100%;">';
                result += ' <tr><th width="70%">Questions</th><th width="30%">YES MAYBE NO</th></tr>';
                for (var key in data) {
                    if (data.hasOwnProperty(key)) {
                        var obj = data[key];

                        result += '<tr><td>';
                        result += obj;
                        result += '</td><td>';
                        checked = '';
                        if (ans && ans[count] === 'y')
                            checked = "checked='checked'";
                        result += "<input type='radio' name='ygt_radio" + count + "' value='y'" + checked + "  />";
                        checked = '';
                        if (ans && ans[count] === 'm')
                            checked = "checked='checked'";
                        result += "<input type='radio' name='ygt_radio" + count + "' value='m'" + checked + "  />";
                        checked = '';
                        if (ans && ans[count] === 'n')
                            checked = "checked='checked'";
                        result += "<input type='radio' name='ygt_radio" + count + "' value='n'" + checked + "  />";

                        result += '</td></tr>';
                        count++;


                    }
                }
                result += '</table>' + '<p><input type="submit" name="ygt_form_save" value="Save"/></p>';
                $("#questions_div").html(result);


            }
        });
      
    });
      
    $('#langsAutocomp').autocomplete({
        source: 'http://localhost/wordpress/wp-content/plugins/ygt_form/ygt_lang_search.php',
        minLength: 2,
        select: function (event, ui) {
            var code = ui.item.id;
            var lang = $("#read_lang_select").val();
            $.ajax({
                type: 'POST',
                url: 'http://localhost/wordpress/wp-content/plugins/ygt_form/ygt_ajax.php',
                data: {
                    'lang': lang,
                    'learn_lang': code
                },
                dataType: 'json',
                success: function (data1) {
                    var data = data1[0];
                    var ans = data1[1];
                    var level = data1[2];
                    var learn_lang = $('#langsAutocomp').val();
                    if (level) {
                        var newTxt = learn_lang.split('(');
                        for (var i = 1; i < newTxt.length; i++) {

                            if (newTxt[i].split(')')[0].length == 3) {
                                learn_lang = newTxt[i].split(')')[0];
                            }
                        }
                        var result = '<div id="delete_record_div"><a href="' + learn_lang + '">Delete this Record</a></div>';
                        result += '<div id="selected_lang_div"> Your current level: ' + level + '</div>';
                    }
                    else {
                        var result = '';
                    }
                    var checked = '';
                    var count = 0;
                    result += '<table style="width:100%;">';
                    result += ' <tr><th width="70%">Questions</th><th width="30%">YES MAYBE NO</th></tr>';
                    for (var key in data) {
                        if (data.hasOwnProperty(key)) {
                            var obj = data[key];

                            result += '<tr><td>';
                            result += obj;
                            result += '</td><td>';
                            checked = '';
                            if (ans && ans[count] === 'y')
                                checked = "checked='checked'";
                            result += "<input type='radio' name='ygt_radio" + count + "' value='y'" + checked + "  />";
                            checked = '';
                            if (ans && ans[count] === 'm')
                                checked = "checked='checked'";
                            result += "<input type='radio' name='ygt_radio" + count + "' value='m'" + checked + "  />";
                            checked = '';
                            if (ans && ans[count] === 'n')
                                checked = "checked='checked'";
                            result += "<input type='radio' name='ygt_radio" + count + "' value='n'" + checked + "  />";

                            result += '</td></tr>';
                            count++;


                        }
                    }
                    result += '</table>'+ '<p><input type="submit" name="ygt_form_save" value="Save"/></p>';
                    $("#questions_div").html(result);


                }
            });
        },
        // optional
        html: true,
        // optional (if other layers overlap the autocomplete list)
        open: function (event, ui) {
            $(".ui-autocomplete").css("z-index", 1000);
        }
    });
    $("#ygt_form_form").submit(function (e) {
        $(document).ajaxStart(function () {
            $("#ygtloading").show();
            $("#ygtloadingp").show();
            
        }).ajaxStop(function () {
            $("#ygtloading").hide();
            $("#ygtloadingp").hide();
        });
        e.preventDefault();
        $form = $(this);
        var lang = $('#langsAutocomp').val();
        var newTxt = lang.split('(');
        for (var i = 1; i < newTxt.length; i++) {

            if (newTxt[i].split(')')[0].length == 3) {
                lang=newTxt[i].split(')')[0];
            };
        }
        var radio_groups = {}
        $form.find('input:radio').each(function () {
            radio_groups[this.name] = true;
        })
        var answers = '';
        var i = 0;
        var number=_.size(radio_groups);
        for (group in radio_groups) {
            if_checked = !!$(":radio[name='" + group + "']:checked").length
            if (if_checked) {
                answers += $(":radio[name='" + group + "']:checked").val();
                if(i!=number-1)
                answers += ',';
            }
            else {
                if(i!=number-1)
                answers += ',';
            }
            i++;
            
        }


        $.ajax({
            type: 'POST',
            url: 'http://localhost/wordpress/wp-content/plugins/ygt_form/ygt_form_save.php?format=json&jsoncallback=?',
            data: {
                'lang': lang,
                'answers': answers
            },
            jsonpCallback:function (data1) {

             

            },
            dataType: 'jsonp',
            success: function () { console.log("success"); },
            error: function (e) { console.log(e); }
        });


       
    });
    $("#read_lang_select").change(function () {
        $item = $(this);
        var lang = $item.val();
        var learn_lang = $('#langsAutocomp').val();
        var newTxt = learn_lang.split('(');
        for (var i = 1; i < newTxt.length; i++) {

            if (newTxt[i].split(')')[0].length == 3) {
                learn_lang = newTxt[i].split(')')[0];
            }
        }
        $.ajax({
            type: 'POST',
            url: 'http://localhost/wordpress/wp-content/plugins/ygt_form/ygt_ajax.php',
            data: {
                'lang': lang,
                'learn_lang': learn_lang
            },
            dataType: 'json',
            success: function (data1) {
                var data = data1[0];
                var ans = data1[1];
                var level = data1[2];
                var learn_lang = $('#langsAutocomp').val();
                if (level) {
                    var newTxt = learn_lang.split('(');
                    for (var i = 1; i < newTxt.length; i++) {

                        if (newTxt[i].split(')')[0].length == 3) {
                            learn_lang = newTxt[i].split(')')[0];
                        }
                    }
                    var result = '<div id="delete_record_div"><a href="' + learn_lang + '">Delete this Record</a></div>';
                    result += '<div id="selected_lang_div"> Your current level: ' + level + '</div>';
                }
                else {
                    var result = '';
                }
                var checked = '';
                var count = 0;
                result += '<table style="width:100%;">';
                result += ' <tr><th width="70%">Questions</th><th width="30%">YES MAYBE NO</th></tr>';
                for (var key in data) {
                    if (data.hasOwnProperty(key)) {
                        var obj = data[key];

                        result += '<tr><td>';
                        result += obj;
                        result += '</td><td>';
                        checked = '';
                        if (ans && ans[count] === 'y')
                            checked = "checked='checked'";
                        result += "<input type='radio' name='ygt_radio" + count + "' value='y'" + checked + "  />";
                        checked = '';
                        if (ans && ans[count] === 'm')
                            checked = "checked='checked'";
                        result += "<input type='radio' name='ygt_radio" + count + "' value='m'" + checked + "  />";
                        checked = '';
                        if (ans && ans[count] === 'n')
                            checked = "checked='checked'";
                        result += "<input type='radio' name='ygt_radio" + count + "' value='n'" + checked + "  />";

                        result += '</td></tr>';
                        count++;


                    }
                }
                result += '</table>' + '<p><input type="submit" name="ygt_form_save" value="Save"/></p>';
                $("#questions_div").html(result);


            }
        });
    });

    $('body').on('click', '#delete_record_a', function (e) {
        $a = $(this);
        e.preventDefault();
        var lang = $a.attr('href');
        $.ajax({
            type: 'POST',
            url: 'http://localhost/wordpress/wp-content/plugins/ygt_form/ygt_form_delete.php',
            data: {
                'lang': lang
            },
            dataType: 'json',
            success: function (data1) {
                location.reload();
            }
        });
    });
});

