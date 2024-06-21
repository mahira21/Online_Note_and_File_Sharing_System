let plan;
let noPendingRequest = true;
$('.input-sec input').on('focus', function () {
    $(this).addClass('focus');
});

$('.input-sec input').on('blur', function () {
    if ($(this).val() == '') {
        $(this).removeClass('focus');
    }
});

function addRemoveFocus() {
    $('.input-sec input').each(function () {
        if ($(this).val() != '') {
            $(this).addClass('focus');
        } else {
            $(this).removeClass('focus');
        }
    });
}
addRemoveFocus();

//to block pass change section
function disableChangePassSec() {
    $('.change-pass-sec :input').attr('disabled', true);
    $('.change-pass-sec :input').val('');
    addRemoveFocus();
}
disableChangePassSec();
$('#passchange').on('change', function () {
    if (!$('#passchange').is(':checked')) {
        disableChangePassSec();
    } else {
        $('.change-pass-sec :input').removeAttr('disabled');
    }
});

function reloadPersonal() {
    $.ajax({
        url: 'controllers/settingshandler.php',
        method: 'POST',
        dataType: 'JSON',
        data: {
            fetchPersonal: 'fetchInfo',
        },
        success: function (data) {
            if ('info' in data) {
                $('#namebox').val(data.info.name);
                $('#unamebox').val(data.info.uname);
                $('#emailbox').val(data.info.email);

                addRemoveFocus();
                plan = data.info.plan;
                selectPlan();
                $('.warn').each(function () {
                    $(this).text('');
                });
            }
        },
    });
}
function reloadPlan() {
    $.ajax({
        url: 'controllers/settingshandler.php',
        method: 'POST',
        dataType: 'JSON',
        data: {
            fetchPlan: 'fetchInfo',
        },
        success: function (data) {
            $('.loader').fadeOut();

            if ('cpinfo' in data) {
                if (data.cpinfo.actions == 0) {
                    $('.card button').attr('disabled', true);

                    $('.request-status').text(
                        'Your request has been placed for admin approval,you will be able to rquest again after ' +
                            data.cpinfo.unlockDate
                    );
                    if (data.cpinfo.dp == 0) {
                        $('.card button').eq(0).text('Requested');
                    } else if (data.cpinfo.dp == 1) {
                        $('.card button').eq(1).text('Requested');
                    } else if (data.cpinfo.dp == 2) {
                        $('.card button').eq(2).text('Requested');
                    }
                } else if (data.cpinfo.actions == 1) {
                    $('.card button').attr('disabled', true);
                    $('.request-status').text(
                        'Your request has been Accepted, you can request again when the rquest option is available'
                    );
                } else if (data.cpinfo.actions == 2) {
                    $('.card button').attr('disabled', true);
                    $('.request-status').text(
                        'Your request has been Declined, you can request again when the rquest option is available'
                    );
                } else {
                }
            } else {
                $('.request-status').text(
                    'You have bot requested for any plan change'
                );
            }
        },
    });
}

reloadPersonal();
reloadPlan();

async function selectPlan() {
    switch (plan) {
        case '0':
            $('.card button').eq(0).text('Selected');
            $('.card button').eq(0).attr('disabled', true);
            break;
        case '1':
            $('.card button').eq(1).text('Selected');
            $('.card button').eq(1).attr('disabled', true);

            break;
        case '2':
            $('.card button').eq(2).text('Selected');
            $('.card button').eq(2).attr('disabled', true);

            break;
        default:
            break;
    }
}
selectPlan();

