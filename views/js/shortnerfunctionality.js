let urlBox = $('#urlBox');
let emptyMsg = 'Please enter your url first';

let resultUrlBox = document.getElementById('resultUrlBox');
function autoBlur() {
    resultUrlBox.blur();
}

function validURL(str) {
    var pattern = new RegExp(
        '^(https?:\\/\\/)?' + // protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
            '(\\#[-a-z\\d_]*)?$',
        'i'
    ); // fragment locator
    return !!pattern.test(str);
}

function errorStyle(msg) {
    urlBox.val('');
    urlBox.attr('placeholder', msg);
    urlBox.addClass('error-class');
    // console.log(urlBox.hasClass('error-class'));
}

urlBox.bind('change keyup input', function () {
    if (urlBox.hasClass('error-class')) {
        urlBox.removeClass('error-class');
        urlBox.attr('placeholder', 'http://www.example.com');
    }
    $('.result-row').fadeOut();
});

$('#shortenButton').click(function () {
    let longUrl = urlBox.val().trim();
    if (longUrl == '') {
        errorStyle('Enter your URL here first');
    } else if (!validURL(longUrl)) {
        errorStyle('Invalid URL found');
    } else {
        $('.semiloader').fadeIn();
        $.ajax({
            url: 'controllers/shorthandler.php',
            method: 'POST',
            dataType: 'JSON',
            data: {
                longUrl: longUrl,
            },
            success: function (data) {
                $('.semiloader').fadeOut(function () {
                    if ('surl' in data) {
                        if (data.surl != '') {
                            $('.result-row').fadeIn();
                            $('#resultUrlBox').val(data.surl);
                        }
                    } else if ('limitError' in data) {
                        throwlert(
                            0,
                            'You have exceeded the limit of short urls as per your current plan. Upgrade your plan or delete 1 of your shortened urls'
                        );
                    }
                });
            },
        });
    }
});

// let copyText = () => {
//     document.getElementById('resultUrlBox').select();
//     let z = document.execCommand('copy');
//     // console.log(z);
// };

$('#copyButton').click(function () {
    let resUrl = $('#resultUrlBox').get(0);
    resUrl.select();
    resUrl.setSelectionRange(0, 99999);
    let z = document.execCommand('copy');
    autoBlur(); //to deselct the selection
    if (z == true) {
        throwlert(1, 'URL copied to your clipboard');
    }
});
