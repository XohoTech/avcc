function initialize_records_form() {
    $('#format_lbl').hide();
    $('#diskDiameters_lbl').hide();
    $('#reelDiameters_lbl').hide();
    $('#mediaDiameters_lbl').hide();
    $('#tapeThickness_lbl').hide();
    $('#trackTypes_lbl').hide();
    $('#cassetteSize_lbl').hide();
    $("#formatVersion_lbl").hide();
    $('#bases_lbl').hide();
    $('#recordingSpeed_lbl').hide();

    if (selectedMediaType)
        $('#mediaType option[value="' + selectedMediaType + '"]').attr("selected", "selected");
    $.mask.definitions['y'] = '[1-2]';
    $.mask.definitions['m'] = '[0-1]';
    $.mask.definitions['d'] = '[0-3]';
    $.mask.definitions['g'] = '[0-9]';
    $("#digitizedWhen").mask("mg/yggg", {optional: true});
//    $("#creationDate, #contentDate").mask("yggg-mg-dg", {optional: true});
    updateViewSetting();
    updateProjects();
    updateFormat();
    onChangeMediaType();

    showUpdateFields();
    if (bulk) {
//        showBulkDigitizedFields();
    } else {
        showDigitizedFields();
    }
    convertTimeToMinutes();
    saveBulkEdit();
    closeBtn();
    var check = 0;
    $("input,textarea,select").keypress(function () {
        if ($(this).attr('id') != 'mediaType') {
            check = 1;
        }
    });
    var changes = false;
    $("select").click(function () {
        if (check == 1 && $(this).attr('id') == 'mediaType') {
            changes = true;
        } else if ($(this).attr('id') == 'mediaType' || $(this).attr('id') == 'project') {
            changes = false;
        }
    });

    window.onbeforeunload = function () {
        if (changes)
        {
            return 'The changes are not saved and will be lost.';
        }
    }

    $('form').submit(function () {
        changes = false;
        return true; // return false to cancel form action
    });
    $('#reelCore').change(function () {
        var media = $('#mediaType').val();
        var reel = $('#reelCore').val();
        if (media == 2 && reel == 1) {
            $('#reelDiameters_lbl').show();
        } else if (media == 2 && reel != 1) {
            $('#reelDiameters_lbl').hide();
        }
    });
}


function updateFormat() {
    var selfObj = this;
    selfObj.ajaxCall = false;
    /// call to get base dropdown options
//    $('#processing').html('<img src="/images/ajax-loader.gif" /> <span><b>Processing please wait...</b></span>');
    if ($("#mediaType").val()) {
        $("#format_lbl").show();
        if (selectedFormat) {

            url = baseUrl + 'getFormat/' + $("#mediaType").val() + '/' + selectedFormat;
        } else {
            url = baseUrl + 'getFormat/' + $("#mediaType").val();
        }
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                if (response != "") {
                    $('#format_lbl').show();
                    $("#format").html(response);
                    $("#format").change();
                    $('#processing').hide();
                    $('#fieldsPanel').show();
                }
            }

        }); // Ajax Call 
    } else {
        $('#processing').hide();
        $('#fieldsPanel').show();
        $("#format_lbl").hide();
    }
}

