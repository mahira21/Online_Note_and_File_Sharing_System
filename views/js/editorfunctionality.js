function setupFuseHeight() {
    let h = $('#bar').height();
    // console.log(h);
    document.documentElement.style.setProperty('--edsetH', `${h}px`);
}
$(window).ready(function () {
    setupFuseHeight();
    $('#editSettings').slideDown(700, function () {
        setupFuseHeight();
    });
    $('#editSettings').slideUp(700, function () {
        setupFuseHeight();
    });
    $('#optionToggler').slideDown(700, function () {
        setupFuseHeight();
    });
    $('#optionToggler').slideUp(700, function () {
        setupFuseHeight();
    });
});
$(window).resize(function () {
    setupFuseHeight();
});

$('#noteForm').submit(function (e) {
    e.preventDefault();
});

$('#expand').on('click', function () {
    if ($('#editSettings').is(':visible')) {
        $('#editSettings').slideUp(function () {
            setupFuseHeight();
        });
    } else {
        $('#editSettings').slideDown(function () {
            setupFuseHeight();
        });
    }
});

let previousXpire;

$('#expire')
    .on('focus', function () {
        // Store the current value on focus and on change
        previousXpire = this.value;
    })
    .change(function () {
        if (loggedUser === '') {
            $(`#expire option[value='${previousXpire}']`).prop(
                'selected',
                'selected'
            );
            throwlert(0, 'Login or create an account for advanced features');
            return;
        } else if (oldpvc == 2) {
            if (loggedUser != noteOwner) {
                $(`#expire option[value='${previousXpire}']`).prop(
                    'selected',
                    'selected'
                );
                throwlert(
                    0,
                    'You have no authority to modify a view only note'
                );
                return;
            }
        }
        let expire = $('#expire', '#noteForm').val();

        $.ajax({
            url: 'controllers/editorhandler.php',
            method: 'POST',
            dataType: 'JSON',
            data: {
                updateNoteExpiration: expire,
                noteID: noteid,
            },
            success: function (data) {
                if ('loginfirst' in data) {
                    $(`#expire option[value='${previousXpire}']`).prop(
                        'selected',
                        'selected'
                    );
                    throwlert(
                        0,
                        'Login or create an account for advanced features'
                    );
                } else if ('success' in data) {
                    noteOwner = loggedUser;
                    $('#author').val(noteOwner);
                } else {
                    throwlert(0, 'Something went wrong!');

                    $(`#expire option[value='${previousXpire}']`).prop(
                        'selected',
                        'selected'
                    );
                }
            },
        });
    });

$('#pad').bind('change keyup input', function () {
    let expire = $('#expire', '#noteForm').val();
    let padtext = $('#pad').val();
    $.ajax({
        url: 'controllers/editorhandler.php',
        method: 'POST',
        dataType: 'JSON',
        data: {
            updateNoteText: padtext,
            noteID: noteid,
            xpire: expire,
        },
        success: function (data) {
            if ('success' in data) {
                // console.log('note updated');
                noteOwner = loggedUser == '' ? 'guest' : loggedUser;
                $('#author').val(noteOwner);
            } else {
                // console.log('something went wrong');
            }
        },
    });
});

$('.privacy-radio-holder input').change(function () {
    if (loggedUser === '') {
        $(`.privacy-radio-holder input:radio[value='${oldpvc}']`).prop(
            'checked',
            true
        );
        throwlert(0, 'Login or create an account for advanced features');
        return;
    } else if (oldpvc == 2) {
        if (loggedUser != noteOwner) {
            $(`.privacy-radio-holder input:radio[value='${oldpvc}']`).prop(
                'checked',
                true
            );
            throwlert(0, 'You have no authority to modify a view only note');
            return;
        }
    }
    let privacy;
    let that = this;

    let expire = $('#expire', '#noteForm').val();

    if (this.value == '0') {
        privacy = 0;
    } else if (this.value == '2') {
        privacy = 2;
    } else {
        privacy = 1;
    }
    $.ajax({
        url: 'controllers/editorhandler.php',
        method: 'POST',
        dataType: 'JSON',
        data: {
            updateNotePrivacy: privacy,
            noteID: noteid,
            xpire: expire,
        },
        success: function (data) {
            if ('loginfirst' in data) {
                $(`.privacy-radio-holder input:radio[value='${oldpvc}']`).prop(
                    'checked',
                    true
                );
                throwlert(
                    0,
                    'Login or create an account for advanced features'
                );
            } else if ('success' in data) {
                oldpvc = privacy;
                noteOwner = loggedUser;
                $('#author').val(noteOwner);
            } else if ('limitError' in data) {
                throwlert(
                    0,
                    'You have exceeded maximum limit of private notes. Upgrade your plan or delete some old notes'
                );

                $(`.privacy-radio-holder input:radio[value='${oldpvc}']`).prop(
                    'checked',
                    true
                );
            } else if ('permitError' in data) {
                throwlert(0, 'You are not allowed to create private notes!');

                $(`.privacy-radio-holder input:radio[value='${oldpvc}']`).prop(
                    'checked',
                    true
                );
            } else {
                // console.log('Something Went wrong');
                $(`.privacy-radio-holder input:radio[value='${oldpvc}']`).prop(
                    'checked',
                    true
                );
            }
        },
    });
});

//text zoom
function setPercent(tsize) {
    let percent = (tsize / 16) * 100;
    percent = Math.round((percent + Number.EPSILON) * 100) / 100;
    $('#tzpercent').text(`${percent}%`);
}
$('#tzmin').click(function () {
    const csize = $('#pad').css('font-size');
    const csizenum = parseFloat(csize, 10);
    const tsize = csizenum - 2;
    $('#pad').css('font-size', tsize);
    setPercent(tsize);
});
$('#tzplus').click(function () {
    const csize = $('#pad').css('font-size');
    const csizenum = parseFloat(csize, 10);
    const tsize = csizenum + 2;
    $('#pad').css('font-size', tsize);
    setPercent(tsize);
});
