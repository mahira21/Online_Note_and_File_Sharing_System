let notecount = (pnotecount = filecount = pfilecount = urlcount = 0);
let usedSpace = 0;
let planInUDC;
let notelimit = (filelimit = urllimit = 0);
let usersfullname;
function setLimit() {
    let planInText;
    switch (planInUDC) {
        case 0:
            notelimit = 50;
            filelimit = 10240;
            urllimit = 50;
            planInText = 'basic';
            break;
        case 1:
            notelimit = 200;
            filelimit = 20480;
            urllimit = 200;
            planInText = 'pro';

            break;
        case 2:
            notelimit = 5000;
            filelimit = 102400;
            urllimit = 5000;
            planInText = 'ultra';

            break;

        default:
            break;
    }
    if ($('.plan').text() != planInText) {
        $('.plan').text(planInText);
    }
}
function setLimitInSpans() {
    // console.log(typeof planInUDC);
    $('.note-limit').text(notelimit);
    $('.file-limit').text(filelimit / 1024 + 'MB');
    $('.url-limit').text(urllimit);
    // console.log('pin');
}

function updateStats() {
    // console.log('ooo');
    $.ajax({
        url: 'controllers/userdashcardhandler.php',
        method: 'POST',
        dataType: 'JSON',
        data: {
            ustats: 'ustats',
        },
        success: function (data) {
            // console.log(data);
            if (planInUDC != data.currentplan) {
                planInUDC = data.currentplan;
                setLimit();
                setLimitInSpans();
            }
            let w;
            if (usersfullname != data.nameofuser) {
                $('.user-dash-card .name-plate').text(data.nameofuser);
                usersfullname = data.nameofuser;
            }

            if (notecount != data.notecount) {
                $('.user-dash-card #notecount').text(data.notecount);
                w = (100 / notelimit) * data.pnotecount;
                if (w > 20) {
                    $('.user-dash-card #noteprog').text(`${w}%`);
                }
                $('.user-dash-card #noteprog').css('width', `${w}%`);
                notecount = data.notecount;
            }
            if (pnotecount != data.pnotecount) {
                $('.user-dash-card #pnotecount').text(data.pnotecount);
                pnotecount = data.pnotecount;
            }

            if (filecount != data.filecount) {
                $('.user-dash-card #filecount').text(data.filecount);
                w = (100 / filelimit) * data.filecount;
                if (w > 15) {
                    $('.user-dash-card #fileprog').text(`${w}%`);
                }
                $('.user-dash-card #fileprog').css('width', `${w}%`);
                filecount = data.filecount;
            }
            if (pfilecount != data.pfilecount) {
                $('.user-dash-card #pfilecount').text(data.pfilecount);
                pfilecount = data.pfilecount;
            }
            if (usedSpace != data.totalfilesize) {
                usedSpace = data.totalfilesize;
                let usedSpaceMB = usedSpace / 1024;
                usedSpaceMB =
                    Math.round((usedSpaceMB + Number.EPSILON) * 100) / 100;
                $('.user-dash-card #usedspace').text(usedSpaceMB);

                w = (100 / filelimit) * usedSpace;
                w = Math.round((w + Number.EPSILON) * 100) / 100;
                if (w > 15) {
                    $('.user-dash-card #fileprog').text(`${w}%`);
                } else {
                    $('.user-dash-card #fileprog').text(``);
                }
                $('.user-dash-card #fileprog').css('width', `${w}%`);
            }

            if (urlcount != data.urlcount) {
                $('.user-dash-card #urlcount').text(data.urlcount);

                w = (100 / urllimit) * data.urlcount;
                if (w > 20) {
                    $('.user-dash-card #urlprog').text(`${w}%`);
                }
                $('.user-dash-card #urlprog').css('width', `${w}%`);
                urlcount = data.urlcount;
            }
        },
    });
}

updateStats();

setInterval(function () {
    if ($('.user-dash-card').is(':visible')) {
        updateStats();
    }
}, 2000);