//JS validation
function warn(that, msg) {
    $(`#${that}`).parent().next('.warn').text(msg);
}
let pinfarr;
let validate = () => {
    pinfarr = [];
    let valid = true;
    function warn(that, msg) {
        $(`#${that}`).parent().next('.warn').text(msg);
    }

    const letters = /^[A-Za-z ]+$/;
    const name = $('#namebox').val();
    if (name == '') {
        warn('namebox', 'please enter your name above');
        valid = false;
    } else if (!name.match(letters)) {
        warn('namebox', 'please enter letters and Space only (e.g. Abcd Efgh)');
        valid = false;
    } else {
        warn('namebox', '');
        pinfarr.push(name);
    }

    const mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    const email = $('#emailbox').val();
    if (email == '') {
        warn('emailbox', 'please enter your email above');
        valid = false;
    } else if (!email.match(mailformat)) {
        warn('emailbox', 'please enter valid email only');
        valid = false;
    } else {
        warn('emailbox', '');
        pinfarr.push(email);
    }

    const opass = $('#oldpassbox').val();
    if (opass == '') {
        warn('oldpassbox', 'please enter your password above');
        valid = false;
    } else {
        warn('oldpassbox', '');
        pinfarr.push(opass);
    }
    if ($('#passchange').is(':checked')) {
        pinfarr.push('passchange');
        const npass = $('#newpassbox').val();
        if (npass == '') {
            warn('newpassbox', 'please enter new your password above');
            valid = false;
        } else {
            warn('newpassbox', '');
            pinfarr.push(npass);
        }

        const cnpass = $('#cnewpassbox').val();
        if (cnpass == '') {
            warn('cnewpassbox', 'please enter your password above');
            valid = false;
        } else if (cnpass !== npass) {
            warn('cnewpassbox', "pleasword didn't match");
            valid = false;
        } else {
            warn('cnewpassbox', '');
            pinfarr.push(cnpass);
        }
    } else {
        pinfarr.push('nopasschange');
    }

    return valid;
};

$('.subBtn').click(function () {
    $('.warn').each(function () {
        $(this).text('');
    });
    if (validate()) {
        $('.loader').fadeIn();
        $.ajax({
            url: 'controllers/settingshandler.php',
            method: 'POST',
            dataType: 'JSON',
            data: {
                infoArray: pinfarr,
            },
            success: function (data) {
                $('.loader').fadeOut(function () {
                    if (Object.keys(data.errors).length > 0) {
                        //if validation error response from server side
                        if (0 in data.errors) {
                            warn('namebox', data.errors[0]);
                        }
                        if (1 in data.errors) {
                            warn('emailbox', data.errors[1]);
                        }
                        if (2 in data.errors) {
                            warn('oldpassbox', data.errors[2]);
                        }
                        if (3 in data.errors) {
                            warn('newpassbox', data.errors[3]);
                        }
                        if (4 in data.errors) {
                            warn('cnewpassbox', data.errors[4]);
                        }
                    }
                    if ('success' in data) {
                        if (data.success == 'true') {
                            reloadPersonal();
                            throwlert(1, 'Information Updated Successfully');
                        } else if (data.success == 'false') {
                            throwlert(1, 'Something went wrong');
                        }
                    }
                });
            },
        });
    }
});

$('.resBtn').click(function () {
    reloadPersonal();
    $('#oldpassbox').val('');
    $('#newpassbox').val('');
    $('#cnewpassbox').val('');
});

$('.card button').click(function () {
    const desiredPlan = $(this).parent().parent().find('div').eq(0).text();
    let that = $(this);

    if (confirm('Are you sure to file a request?')) {
        $('.loader').fadeIn();

        $.ajax({
            url: 'controllers/settingshandler.php',
            method: 'POST',
            dataType: 'JSON',
            data: {
                requestPlanChange: desiredPlan,
            },
            success: function (data) {
                that.text('Requested');
                $('.card button').attr('disabled', true);
                $('.loader').fadeOut(function () {
                    if ('success' in data) {
                        reloadPlan();
                    } else if ('hasExistingReq' in data) {
                        throwlert(0, data.hasExistingReq);

                        that.text('Select');
                        $('.card button').attr('disabled', false);
                        selectPlan();
                    } else {
                        throwlert(0, 'Something went wrong');

                        that.text('Select');
                        $('.card button').attr('disabled', false);
                        selectPlan();
                    }
                });
            },
        });
    }
});
