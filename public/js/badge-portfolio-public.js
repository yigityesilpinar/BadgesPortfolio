jQuery(document).ready(function ($) {
    'use strict';
    var http = location.protocol;
    var slashes = http.concat("//");
    var host = slashes.concat(window.location.hostname);
    var plugin_url = host.concat('/wp-content/plugins/badge_portfolio/');
    $('#langsAutocompSkills').autocomplete({
        source: plugin_url + 'includes/partials/badge_portfolio-langs.php',
        minLength: 2,
        select: function (event, ui) {
            var code = ui.item.id;
            var lang = $("#skills_read_lang_select").val();
            var skill = 0; // 0  for Writing
            $.ajax({
                type: 'POST',
                url: plugin_url + 'includes/partials/badge_portfolio-all.php',
                data: {
                    'lang': lang,
                    'learn_lang': code,
                    'skill': skill
                },
                dataType: 'json',
                success: function (data1) {
                    var skills_div = '';
                    var data = data1[0];
                    var skill = data1[1];
                    var ans;
                    var level;
                    var learn_lang = $('#langsAutocompSkills').val();
                    var skills = ["Writing", "Interaction", "Reading", "Listening", "Speaking"];
                    if (level) {
                        var newTxt = learn_lang.split('(');
                        for (var i = 1; i < newTxt.length; i++) {

                            if (newTxt[i].split(')')[0].length == 3) {
                                learn_lang = newTxt[i].split(')')[0];
                            }
                        }
                        for (i = 0; i < skills.length; i++) {
                            if (i != skill) {
                                skills_div += '<a href="' + i + '">' + skills[i] + "</a>";
                            }
                            else {
                                skills_div += '<a href="' + i + '" class="selected">' + skills[i] + "</a>";
                            }
                        }
                        var result = '';
                        result += '<div id="skills_delete_record_div"><a href="' + learn_lang + '">Delete this Record</a></div>';
                        result += '<div id="skills_selected_lang_div"> Your current level: ' + level + '</div>';
                    }
                    else {
                        var result = '';
                    }
                    for (i = 0; i < skills.length; i++) {
                        if (i != skill) {
                            skills_div += '<a href="' + i + '">' + skills[i] + "</a>";
                        }
                        else {
                            skills_div += '<a href="' + i + '" class="selected">' + skills[i] + "</a>";
                        }
                    }
                    var checked = '';
                    var count = 0;
                    result += '<table style="width:100%;">';
                    result += ' <tr><th width="70%">Questions</th><th width="30%">YES MAYBE NO</th></tr>';
                    for (var key in data) {
                        if (data.hasOwnProperty(key)) {

                            var level = data[key].level;
                            var questions = data[key].questions;

                            for (var key2 in questions) {
                                if (questions.hasOwnProperty(key2)) {

                                    var question = questions[key2];
                                    result += '<tr><td class="' + level + '">';
                                    result += question;
                                    result += '</td><td>';
                                    checked = '';
                                    if (ans && ans[count] === 'y')
                                        checked = "checked='checked'";
                                    result += "<input type='radio' name='skills_radio_" + level + '_' + count + "' value='y'" + checked + "  />";
                                    checked = '';
                                    if (ans && ans[count] === 'm')
                                        checked = "checked='checked'";
                                    result += "<input type='radio' name='skills_radio_" + level + '_' + count + "' value='m'" + checked + "  />";
                                    checked = '';
                                    if (ans && ans[count] === 'n')
                                        checked = "checked='checked'";
                                    result += "<input type='radio' name='skills_radio_" + level + '_' + count + "' value='n'" + checked + "  />";

                                    result += '</td></tr>';
                                    count++;
                                }
                            }

                        }
                    }
                    result += '</table>' + '<p><input type="submit" name="skills_form_save" value="Save"/></p>';
                    $("#skills_div").html(skills_div);
                    $("#skills_questions_div").html(result);


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
    $('#skills_div').on('click', 'a', function (e) {

        e.preventDefault();

        var skill = $(this).attr('href');

        var learn_lang = $("#langsAutocompSkills").val();
        var lang = $("#skills_read_lang_select").val();
        var newTxt = learn_lang.split('(');
        for (var i = 1; i < newTxt.length; i++) {

            if (newTxt[i].split(')')[0].length == 3) {
                learn_lang = newTxt[i].split(')')[0];
            };
        }
        $.ajax({
            type: 'POST',
            url: plugin_url + 'includes/partials/badge_portfolio-all.php',
            data: {
                'lang': lang,
                'learn_lang': learn_lang,
                'skill': skill
            },
            dataType: 'json',
            success: function (data1) {
                var skills_div = '';
                var data = data1[0];
                var skill = data1[1];
                var ans;
                var level;
                var learn_lang = $('#langsAutocompSkills').val();
                var skills = ["Writing", "Interaction", "Reading", "Listening", "Speaking"];
                if (level) {
                    var newTxt = learn_lang.split('(');
                    for (var i = 1; i < newTxt.length; i++) {

                        if (newTxt[i].split(')')[0].length == 3) {
                            learn_lang = newTxt[i].split(')')[0];
                        }
                    }
                    for (i = 0; i < skills.length; i++) {
                        if (i != skill) {
                            skills_div += '<a href="' + i + '">' + skills[i] + "</a>";
                        }
                        else {
                            skills_div += '<a href="' + i + '" class="selected">' + skills[i] + "</a>";
                        }
                    }
                    var result = '';
                    result += '<div id="skills_delete_record_div"><a href="' + learn_lang + '">Delete this Record</a></div>';
                    result += '<div id="skills_selected_lang_div"> Your current level: ' + level + '</div>';
                }
                else {
                    var result = '';
                }
                for (i = 0; i < skills.length; i++) {
                    if (i != skill) {
                        skills_div += '<a href="' + i + '">' + skills[i] + "</a>";
                    }
                    else {
                        skills_div += '<a href="' + i + '" class="selected">' + skills[i] + "</a>";
                    }
                }
                var checked = '';
                var count = 0;
                result += '<table style="width:100%;">';
                result += ' <tr><th width="70%">Questions</th><th width="30%">YES MAYBE NO</th></tr>';
                for (var key in data) {
                    if (data.hasOwnProperty(key)) {

                        var level = data[key].level;
                        var questions = data[key].questions;

                        for (var key2 in questions) {
                            if (questions.hasOwnProperty(key2)) {

                                var question = questions[key2];
                                result += '<tr><td class="' + level + '">';
                                result += question;
                                result += '</td><td>';
                                checked = '';
                                if (ans && ans[count] === 'y')
                                    checked = "checked='checked'";
                                result += "<input type='radio' name='skills_radio_" + level + '_' + count + "' value='y'" + checked + "  />";
                                checked = '';
                                if (ans && ans[count] === 'm')
                                    checked = "checked='checked'";
                                result += "<input type='radio' name='skills_radio_" + level + '_' + count + "' value='m'" + checked + "  />";
                                checked = '';
                                if (ans && ans[count] === 'n')
                                    checked = "checked='checked'";
                                result += "<input type='radio' name='skills_radio_" + level + '_' + count + "' value='n'" + checked + "  />";

                                result += '</td></tr>';
                                count++;
                            }
                        }

                    }
                }
                result += '</table>' + '<p><input type="submit" name="skills_form_save" value="Save"/></p>';
                $("#skills_div").html(skills_div);
                $("#skills_questions_div").html(result);


            }
        });

    });


    $("#skills_read_lang_select").change(function (e) {
        e.preventDefault();
        var skill = $('#skills_div').find('a.selected').attr('href');
        var learn_lang = $("#langsAutocompSkills").val();
        var lang = $(this).val();
        var newTxt = learn_lang.split('(');
        for (var i = 1; i < newTxt.length; i++) {

            if (newTxt[i].split(')')[0].length == 3) {
                learn_lang = newTxt[i].split(')')[0];
            };
        }
        $.ajax({
            type: 'POST',
            url: plugin_url + 'includes/partials/badge_portfolio-all.php',
            data: {
                'lang': lang,
                'learn_lang': learn_lang,
                'skill': skill
            },
            dataType: 'json',
            success: function (data1) {
                var skills_div = '';
                var data = data1[0];
                var skill = data1[1];
                var ans;
                var level;
                var learn_lang = $('#langsAutocompSkills').val();
                var skills = ["Writing", "Interaction", "Reading", "Listening", "Speaking"];
                if (level) {
                    var newTxt = learn_lang.split('(');
                    for (var i = 1; i < newTxt.length; i++) {

                        if (newTxt[i].split(')')[0].length == 3) {
                            learn_lang = newTxt[i].split(')')[0];
                        }
                    }
                    for (i = 0; i < skills.length; i++) {
                        if (i != skill) {
                            skills_div += '<a href="' + i + '">' + skills[i] + "</a>";
                        }
                        else {
                            skills_div += '<a href="' + i + '" class="selected">' + skills[i] + "</a>";
                        }
                    }
                    var result = '';
                    result += '<div id="skills_delete_record_div"><a href="' + learn_lang + '">Delete this Record</a></div>';
                    result += '<div id="skills_selected_lang_div"> Your current level: ' + level + '</div>';
                }
                else {
                    var result = '';
                }
                for (i = 0; i < skills.length; i++) {
                    if (i != skill) {
                        skills_div += '<a href="' + i + '">' + skills[i] + "</a>";
                    }
                    else {
                        skills_div += '<a href="' + i + '" class="selected">' + skills[i] + "</a>";
                    }
                }
                var checked = '';
                var count = 0;
                result += '<table style="width:100%;">';
                result += ' <tr><th width="70%">Questions</th><th width="30%">YES MAYBE NO</th></tr>';
                for (var key in data) {
                    if (data.hasOwnProperty(key)) {

                        var level = data[key].level;
                        var questions = data[key].questions;

                        for (var key2 in questions) {
                            if (questions.hasOwnProperty(key2)) {

                                var question = questions[key2];
                                result += '<tr><td class="' + level + '">';
                                result += question;
                                result += '</td><td>';
                                checked = '';
                                if (ans && ans[count] === 'y')
                                    checked = "checked='checked'";
                                result += "<input type='radio' name='skills_radio_" + level + '_' + count + "' value='y'" + checked + "  />";
                                checked = '';
                                if (ans && ans[count] === 'm')
                                    checked = "checked='checked'";
                                result += "<input type='radio' name='skills_radio_" + level + '_' + count + "' value='m'" + checked + "  />";
                                checked = '';
                                if (ans && ans[count] === 'n')
                                    checked = "checked='checked'";
                                result += "<input type='radio' name='skills_radio_" + level + '_' + count + "' value='n'" + checked + "  />";

                                result += '</td></tr>';
                                count++;
                            }
                        }

                    }
                }
                result += '</table>' + '<p><input type="submit" name="skills_form_save" value="Save"/></p>';
                $("#skills_div").html(skills_div);
                $("#skills_questions_div").html(result);


            }
        });
    });
});
