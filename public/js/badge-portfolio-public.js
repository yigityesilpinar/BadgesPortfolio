jQuery(document).ready(function ($) {
    'use strict';
    var numOfQuestions = [];
    var levels = [];
    var skills_data = [];
    var http = location.protocol;
    var slashes = http.concat("//");
    var host = slashes.concat(window.location.hostname);
    var plugin_url = host.concat('/wp-content/plugins/BadgePortfolio/');
    var skills = ["Writing", "Interaction", "Reading", "Listening", "Speaking"];
    var change = true;
    function displayForm(data1) {
        change = true;
        $('#previous_skills_div').html('');
        var skills_div = '';
        skills_data = data1[0];
        var skill = data1[1];
        var answers = data1[2];
        if(answers)
        var ans = answers.split(',');
        var level = data1[3];
        var learn_lang = $('#langsAutocompSkills').val();
        var result = '';

        if (level) {
            var newTxt = learn_lang.split('(');
            for (var i = 1; i < newTxt.length; i++) {

                if (newTxt[i].split(')')[0].length == 3) {
                    learn_lang = newTxt[i].split(')')[0];
                }
            }
            $('#previous_skills_div').append('<div id="skills_delete_record_div"><a id="delete_record_a" href="' + learn_lang + ',' + level + '">Delete this Record</a></div>');
            $('#previous_skills_div').append('<div id="skills_selected_lang_div"> Your current level: ' + level + '</div>');
        }
        var i;
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
        numOfQuestions = [];
        levels = [];
        var numCount = 0;
        for (var key in skills_data) {
            if (skills_data.hasOwnProperty(key)) {

                var level = skills_data[key].level;
                var questions = skills_data[key].questions;
                numCount = 0;
                var sth_checked = false;
                for (var key2 in questions) {
                    if (questions.hasOwnProperty(key2)) {

                        var question = questions[key2];
                        result += '<tr><td class="' + level + '">';
                        result += question;
                        result += '</td><td>';
                        sth_checked = false;
                        checked = '';
                        if (ans && ans[count] === 'y') {
                            sth_checked = true;
                            checked = "checked='checked'";
                        }
                        result += "<input type='radio' name='skills_radio[" + count + "]' value='y'" + checked + "  />";
                        checked = '';
                        if (ans && ans[count] === 'm') {
                            sth_checked = true;
                            checked = "checked='checked'";
                        }
                        result += "<input type='radio' name='skills_radio[" + count + "]' value='m'" + checked + "  />";
                        checked = '';
                        if ((ans && ans[count] === 'n') || !sth_checked)
                            checked = "checked='checked'";
                        result += "<input type='radio' name='skills_radio[" + count + "]' value='n'" + checked + "  />";

                        result += '</td></tr>';
                        count++;
                        numCount++;
                    }

                }
                numOfQuestions.push(numCount);
                levels.push(level);
            }
        }

        result += '</table>' + '<p id="portfolio_save_p"><input type="submit" name="skills_form_save" value="Save"/><span></span></p>';
        $("#skills_div").html(skills_div);
        $("#skills_questions_div").html(result);


    }

    $('#langsAutocompSkills').autocomplete({
        source: plugin_url + 'includes/partials/badge-portfolio-langs.php',
        minLength: 2,
        select: function (event, ui) {
            var code = ui.item.id;
            var lang = $("#skills_read_lang_select").val();
            var skill = 0; // 0  for Writing
            $.ajax({
                type: 'POST',
                url: plugin_url + 'includes/partials/badge-portfolio-all.php',
                data: {
                    'lang': lang,
                    'learn_lang': code,
                    'skill': skill
                },
                dataType: 'json',
                success: function (data) {
                    displayForm(data);
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
            url: plugin_url + 'includes/partials/badge-portfolio-all.php',
            data: {
                'lang': lang,
                'learn_lang': learn_lang,
                'skill': skill
            },
            dataType: 'json',
            success: function (data) {
                displayForm(data);
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
            url: plugin_url + 'includes/partials/badge-portfolio-all.php',
            data: {
                'lang': lang,
                'learn_lang': learn_lang,
                'skill': skill
            },
            dataType: 'json',
            success: function (data) {
                displayForm(data);
            }
        });
    });

    $('#badge_portfolio_form').on('submit', function (e) {
        e.preventDefault();
        if (!change) {
            console.log('Can not be saved, no change!');
            return;
        }
        change = false;
        $("#portfolio_save_p").find('span').attr('class', '');
        $("#portfolio_save_p").find('span').html('');
        var i = 0;
        var answers = [];  // empty array
        var question_count = 0;
        $("#badge_portfolio_form input[type=radio]").each(function () {
            question_count++;
        });
        question_count = question_count / 3;
        $("#badge_portfolio_form input[type=radio]:checked").each(function () {
            answers.push($(this).val());
        });
        if (answers.length != question_count) {
            var save_error = 'Please fill all the options before saving.';
            $("#portfolio_save_p").find('span').attr('class', 'save_error');
            $("#portfolio_save_p").find('span').html(save_error);
            return;
        }
        var skill = parseInt($('#skills_div').find('a.selected').attr('href'));
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
            url: plugin_url + 'includes/partials/badge-portfolio-save.php',
            data: {
                'lang': lang,
                'learn_lang': learn_lang,
                'skill': skill,
                'answers': answers,
                'num': numOfQuestions,
                'levels': levels
            },
            success: function (bata) {
                console.log(typeof bata);

                var is_valid = bata[0];
                var save_message = bata[1];
                console.log(save_message);
                return;
                if (is_valid) {
                    var skill = (parseInt(data) + 1) % 6; // next skill
                    $("#portfolio_save_p").find('span').attr('class', 'save_success');
                    $("#portfolio_save_p").find('span').html('Succesfully saved!.To fill the next skill click ');
                    $("#portfolio_save_p").append('<a id="show_next_skill_a" href="_">Next</a>');
                }
                else {
                    return;
                }


            }
        });
    });
    $('#skills_questions_div').on('click', 'a#show_next_skill_a', function (e) {
        e.preventDefault();
        var skill = (parseInt($('#skills_div').find('a.selected').attr('href')) + 1) % 5;
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
            url: plugin_url + 'includes/partials/badge-portfolio-all.php',
            data: {
                'lang': lang,
                'learn_lang': learn_lang,
                'skill': skill
            },
            dataType: 'json',
            success: function (data) {
                displayForm(data);
                window.scrollTo(0, 0);
            }
        });
    });

    $('#previous_skills_div').on('click', '#delete_record_a', function (e) {
        e.preventDefault();
        var temp = $('#delete_record_a').attr('href').split(',');
        var lang = temp[0];
        var level = temp[1];
        var skill = parseInt($('#skills_div').find('a.selected').attr('href'));
        $.ajax({
            type: 'POST',
            url: plugin_url + 'includes/partials/badge-portfolio-delete.php',
            data: {
                'lang': lang,
                'level': level,
                'skill': skill,
                'levels': levels
            },
            dataType: 'json',
            success: function (data1) {
                var temp = {};
                if (data1[0]) {
                    temp[0]=(skills_data);
                    temp[1]=(data1[1]);
                    temp[2] = (data1[2]);
                    temp[3] = (data1[3]);
                    displayForm(temp);
                    //window.scrollTo(0, 0);
                }
                else {
                    alert('mistake');
                }

            }
        });
    });

});