function convertTimeToMinutes() {
    $("#contentDuration").on("blur", function () {
        var hms = $("#contentDuration").val();
        if (hms != "") {
            var a = hms.split(':');
            if (a.length > 1) {
                var seconds;
                var minutes;
                if (a.length == 2 && parseInt(a[1]) == 60) {
                    seconds = (parseInt(a[0]) * 60) + parseInt(a[1]);
                    minutes = seconds / 60;
                } else if (a.length == 2) {
                    minutes = parseInt(a[0]) + (parseInt(a[1]) / 100);
                } else if (a.length == 3 && parseInt(a[2]) == 60) {
                    seconds = (parseInt(a[0]) * 60 * 60) + (parseInt(a[1]) * 60) + parseInt(a[2]);
                    minutes = seconds / 60;
                } else {
                    seconds = (parseInt(a[0]) * 60 * 60) + (parseInt(a[1]) * 60);
                    minutes = (seconds / 60) + (parseInt(a[2]) / 100);
                }
                $("#contentDuration").val(minutes.toFixed(2));
            }
        }
    });
}
function showDigitizedFields() {
    if ($('#digitized').prop("checked") == false) {
        $("#digitizedBy_lbl").hide();
        $("#digitizedWhen_lbl").hide();
        $("#urn_lbl").hide();
    }

    $('#digitized').click(function () {
        if ($(this).prop("checked") == true) {
            $("#digitizedBy_lbl").show();
            $("#digitizedWhen_lbl").show();
            $("#urn_lbl").show();
        } else {
            $("#digitizedBy").val('');
            $("#digitizedWhen").val('');
            $("#urn").val('');
            $("#digitizedBy_lbl").hide();
            $("#digitizedWhen_lbl").hide();
            $("#urn_lbl").hide();
        }
    });
}

function showUpdateFields() {
    $('#format').change(function () {
        var showDiskDiameter = [16, 17, 18, 19, 20, 28];
        var showMediaDiameter = [1, 2, 3, 4, 5, 24];
        var showTapeThickness = [1, 2, 3, 4, 5];
        var showTrackType = [1, 2, 3, 4, 5];
        var showCassetteSize = [59, 60, 33, 34, 35, 43, 44, 46, 47, 48, 52, 53, 54, 55, 57];
        var hideRecordingSpeedFormat = [37, 39, 40, 41];
        var hideIfFormat = [24, 25, 26];
        var showSideIfFormat = [1, 10, 11, 13, 14, 16, 17, 18, 19, 20, 27, 28];
        var showNoiceRedIfFormat = [1, 2, 4, 5, 6, 7, 10, 13, 14, 27];
        var hideBaseIfFormat = [3, 4, 5];

        if (jQuery.inArray(parseInt($(this).val()), showDiskDiameter) >= 0) {
            if ($('#diskDiameters_lbl').data('view') == 'show')
                $('#diskDiameters_lbl').show();
        } else {
            $('#diskDiameters_lbl').hide();
        }

        if (jQuery.inArray(parseInt($(this).val()), showMediaDiameter) >= 0) {
            if ($('#mediaDiameters_lbl').data('view') == 'show')
                $('#mediaDiameters_lbl').show();
        } else {
            $('#mediaDiameters_lbl').hide();
        }

        if (jQuery.inArray(parseInt($(this).val()), showTapeThickness) >= 0) {
            if ($('#tapeThickness_lbl').data('view') == 'show')
                $('#tapeThickness_lbl').show();
        } else {
            $('#tapeThickness_lbl').hide();
        }

        if (jQuery.inArray(parseInt($(this).val()), showTrackType) >= 0) {
            if ($('#trackTypes_lbl').data('view') == 'show')
                $('#trackTypes_lbl').show();
        } else {
            $('#trackTypes_lbl').hide();
        }

        if (jQuery.inArray(parseInt($(this).val()), showCassetteSize) >= 0) {
            if ($('#cassetteSize_lbl').data('view') == 'show')
                $('#cassetteSize_lbl').show();
        } else {
            $('#cassetteSize_lbl').hide();
        }

        if (jQuery.inArray(parseInt($(this).val()), hideIfFormat) >= 0) {
            $('#slides_lbl, #monoStereo_lbl').hide();
        } else {
            if ($('#slides_lbl').data('view') == 'show')
                $('#slides_lbl').show();
            if ($('#monoStereo_lbl').data('view') == 'show')
                $('#monoStereo_lbl').show();
        }
        if (jQuery.inArray(parseInt($(this).val()), showSideIfFormat) >= 0) {
            if ($('#slides_lbl').data('view') == 'show')
                $('#slides_lbl').show();
        } else {
            $('#slides_lbl').hide();
        }
        if (jQuery.inArray(parseInt($(this).val()), showNoiceRedIfFormat) >= 0) {
            if ($('#noiceReduction_lbl').data('view') == 'show')
                $('#noiceReduction_lbl').show();
        } else {
            $('#noiceReduction_lbl').hide();
        }
        if ($(this).val()) {
            if (jQuery.inArray(parseInt($(this).val()), hideBaseIfFormat) >= 0) {
                $("#bases_lbl").hide();
            } else {
                var _url = baseUrl + 'getBase/' + $(this).val();
                if (typeof selectedbase != 'undefined' && selectedbase) {
                    _url = baseUrl + 'getBase/' + selectedbase + '/' + $(this).val();
                }
                /// call to get base dropdown options
                $.ajax({
                    type: "GET",
                    url: _url,
                    success: function (response) {
                        if (response != "") {
                            if ($('#bases_lbl').data('view') == 'show') {
                                $("#bases_lbl").show();
                                $("#bases").html(response);
                            }
                        } else {
                            $("#bases_lbl").hide();
                        }
                    }

                }); // Ajax Call
            }
            /// call to get reel diameters dropdown options
            if (selectedRD) {
                RDurl = baseUrl + 'getReelDiameter/' + $(this).val() + '/' + $("#mediaType").val() + '/' + selectedRD;
            } else {
                RDurl = baseUrl + 'getReelDiameter/' + $(this).val() + '/' + $("#mediaType").val();
            }
            $.ajax({
                type: "GET",
                url: RDurl,
                success: function (response) {
                    if (response != "") {
                        if ($('#reelDiameters_lbl').data('view') == 'show') {
                            $("#reelDiameters_lbl").show();
                            $("#reelDiameters").html(response);
                        }
                    } else {
                        $("#reelDiameters_lbl").hide();
                    }
                }

            }); // Ajax Call 
            if (jQuery.inArray(parseInt($(this).val()), hideRecordingSpeedFormat) >= 0) {
                $('#recordingSpeed_lbl').hide();
            } else {
//                $('#recordingSpeed_lbl').show();
                /// call to get recording speed dropdown options
                if (selectedRS) {
                    url = baseUrl + 'getRecordingSpeed/' + $(this).val() + '/' + $("#mediaType").val() + '/' + selectedRS;
                } else {
                    url = baseUrl + 'getRecordingSpeed/' + $(this).val() + '/' + $("#mediaType").val();
                }
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function (response) {
                        if (response != "") {
                            if ($('#recordingSpeed_lbl').data('view') == 'show') {
                                $("#recordingSpeed_lbl").show();
                                $("#recordingSpeed").html(response);
                            }
                        } else {
                            $("#recordingSpeed_lbl").hide();
                        }
                    }
                }); // Ajax Call  
            }
            /// call to get formatversion dropdown options
            if (selectedFormatVersion) {
                formatVersiourl = baseUrl + 'getFormatVersion/' + $(this).val() + '/' + selectedFormatVersion;
            } else {
                formatVersiourl = baseUrl + 'getFormatVersion/' + $(this).val();
            }
            $.ajax({
                type: "GET",
                url: formatVersiourl,
                success: function (response) {
                    if (response != "") {
                        if ($('#formatVersion_lbl').data('view') == 'show') {
                            $("#formatVersion_lbl").show();
                            $("#formatVersion").html(response);
                        }
                    } else {
                        $("#formatVersion_lbl").hide();
                    }
                }

            }); // Ajax Call   
        } else {
            $('#diskDiameters_lbl').hide();
            $('#reelDiameters_lbl').hide();
            $('#mediaDiameters_lbl').hide();
            $('#tapeThickness_lbl').hide();
            $('#trackTypes_lbl').hide();
            $('#cassetteSize_lbl').hide();
            $("#formatVersion_lbl").hide();
            $('#bases_lbl').hide();
            $('#recordingSpeed_lbl').hide();
        }
    });

}

function onChangeMediaType() {
    $(".new #mediaType").change(function () {
        if ($(this).val() == 3) {
            $('#fieldsPanel').hide();
            $('#processing').show();
            window.location.href = baseUrl + 'video/new';
        } else if ($(this).val() == 2) {
            $('#fieldsPanel').hide();
            $('#processing').show();
            window.location.href = baseUrl + 'film/new';
        } else if ($(this).val() == 1) {
            $('#fieldsPanel').hide();
            $('#processing').show();
            window.location.href = baseUrl + 'audio/new';
        } else {
            window.location.href = baseUrl + 'new/';
        }
    });
}

function saveBulkEdit() {
    $("#submitBulkEdit").click(function () {
        $("#digitizedByError").text("");
        $("#digitizedWhenError").text("");
        data = $('#frmBulkEdit').serialize();
        $(document).ajaxStart(function () {
            $("#frmBulkEdit").hide();
            $('#editProcessing').show();
            $('#editProcessing').css('color', 'black');
            $('#editProcessing').html('<img src="/images/ajax-loader.gif" /> <span><b>Processing please wait...</b></span>');
        });
        $.ajax({
            type: "POST",
            url: bulkEdit,
            data: data,
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    window.location.reload();
                }
            }
        }); // Ajax Call  
    });
}

function closeBtn() {
    $(".bulkEditCloseBtn").on('click', function () {
        $('#selectAll').prop("checked", false);
        $('input[name=record_checkbox]').each(function () {
            $(this).prop("checked", false);
        });
        $('#records tr').each(function () {
            $(this).removeClass("selected");
        });
        $("#selectedrecords").val('');
        window.location.reload();
    });
}

function updateProjects() {
    if (typeof projectId != 'undefined') {
        if (projectId) {
            selectedProject = projectId;
        }
    }
    proj = $("#project").val();
    var path = window.location.href;
    var split_path = path.split('/');
    var split = split_path[split_path.length - 1];
    var split_2 = split_path[split_path.length - 2];
    if (Math.floor(split) == split && $.isNumeric(split) && (split_2 == "new" || split_2 == "edit")) {
        selectedProject = split_path[split_path.length - 1];
    }
    if (selectedProject) {
        urlProjects = baseUrl + 'getAllProjects/' + selectedProject;
    } else {
        urlProjects = baseUrl + 'getAllProjects/';
    }
    $.ajax({
        type: "GET",
        url: urlProjects,
        success: function (response) {
            if (response != "") {
                $("#project").html(response);
                //$("#project").val(proj);
            } else {
                $("#project").html('');
                $('#project').after('&nbsp;&nbsp;Create a project in the Settings menu');
            }
        }
    }); // Ajax Call    
}

function updateViewSetting() {
    $('#project').change(function () {
        if ($('#project').parents('form').attr('id') != 'frmBulkEdit') {
            var proj_id = $('#project').val();

            if (proj_id) {
                var newUrl = viewUrl + proj_id;

                window.location = newUrl;

            }
        }
    });
}
function showBulkDigitizedFields() {
    $("#digitizedBy_lbl").hide();
    $("#digitizedWhen_lbl").hide();
    $("#urn_lbl").hide();
    $('.digitized').click(function () {
        if ($("input[name=digitized]:checked").val() == 1) {
            $("#digitizedBy_lbl").show();
            $("#digitizedWhen_lbl").show();
            $("#urn_lbl").show();
        } else {
            $("#digitizedBy").val('');
            $("#digitizedWhen").val('');
            $("#urn").val('');
            $("#digitizedBy_lbl").hide();
            $("#digitizedWhen_lbl").hide();
            $("#urn_lbl").hide();
        }
    });
}