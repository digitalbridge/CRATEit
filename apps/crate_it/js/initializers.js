function loadTemplateVars() {
    templateVars = {};
    $('#hidden_vars').children().each(function() {
        var $el = $(this);
        var key = $el.attr('id');
        var value = $el.text();

        if (! isNaN(value)) {
            value = +value;
        }

        templateVars[key] = value;
    });
}

function drawCrateContents() {
    // TODO: maybe get rid of this and just use reloadCrateData
    var c_url = OC.generateUrl('apps/crate_it/crate/get_manifest?crate_id={crateName}', {
        'crateName': encodeURIComponent(templateVars['selected_crate'])
    });

    $.ajax({
        url: c_url,
        type: 'get',
        dataType: 'json',
        success: function(data) {
            manifest = data;
            $tree = buildFileTree(data);
            indentTree();
        },
        error: function(data) {
            var e = data.statusText; // TODO: does nothing?
        }
    });
}

function initCrateActions() {
    var metadataEmpty = function() {
        var isEmpty = true;

        $('.metadata').each(function() {
            if ($(this).attr('id') != 'retention_period_value' && $(this).attr('id') != 'edit_embargo_details' && $(this).html() != '') {
                isEmpty = isEmpty && false;
            }
        });

        return isEmpty;
    };

    var syncCrate = function() {
        var c_url = OC.generateUrl('apps/crate_it/crate/sync');
        var crate_filename = $(this).data('filename');

        $.ajax({
            url: c_url,
            type: 'post',
            dataType: 'json',
            data: {
                'file_name': crate_filename
            },
            success: function(jqXHR) {
                displayNotification(jqXHR.msg);
            },
            error: function(jqXHR) {
                displayError(jqXHR.responseJSON.msg);
            }
        });
    };

    $('#mycratesModal .sync-crate').click(syncCrate);

    var checkCrate = function() {
        $('div#checkingCrateModal').modal();
        $('#result-message').text('');
        $('#check-results-table').empty();
        var c_url = OC.generateUrl('apps/crate_it/crate/check');

        $.ajax({
            url: c_url,
            type: 'get',
            dataType: 'json',
            async: true,
            success: function(data) {
                $('#result-message').html(data.msg);
            },
            error: function(data) {
                // TODO Format errors
            },
            complete: function(data) {
                $('div#checkingCrateModal').modal('hide');
                $('div#checkCrateModal').modal();
            }
        });
    };

    var crateEmpty = function() {
        return $tree.tree('getNodeById', 'rootfolder').children.length == 0;
    };

    var createCrate = function() {
        var $errors = 0;

        var $name = $('#crate_input_name');
        var $description = $('#crate_input_description');

        var $error_name = $('#crate_name_validation_error');
        var $error_description = $('#crate_description_validation_error');

        $error_name.text('').hide();
        $error_description.text('').hide();

        if (templateVars['validate_crate_name']) {
            var $nameError = validateCrateName($name);

            if ($nameError != '') {
                $error_name.text($nameError).show();
                $errors++;
            }
        }

        if (templateVars['validate_crate_description']) {
            var $descriptionError = validateCrateDescription($description);

            if ($descriptionError != '') {
                $error_description.text($descriptionError).show();
                $errors++;
            }
        }

        if ($errors > 0) {
            return false;
        } else {
            var params = {
                'name': $name.val(),
                'description': $description.val(),
                'data_retention_period': ''
            };

            var c_url = OC.generateUrl('apps/crate_it/crate/create');

            $.ajax({
                url: c_url,
                type: 'post',
                dataType: 'json',
                async: false,
                data: params,
                success: function(data) {
                    var crateName = data.crateName;
                    $('#crate_input_name').val('');
                    $('#crate_input_description').val('');
                    $('#createCrateModal').modal('hide');
                    $('#crates').append('<option id="' + crateName + '" value="' + crateName + '">' + crateName + '</option>');
                    $('#crates').val(crateName);
                    $('#crates').trigger('change');
                    displayNotification('Crate ' + crateName + ' successfully created', 6000);
                    location.reload();
                },
                error: function(jqXHR) {
                    // TODO: Make sure all ajax errors are this form instead of data.msg
                    displayError(jqXHR.responseJSON.msg);
                }
            });
        }
    };

    $('#createCrateModal').find('.btn-primary').click(createCrate);

    // Create Crate Keyup Validations
    $('#crate_input_name').keyup(function(e) {
        if (e.which === 9) {
            return false;
        }

        if (templateVars['validate_crate_name']) {
            var $name = $(this);
            var $error_name = $('#crate_name_validation_error');
            var $nameError = validateCrateName($name);

            if ($nameError != '') {
                $error_name.text($nameError);
                $error_name.show();
            } else {
                $error_name.text('');
                $error_name.hide();
            }
        }
    });

    $('#crate_input_description').keyup(function(e) {
        if (e.which === 9) {
            return false;
        }

        if (templateVars['validate_crate_description']) {
            var $description = $(this);
            var $error_description = $('#crate_description_validation_error');
            var $descriptionError = validateCrateDescription($description);

            if ($descriptionError != '') {
                $error_description.text($descriptionError);
                $error_description.show();
            } else {
                $error_description.text('');
                $error_description.hide();
            }
        }
    });

    var deleteCrate = function() {
        var current_crate = $('#crates').val();

        $.ajax({
            url: OC.generateUrl('apps/crate_it/crate/delete'),
            type: 'get',
            dataType: 'json',
            success: function(data) {
                displayNotification(data.msg);
                location.reload();
            },
            error: function(jqXHR) {
                displayError(jqXHR.responseJSON.msg);
            }
        });

        $('#deleteCrateModal').modal('hide');
    };

    var downloadCrate = function() {
        if (treeHasNoFiles()) {
            displayNotification('No items in the crate to package');
            return;
        }

        displayNotification('Your download is being prepared. This might take some time if the files are big', 10000);
        var c_url = OC.generateUrl('apps/crate_it/crate/downloadzip?requesttoken={requesttoken}', {
            requesttoken: oc_requesttoken
        });
        window.location = c_url;
    };

    var downloadReports = function() {
        displayNotification('Your download is being prepared. This might take some time if the files are big', 10000);
        var c_url = OC.generateUrl('apps/crate_it/crate/downloadcsv?requesttoken={requesttoken}', {
            requesttoken: oc_requesttoken
        });
        window.location = c_url;
    };

    $('#checkCrateModal').on('hide.bs.modal', function() {
        location.reload();
    });

    $('#createCrateModal').on('show.bs.modal', function() {
        if (templateVars['validate_crate_name']) {
            $('label[for="crate_input_name"]').addClass('req');
        }

        if (templateVars['validate_crate_description']) {
            $('label[for="crate_input_description"]').addClass('req');
        }

        $('#crate_input_name').val('');
        $('#crate_input_description').val('');
        $('#crate_name_validation_error').hide();
        $('#crate_description_validation_error').hide();
    });

    $('#clearCrateModal').find('.btn-primary').click(function() {
        var children = $tree.tree('getNodeById', 'rootfolder').children;
        // NOTE: The while loop is a workaround to the forEach loop inexplicably skipping
        // the first element
        while (children.length > 0) {
            children.forEach(function(node) {
                $tree.tree('removeNode', node);
            });
        }
        saveTree($('#crates').val() + ' has been cleared');
        indentTree();
        $('#clearCrateModal').modal('hide');
    });

    $('#deleteCrateModal').on('show.bs.modal', function() {
        var currentCrate = $('#crates').val();
        if (! metadataEmpty() && ! crateEmpty()) {
            $('#deleteCrateMsg').text('Crate ' + currentCrate + ' has items and metadata, proceed with deletion?');
        } else if (! metadataEmpty()) {
            $('#deleteCrateMsg').text('Crate ' + currentCrate + ' has metadata, proceed with deletion?');
        } else if (! crateEmpty()) {
            $('#deleteCrateMsg').text('Crate ' + currentCrate + ' has items, proceed with deletion?');
        }
    });

    $('#deleteCrateModal').find('.btn-primary').click(deleteCrate);

    $('#delete').click(function() {
        if (metadataEmpty() && crateEmpty()) {
            deleteCrate();
        } else {
            $('#deleteCrateModal').modal('show');
        }
    });

    $('#check').click(checkCrate);

    $('#crates').change(function() {
        var id = $(this).val();
        var c_url = OC.generateUrl('apps/crate_it/crate/get_manifest?crate_id={crateName}', {
            crateName: encodeURIComponent(id)
        });

        $.ajax({
            url: c_url,
            type: 'get',
            dataType: 'json',
            async: false, // TODO: why not async?
            success: function(data) {
                manifest = data;
                reloadCrateData(data);
            },
            error: function(jqXHR) {
                displayError(jqXHR.responseJSON.msg);
            }
        });
    });

    $('#download-zip').click(downloadCrate);
    $('#usage-reports').click(downloadReports);

    var publishCrate = function(crateName, endpoint, collection) {
        var c_url = OC.generateUrl('apps/crate_it/crate/publish');
        var postData = {
            'name': crateName,
            'endpoint': endpoint,
            'collection': collection
        };

        $('div#publishingCrateModal').modal();

        $.ajax({
            url: c_url,
            type: 'post',
            data: postData,
            dataType: 'json',
            success: function(data) {
                $('div#publishingCrateModal').modal('hide');
                confirmPublish(data);
            },
            error: function(jqXHR) {
                $('div#publishingCrateModal').modal('hide');
                confirmPublish(jqXHR.responseJSON);
            }
        });
    };

    var confirmPublish = function(data) {
        var msg = data.msg;
        var metadata = data.metadata;

        $('#publish-confirm-status').text(msg);
        $('#publishConfirmModal').modal('show');
        $('#publish-confirm-email-send').click(function() {
            var c_url = OC.generateUrl('apps/crate_it/crate/email');

            $.ajax({
                url: c_url,
                type: 'post',
                data: {
                    address: $('#publish-confirm-email').val(),
                    metadata: metadata
                },
                dataType: 'json',
                success: function(data) {
                    $('#publish-confirm-email-status').text(data.msg);
                },
                error: function(jqXHR) {
                    $('#publish-confirm-email-status').text(jqXHR.responseJSON.msg);
                }
            });
        });

        $('#publishConfirmModal').on('hide.bs.modal', function(e) {
            e.preventDefault();
            window.location.reload();
        });
    };

    var $publishConfirmModal = $('#publishConfirmModal');
    var publishConfirmValidator = new CrateIt.Validation.FormValidator($publishConfirmModal);
    publishConfirmValidator.addValidator($('#publish-confirm-email'), new CrateIt.Validation.EmailValidator());

    $('#publish').click(function() {
        if (treeHasNoFiles()) {
            displayNotification('You must add files to crate to publish.');
            return;
        }

        $crateName = manifest.vfs[0].name;
        if ($crateName == 'default_crate') {
            displayNotification('Crate name cannot be "default_crate". Please update and try again.');
            return;
        }

        $('#publishModalLabel .crate-name').text(' - ' + $crateName);

        // TODO: Migrate to a single client side shared model of the manifest
        // TODO: let this be handled by the search managers perhaps?
        $('div#checkingCrateModal').modal();
        $('#publish-consistency').text('');
        $('#publish-consistency-table').empty();
        $('#publishModal .modal-footer .btn-primary, #publishModal .modal-header .btn-primary').prop('disabled', false);
        updateCrateSize();

        var c_url = OC.generateUrl('apps/crate_it/crate/check');

        $.ajax({
            url: c_url,
            type: 'get',
            dataType: 'json',
            async: true,
            success: function(data) {
                if (! data.valid) {
                    $('#publish-consistency').html('[Consistency Check]<br />' + data.msg);
                    $('#publishModal .modal-footer .btn-primary, #publishModal .modal-header .btn-primary').prop('disabled', true);
                }
            },
            error: function(jqXHR) {
                $('#publish-consistency').text('Unable to determine crate consistency');
            },
            complete: function() {
                $('div#checkingCrateModal').modal('hide');
                $('#publishModal').modal();
            }
        });

        if ($('#publish-collection > option').length <= 1) {
            $('#collection-choice').hide();
        }

        $('#publish-location').text($('#location').text());

        $('#publish-description').text($('#description').text());
        $('#publish-data-retention-period').text($('#retention_period_value').text() + ' (years)');

        $('#publish-embargo-enabled').text($('span#embargo_enabled').text());
        $('#publish-embargo-date').text($('span#embargo_until').text());
        $('#publish-embargo-note').text($('span#embargo_note').text());
        $('#publish-access-conditions').text($('label[for="' + $('input[name=access_conditions]:checked').attr('id') + '"]').text());
        $('#publish-access-permissions-statement').text($('#access_permissions_statement').val());

        $('#publish-creators').children().remove();
        // TODO: create proper render functions
        var records = CreatorSearchManager.getSelected();
        records.forEach(function(record) {
            var html = CreatorSearchManager.renderSummary(record);
            $('#publish-creators').append(html);
        });

        $('#publish-activities').children().remove();
        records = ActivitySearchManager.getSelected();
        records.forEach(function(record) {
            var html = ActivitySearchManager.renderSummary(record);
            $('#publish-activities').append(html);
        });

        manifest = getManifest();
        if (manifest['for_keywords'] && manifest['for_keywords'].length) {
            updateKeywordsPublishList(manifest.for_keywords);
        }

        $('#publish-fors').children().remove();
        records = ForSearchManager.getSelected();
        if (records) {
            records.forEach(function(record) {
                var html = ForSearchManager.renderSummary(record);
                $('#publish-fors').append(html);
            });
        }

        $('#publish-primarycontacts').children().remove();
        records = PrimaryContactSearchManager.getSelected();
        if (records) {
            records.forEach(function(record) {
                var html = PrimaryContactSearchManager.renderSummary(record);
                $('#publish-primarycontacts').append(html);
            });
        }
    });

    if ($('#publish-collection > option').length == 0) {
        $('#publish-collection').next().css('display', 'inline');
        $('#publishModal').find('.btn-primary').prop('disabled', true);
    } else {
        $('#publish-collection').next().css('display', 'none');
        $('#publishModal').find('.btn-primary').prop('disabled', false);
    }

    $('#publishModal').find('.btn-primary').click(function() {
        var crateName = $('#crates').val();
        var endpoint = $('#publish-collection option:selected').attr('data-endpoint');
        var collection = $('#publish-collection').val();
        $('#publishModal').modal('hide');
        publishCrate(crateName, endpoint, collection);
    });

    $('#userguide').click(function(event) {
        event.preventDefault();
        window.open($(this).attr('href'), 'popupWindow', 'width=600,height=600,scrollbars=yes');
    });
}

/*
To Edit in Meta Data Template
function setupNameOps() {
    $('#crate_input_name').keyup(function() {
        var name_length = templateVars['name_length'];
        if ($(this).val().length > name_length) {
            $('#crate_name_validation_error').text('Crate Name has reached the limit of ' + name_length + ' characters');
            $('#crate_name_validation_error').show();
            $(this).val($(this).val().substr(0, name_length));
        } else {
            $('#crate_name_validation_error').text('');
        }
    });

    // Edit Crate
    $('#edit_name').click(function(event) {
        var old_name = $('#name').text();
        $('#name').text('');
        $('#name').html('<textarea id="name" maxlength="' + name_length + '" style="width: 40%;" placeholder="Enter a name for this Crate">' + old_name + '</textarea><br /><div id="edit_name_validation_error" style="color: red;"></div><input type="button" id="save_name" value="Save" /><input type="button" id="cancel_name" value="Cancel" />');
        setupEditNameOp();
        $('#edit_name').addClass('hidden');
        $('#save_name').click(function(event) {
            var c_url = OC.generateUrl('apps/crate_it/crate/update');

            // Perform validation
            if (templateVars['validate_crate_name'] == 'true') {
                var crateName = $('input#name').val();
                var errors = false;
                $('#crate-information-modal-ul').html('');

                if (! crateName) {
                    errors = true;
                    $('#crate-information-modal-ul').append('<li>You must enter a CRATE name</li>');
                } else if (crateName.val.length > name_length) {
                    errors = true;
                    $('#crate-information-modal-ul').append('<li>The CRATE name must be less than: ' + name_length + ' characters</li>');
                }

                // Show the Error modal
                if (errors) {
                    $('#crate-information-submission-modal').modal({});
                    return;
                }
            }

            $.ajax({
                url: c_url,
                type: 'post',
                dataType: 'json',
                data: {
                    'fields': [{
                        'field': 'name',
                        'value': $('#crate_name').val()
                    }]
                },
                success: function(data) {
                    $('#name').html('');
                    $('#name').text(data.values['name']);
                    $('#edit_name').removeClass('hidden');
                    calulateHeights();
                },
                error: function(jqXHR) {
                    displayError(jqXHR.responseJSON.msg);
                }
            });
        });

        $('#cancel_name').click(function(event) {
            $('#name').html('');
            $('#name').text(old_name);
            $('#edit_name').removeClass('hidden');
        });
    });
}
*/

function setupDescriptionOps() {
    // $('#crate_input_description').keyup(function() {
    //     var description_length = templateVars['description_length'];
    //     if ($(this).val().length > description_length) {
    //         $('#crate_description_validation_error').text('Crate Description has reached the limit of ' + description_length + ' characters');
    //         $('#crate_description_validation_error').show();
    //         $(this).val($(this).val().substr(0, description_length));
    //     } else {
    //         $('#crate_description_validation_error').text('');
    //     }
    // });

    $(window).load(function() {
        $('#crate-information-head').click();

        setDescriptionHeight();
    });

    function setDescriptionHeight() {
        var panelBody = $('#crate-information .panel-body').height();
        var descTitle = $('#description_box h6').outerHeight();
        var crateSize = $('#crate-information .crate-size').outerHeight();
        var margTop = parseInt($('#description').css('margin-bottom').replace('px', ''));
        var margBot = parseInt($('#description').css('margin-bottom').replace('px', ''));
        var paddTop = parseInt($('#description').css('padding-bottom').replace('px', ''));
        var paddBot = parseInt($('#description').css('padding-bottom').replace('px', ''));
        var selfMarPad = margTop + margBot + paddTop + paddBot;

        var descHeight = panelBody - descTitle - crateSize - selfMarPad - 10;
        $('#description').css('max-height', descHeight);
    }

    $('#edit_description').click(function(event) {
        var old_description = $('#description').text();
        var description_length = templateVars['description_length'];
        $('#description').text('').addClass('no-border');
        $('#description').html('<textarea id="crate_description" maxlength="' + description_length + '" placeholder="Enter a description of the research data package for this Crate">' + old_description + '</textarea><div class="char-count"><span></span> / ' + description_length + '</div><br /><div id="edit_description_validation_error" style="color: red;"></div><input type="button" id="save_description" value="Save" /><input type="button" id="cancel_description" value="Cancel" /><span class="message error"></span>');
        $('#description textarea').height($(this).closest('.panel-body').height() / 2);
        setupEditDescriptionOp();
        $('#edit_description').addClass('hidden');

        $('#description').find('textarea').on('keyup', function() {
            chars = $(this).val().length;

            $(this).next('.char-count').find('span').text(chars);
        });

        $('#save_description').click(function(event) {
            var c_url = OC.generateUrl('apps/crate_it/crate/update');

            // Perform validation
            if (templateVars['validate_crate_description']) {
                var crateDescription = $('#description').find('textarea').val();

                if (! crateDescription) {
                    $('#description_box .message').text('You must enter a CRATE description');
                    return;
                } else if (crateDescription.length > description_length) {
                    $('#description_box .message').text('The CRATE description must be less than ' + description_length + ' characters');
                    return;
                }
            }

            $.ajax({
                url: c_url,
                type: 'post',
                dataType: 'json',
                data: {
                    'fields': [{
                        'field': 'description',
                        'value': $('#crate_description').val()
                    }]
                },
                success: function(data) {
                    $('#description').html('').removeClass('no-border');
                    $('#description').text(data.values['description']);
                    $('#edit_description').removeClass('hidden');
                    calulateHeights();
                },
                error: function(jqXHR) {
                    displayError(jqXHR.responseJSON.msg);
                }
            });
        });

        $('#cancel_description').click(function(event) {
            // $('#description').html('');
            // var escaped = $('<div>').text(old_description).text();
            // $('#description').html(escaped.replace(/\n/g, '<br />'));
            // $('#edit_description').removeClass('hidden');

            $('#description').html('').removeClass('no-border');
            $('#description').text(old_description);
            $('#edit_description').removeClass('hidden');
        });
    });
}

function setupRetentionPeriodOps() {
    var html = '';
    html += '<select name="retention_radio" id="retention_period">';
    html += '<option value="Please Select">Select Option</option>';
    html += '<option value="Perpetuity">Perpetuity</option>';
    for (i = 1; i < 51; i++) {
        html += '<option value="' + i + '">' + i + '</option>';
    }
    html += '</select><br /><br />';

    html += '<input id="save_retention_period" type="button" value="Save" />';
    html += '<input id="cancel_retention_period" type="button" value="Cancel" />';
    html += '<span class="message error"></span>';

    $('#choose_retention_period').click(function(event) {
        var old_retention_period = $('#retention_period_value').text(); // this does the same thing

        $('#retention_period_value').text('');
        $('#retention_period_value').html(html);
        $('#choose_retention_period').addClass('hidden');
        $('#retention_period').val(old_retention_period);

        $('#save_retention_period').click(function(event) {
            var $retentionPeriod = $('#retention_period').val();

            if (templateVars['validate_data_retention_period'] && $retentionPeriod == 'Please Select') {
                $('#retention_period_value .message').text('Please select an option');
                return;
            } else {
                $('#retention_period_value .message').text('');
                var c_url = OC.generateUrl('apps/crate_it/crate/update');

                $.ajax({
                    url: c_url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'fields': [{
                            'field': 'data_retention_period',
                            'value': $retentionPeriod
                        }]
                    },
                    success: function(data) {
                        $('#retention_period_value').html('');
                        $('#retention_period_value').text(data.values['data_retention_period']);
                        $('#choose_retention_period').removeClass('hidden');
                    },
                    error: function(jqXHR) {
                        displayError(jqXHR.responseJSON.msg);
                    }
                });
            }
        });

        $('#cancel_retention_period').click(function(event) {
            $('#retention_period_value').html('');
            $('#retention_period_value').text(old_retention_period);
            $('#choose_retention_period').removeClass('hidden');
        });
    });
}

function setupForKeywordsOps() {
    manifest = getManifest();

    if (manifest.for_keywords) {
        if (typeof manifest.for_keywords == 'string') {
            $keywords = [];
            $keywords.push(manifest.for_keywords);
        } else {
            $keywords = manifest.for_keywords;
        }
    } else {
        $keywords = [];
    }

    if ($keywords) {
        updateKeywordsList($keywords);
    } else {
        $('#keywords_list').html('');
    }

    $('#for_keywords_container #save_keyword').click(function(e) {
        $new_keywords = $('#for_keyword').val().split(',');

        $.each($new_keywords, function(key, value) {
            if ($.inArray(value.trim(), $keywords) == -1) {
                $keywords.push(value.trim());
            }
        });

        updateKeywords($keywords, 'add', 'Keywords updated');
    });

    $(document).on('click', '#keywords_list li a', function(e) {
        e.preventDefault();

        if (! $('#keywords_list').hasClass('disabled')) {
            $del = $(this).data('index');
            $keywords.splice($del, 1);
            updateKeywords($keywords, 'remove', 'Keyword removed');
        }
    });

    function updateKeywords(keywords, action, message) {
        var c_url = OC.generateUrl('apps/crate_it/crate/update');

        $('#keywords_list').addClass('disabled');

        $.ajax({
            url: c_url,
            type: 'post',
            dataType: 'json',
            data: {
                'fields': [{
                    'field': 'for_keywords',
                    'value': keywords
                }]
            },
            success: function(data) {
                $('.for_keywords_heading .message').text(message).show();
                setTimeout(function() {
                    $('.for_keywords_heading .message').animate({
                        opacity: 0
                    }, 400, function() {
                        $('#keywords_list').removeClass('disabled');
                        $('.for_keywords_heading .message').empty().hide().css('opacity', 100);
                    });
                }, 1500);

                if (action == 'add') {
                    $('#for_keyword').val('');
                }

                updateKeywordsList(keywords);
            },
            error: function(jqXHR) {
                displayError(jqXHR.responseJSON.msg);
            }
        });
    }
}

function updateKeywordsList(keywords) {
    $('#for_keywords_container #keywords_list').empty();

    $.each(keywords, function(key, value) {
        $('#for_keywords_container #keywords_list').append('<li>' + value + '<a href="#" data-index="' + key + '"><i class="fa fa-times"></i></a></li>');
    });
}

function updateKeywordsPublishList(keywords) {
    $('#publish-keywords').empty();

    $count = 0;
    $.each(keywords, function(key, value) {
        if ($count === 0) {
            $('#publish-keywords').append(value);
        } else {
            $('#publish-keywords').append(', ' + value);
        }
        $count++;
    });
}

function setupAccessConditionsOps() {
    $('#save_access_conditions').click(function(event) {
        var accessConditions = $('input[name=access_conditions]:checked').val();
        var accessPermissionStatement = $('#access_permissions_statement').val();
        $('#access_conditions_options .message').text('').removeClass('error').removeClass('success');

        if (templateVars['validate_access_conditions'] && ! accessConditions) {
            $('#access_conditions_list .message').text('Please select an Access Condition.').addClass('error');
            return;
        } else if (templateVars['validate_access_permissions_statement'] && ! accessPermissionStatement) {
            $('#access_conditions_list .message').text('Please complete the Access Permissions Statement field.').addClass('error');
        } else {
            var c_url = OC.generateUrl('apps/crate_it/crate/update');

            $.ajax({
                url: c_url,
                type: 'post',
                dataType: 'json',
                data: {
                    'fields': [{
                        'field': 'access_conditions',
                        'value': accessConditions
                    }, {
                        'field': 'access_permissions_statement',
                        'value': accessPermissionStatement
                    }]
                },
                success: function(data) {
                    $('#access_conditions_list .message').text('Access Conditions saved.').addClass('success')

                    setTimeout(function() {
                        $('#access_conditions_list .message.success').animate({
                            opacity: 0
                        }, 400, function() {
                            $('#access_conditions_list .message.success').removeClass('success').text('').css('opacity', 100);
                        });
                    }, 1500);
                },
                error: function(jqXHR) {
                    displayError(jqXHR.responseJSON.msg);
                }
            });
        }
    });
}

function setupEmbargoDetailsOps() {
    var oldEmbargoEnabled;
    var oldEmbargoDisabled;
    var oldEmbargoDate;
    var oldEmbargoDetails;
    var oldAccessConditions;

    $('#choose_embargo_details').click(function(event) {
        $('#embargo-summary').hide();
        $('#edit_embargo_details').show();
        $('input[name="embargo_enabled"]:checked').change();

        oldEmbargoEnabled = $('#embargo_enabled_yes').is(':checked');
        oldEmbargoDisabled = $('#embargo_enabled_no').is(':checked');
        oldEmbargoDate = $('input#embargo_date').val();
        oldEmbargoDetails = $('textarea#embargo_details').val();
        oldAccessConditions = $('input[name=access_conditions]:checked').val();
    });

    $('input[name="embargo_enabled"]').change(function(e) {
        if ($(this).val() == 'no') {
            $('#embargo_date').next('.add-on').hide();
            $('#embargo_details').prop('disabled', true);
            $('input[name="access_conditions"]').prop('disabled', true);
        } else {
            $('#embargo_date').next('.add-on').show();
            $('#embargo_details').prop('disabled', false);
            $('input[name="access_conditions"]').prop('disabled', false);
        }
    });

    $('#save_embargo').click(function(event) {
        var c_url = OC.generateUrl('apps/crate_it/crate/update');

        // Perform validation
        var embargoEnabled = $('#embargo_enabled_yes').is(':checked');
        var embargoDisabled = $('#embargo_enabled_no').is(':checked');
        var embargoDate = $('input#embargo_date').val();
        var embargoDetails = $('textarea#embargo_details').val();
        var accessConditions = $('input[name=access_conditions]:checked').val();

        var errors = false;
        $('#embargo-details-modal-ul').html('');

        if (! embargoEnabled && ! embargoDisabled) {
            errors = true;
            $('#embargo-details-modal-ul').append('<li>Embargo enabled must be set to yes or no</li>');
        }

        if (embargoEnabled) {
            if (! embargoDate) {
                errors = true;
                $('#embargo-details-modal-ul').append('<li>Embargo date must not be blank</li>');
            }

            if (! embargoDetails) {
                errors = true;
                $('#embargo-details-modal-ul').append('<li>Embargo details must not be blank</li>');
            } else if (embargoDetails.length > 1024) {
                errors = true;
                $('#embargo-details-modal-ul').append('<li>Embargo details must be less than 1024 characters in length</li>');
            }

            if (typeof(accessConditions) === 'undefined') {
                errors = true;
                $('#embargo-details-modal-ul').append('<li>Access conditions must not be blank</li>');
            }
        } else {
            embargoDate = '';
            $('input#embargo_date').val('');
            embargoDetails = '';
            $('textarea#embargo_details').val('');
            accessConditions = '';
            $('input[name=access_conditions]').attr('checked', false);
        }

        // Show the modal
        if (errors) {
            $('#embargo-details-submission-modal').modal({});
            return;
        }

        $.ajax({
            url: c_url,
            type: 'post',
            dataType: 'json',
            data: {
                'fields': [{
                    'field': 'embargo_enabled',
                    'value': embargoEnabled
                }, {
                    'field': 'embargo_date',
                    'value': embargoDate
                }, {
                    'field': 'embargo_details',
                    'value': embargoDetails
                }, {
                    'field': 'access_conditions',
                    'value': accessConditions
                }]
            },
            success: function(data) {
                $('span#embargo_enabled').html(data.values['embargo_enabled'] === 'true' ? 'Yes' : 'No');
                $('span#embargo_until').html(data.values['embargo_date']);
                $('span#embargo_note').html(data.values['embargo_details'].replace(/\n/g, '<br />'));
                $('span#access_conditions').html(data.values['access_conditions']);
                $('#embargo-summary').show();
                $('#edit_embargo_details').hide();
            },
            error: function(jqXHR) {
                displayError(jqXHR.responseJSON.msg);
            }
        });
    });

    $('#cancel_embargo').click(function(event) {
        $('#embargo-summary').show();
        $('#edit_embargo_details').hide();

        // Reset the inputs
        $('#embargo_enabled_yes').prop('checked', oldEmbargoEnabled);
        $('#embargo_enabled_no').prop('checked', oldEmbargoDisabled);
        $('input#embargo_date').val(oldEmbargoDate);
        $('textarea#embargo_details').val(oldEmbargoDetails);
        $('#embargo_closed').prop('checked', oldAccessConditions === 'closed');
        $('#embargo_open').prop('checked', oldAccessConditions === 'open');
        $('#embargo_shared').prop('checked', oldAccessConditions === 'shared');
    });
}

function initSearchHandlers() {
    // TODO: prefix this with var to close scope when not dubugging
    // TODO: replace this call with a variable shared between buildFileTree as the manifest is retrieved multiple times
    manifest = getManifest();
    $clearMetadataModal = $('#clearMetadataModal');

    var creatorDefinition = {
        manifestField: 'creators',
        actions: {
            search: 'people'
        },
        mapping: {
            'id': 'id',
            'identifier': 'dc_identifier',
            'name': ['Honorific', 'Given_Name', 'Family_Name'],
            'email': 'Email'
        },
        displayFields: ['name', 'email'],
        editFields: ['name', 'email', 'identifier'],
        editableRecords: ['manual', 'mint']
    };

    // TODO: a lot of these elements could be pushed into the SearchManager constructor so it creates the widget
    var creatorSelectedList = manifest.creators;
    var creator$resultsUl = $('#search_people_results');
    var creator$selectedUl = $('#selected_creators');
    var creator$notification = $('#creators_search_notification');
    var creator$editModal = $('#editCreatorsModal');

    // TODO: for add it's 'creator', but edit it's 'creators' logic works on field name, so make them call creators
    var editCreatorValidator = new CrateIt.Validation.FormValidator(creator$editModal);
    if (templateVars['validate_data_creators_name']) {
        editCreatorValidator.addValidator($('#edit-creators-name'), new CrateIt.Validation.RequiredValidator('Name'));
        editCreatorValidator.addValidator($('#edit-creators-name'), new CrateIt.Validation.MaxLengthValidator('Name', 256));
    }

    if (templateVars['validate_data_creators_email']) {
        editCreatorValidator.addValidator($('#edit-creators-email'), new CrateIt.Validation.RequiredValidator('Email'));
        editCreatorValidator.addValidator($('#edit-creators-email'), new CrateIt.Validation.MaxLengthValidator('Email', 128));
        editCreatorValidator.addValidator($('#edit-creators-email'), new CrateIt.Validation.EmailValidator());
    }

    var editCreatorUrlValidator = new CrateIt.Validation.UrlValidator();
    if (templateVars['validate_data_creators_url']) {
        editCreatorValidator.addValidator($('#edit-creators-identifier'), new CrateIt.Validation.RequiredValidator('Identifier'));
        editCreatorValidator.addValidator($('#edit-creators-identifier'), new CrateIt.Validation.MaxLengthValidator('Identifier', 2000));
    }
    editCreatorValidator.addValidator($('#edit-creators-identifier'), new CrateIt.Validation.OptionalValidator(editCreatorUrlValidator));

    // TODO: add this to a namespace rather than exposing globally
    CreatorSearchManager = new SearchManager(creatorDefinition, creatorSelectedList, creator$resultsUl, creator$selectedUl, creator$notification, creator$editModal);
    $('#search_people').click(function() {
        CreatorSearchManager.search($.trim($('#keyword_creator').val()));
    });

    $('#keyword_creator').keyup(function(e) {
        if (e.keyCode == 13) {
            CreatorSearchManager.search($.trim($(this).val()));
        }
    });

    var creatorsCount = function(e) {
        $('#creators_count').text(e.selected);
    };

    CreatorSearchManager.addEventListener(creatorsCount);
    CreatorSearchManager.notifyListeners();

    $('#clear_creators').click(function() {
        $('#clearMetadataField').text('creators');
        attachModalHandlers($clearMetadataModal, CreatorSearchManager.clearSelected);
    });

    var addCreator = function() {
        var name = $('#add-creator-name').val();
        var email = $('#add-creator-email').val();
        var identifier = $('#add-creator-identifier').val();
        var overrides = {
            'name': name,
            'email': email,
            'identifier': identifier
        };
        CreatorSearchManager.addRecord(overrides);
    };

    var $addCreatorModal = $('#addCreatorModal');
    var $addCreatorConfirm = $addCreatorModal.find('.btn-primary');

    var addCreatorValidator = new CrateIt.Validation.FormValidator($addCreatorModal);
    if (templateVars['validate_data_creators_name']) {
        addCreatorValidator.addValidator($('#add-creator-name'), new CrateIt.Validation.RequiredValidator('Name'));
        addCreatorValidator.addValidator($('#add-creator-name'), new CrateIt.Validation.MaxLengthValidator('Name', 256));
    }

    if (templateVars['validate_data_creators_email']) {
        addCreatorValidator.addValidator($('#add-creator-email'), new CrateIt.Validation.RequiredValidator('Email'));
        addCreatorValidator.addValidator($('#add-creator-email'), new CrateIt.Validation.MaxLengthValidator('Email', 128));
        addCreatorValidator.addValidator($('#add-creator-email'), new CrateIt.Validation.EmailValidator());
    }

    var addCreatorUrlValidator = new CrateIt.Validation.UrlValidator();
    if (templateVars['validate_data_creators_url']) {
        addCreatorValidator.addValidator($('#add-creator-identifier'), new CrateIt.Validation.RequiredValidator('Identifier'));
        addCreatorValidator.addValidator($('#add-creator-identifier'), new CrateIt.Validation.MaxLengthValidator('Identifier', 2000));
    }
    addCreatorValidator.addValidator($('#add-creator-identifier'), new CrateIt.Validation.OptionalValidator(addCreatorUrlValidator));

    if (! templateVars['validate_data_creators_name'] && ! templateVars['validate_data_creators_email'] && ! templateVars['validate_data_creators_url']) {
        $('#addCreatorModal .modal-footer .btn-primary, #editCreatorsModal .modal-footer .btn-primary').removeAttr('disabled').addClass('noval');
    }

    // TODO: this doesn't need to be dynamically attached, maybe create a second helper
    $('#add-creator').click(function() {
        attachModalHandlers($addCreatorModal, addCreator);
    });

    // Primary Contact
    var primaryContactDefinition = {
        manifestField: 'primarycontacts',
        actions: {
            search: 'people'
        },
        mapping: {
            'id': 'id',
            'identifier': 'dc_identifier',
            'name': ['Honorific', 'Given_Name', 'Family_Name'],
            'email': 'Email'
        },
        displayFields: ['name', 'email'],
        editFields: ['name', 'email', 'identifier'],
        editableRecords: ['manual', 'mint']
    };

    // TODO: a lot of these elements could be pushed into the SearchManager constructor so it creates the widget
    var primaryContactSelectedList = manifest.primarycontacts;
    var primaryContactResultsUl = $('#search_primarycontacts_results');
    var primaryContactSelectedUl = $('#selected_primarycontacts');
    var primaryContactNotification = $('#primarycontacts_search_notification');
    var primaryContactEditModal = $('#editPrimaryContactsModal');

    var editPrimaryContactValidator = new CrateIt.Validation.FormValidator(primaryContactEditModal);
    if (templateVars['validate_primary_contact_name']) {
        editPrimaryContactValidator.addValidator($('#edit-primarycontacts-name'), new CrateIt.Validation.RequiredValidator('Name'));
        editPrimaryContactValidator.addValidator($('#edit-primarycontacts-name'), new CrateIt.Validation.MaxLengthValidator('Name', 256));
    }

    if (templateVars['validate_primary_contact_email']) {
        editPrimaryContactValidator.addValidator($('#edit-primarycontacts-email'), new CrateIt.Validation.RequiredValidator('Email'));
        editPrimaryContactValidator.addValidator($('#edit-primarycontacts-email'), new CrateIt.Validation.MaxLengthValidator('Email', 128));
        editPrimaryContactValidator.addValidator($('#edit-primarycontacts-email'), new CrateIt.Validation.EmailValidator());
    }

    var editPrimaryContactUrlValidator = new CrateIt.Validation.UrlValidator();
    if (templateVars['validate_primary_contact_url']) {
        editPrimaryContactUrlValidator = new CrateIt.Validation.OptionalValidator(editPrimaryContactUrlValidator);
        editPrimaryContactValidator.addValidator($('#edit-primarycontacts-identifier'), new CrateIt.Validation.MaxLengthValidator('Identifier', 2000));
        editPrimaryContactValidator.addValidator($('#edit-primarycontacts-identifier'), new CrateIt.Validation.IgnoredWhenHiddenValidator(editPrimaryContactUrlValidator));
    }

    PrimaryContactSearchManager = new SearchManager(primaryContactDefinition, primaryContactSelectedList, primaryContactResultsUl, primaryContactSelectedUl, primaryContactNotification, primaryContactEditModal);
    $('#search_primarycontacts').click(function() {
        PrimaryContactSearchManager.search($.trim($('#keyword_primarycontact').val()));
    });

    $('#keyword_primarycontact').keyup(function(e) {
        if (e.keyCode == 13) {
            PrimaryContactSearchManager.search($.trim($(this).val()));
        }
    });

    var primaryContactsCount = function(e) {
        $('#primarycontacts_count').text(e.selected);
    };

    PrimaryContactSearchManager.addEventListener(primaryContactsCount);
    PrimaryContactSearchManager.notifyListeners();

    $('#clear_primarycontacts').click(function() {
        $('#clearMetadataField').text('primarycontacts');
        attachModalHandlers($clearMetadataModal, PrimaryContactSearchManager.clearSelected);
    });

    var addPrimaryContact = function() {
        var name = $('#add-primarycontact-name').val();
        var email = $('#add-primarycontact-email').val();
        var identifier = $('#add-primarycontact-identifier').val();
        var overrides = {
            'name': name,
            'email': email,
            'identifier': identifier
        };
        PrimaryContactSearchManager.addRecord(overrides);
    };

    var $addPrimaryContactModal = $('#addPrimaryContactModal');
    var $addPrimaryContactConfirm = $addPrimaryContactModal.find('.btn-primarycontact');

    var addPrimaryContactValidator = new CrateIt.Validation.FormValidator($addPrimaryContactModal);
    if (templateVars['validate_primary_contact_name']) {
        addPrimaryContactValidator.addValidator($('#add-primarycontact-name'), new CrateIt.Validation.RequiredValidator('Name'));
        addPrimaryContactValidator.addValidator($('#add-primarycontact-name'), new CrateIt.Validation.MaxLengthValidator('Name', 256));
    }

    if (templateVars['validate_primary_contact_email']) {
        addPrimaryContactValidator.addValidator($('#add-primarycontact-email'), new CrateIt.Validation.RequiredValidator('Email'));
        addPrimaryContactValidator.addValidator($('#add-primarycontact-email'), new CrateIt.Validation.MaxLengthValidator('Email', 128));
        addPrimaryContactValidator.addValidator($('#add-primarycontact-email'), new CrateIt.Validation.EmailValidator());
    }

    var addPrimaryContactUrlValidator = new CrateIt.Validation.UrlValidator();
    if (templateVars['validate_primary_contact_url']) {
        addPrimaryContactValidator.addValidator($('#add-primarycontact-identifier'), new CrateIt.Validation.RequiredValidator('Identifier'));
        addPrimaryContactValidator.addValidator($('#add-primarycontact-identifier'), new CrateIt.Validation.MaxLengthValidator('Identifier', 2000));
        addPrimaryContactValidator.addValidator($('#add-primarycontact-identifier'), new CrateIt.Validation.OptionalValidator(addPrimaryContactUrlValidator));
    }

    if (! templateVars['validate_primary_contact_name'] && ! templateVars['validate_primary_contact_email'] && ! templateVars['validate_primary_contact_url']) {
        $('#addPrimaryContactModal .modal-footer .btn-primary, #addPrimaryContactsModal .modal-footer .btn-primary').removeAttr('disabled').addClass('noval');
    }

    // TODO: this doesn't need to be dynamically attached, maybe create a second helper
    $('#add-primarycontact').click(function() {
        attachModalHandlers($addPrimaryContactModal, addPrimaryContact);
    });

    var activityDefinition = {
        manifestField: 'activities',
        actions: {
            search: 'activities'
        },
        mapping: {
            'id': 'id',
            'identifier': 'dc_identifier',
            'title': 'dc_title',
            'date': 'dc_date',
            'institution': 'foaf_name',
            'grant_number': 'grant_number',
            'date_submitted': 'dc_date_submitted',
            'description' : 'dc_description',
            'contributors' :  'dc_contributor',
            'repository_name' : 'repository_name',
            'repository_type' : 'repository_type',
            'oai_set' : 'oai_set',
            'format' : 'dc_format',
            'display_type' : 'display_type',
            'subject' : 'dc_subject'
        },
        displayFields: ['grant_number', 'date', 'title'],
        editFields: ['grant_number', 'date', 'title', 'institution'],
        editableRecords: ['manual']
    };

    var activitySelectedList = manifest.activities;
    var activity$resultsUl = $('#search_activity_results');
    var activity$selectedUl = $('#selected_activities');
    var activity$notification = $('#activities_search_notification');
    var activity$editModal = $('#editActivitiesModal');

    var editActivityValidator = new CrateIt.Validation.FormValidator(activity$editModal);
    if (templateVars['validate_grants_number']) {
        editActivityValidator.addValidator($('#edit-activities-grant_number'), new CrateIt.Validation.RequiredValidator('Grant number'));
        editActivityValidator.addValidator($('#edit-activities-grant_number'), new CrateIt.Validation.MaxLengthValidator('Grant number', 256));
    }

    if (templateVars['validate_grants_year']) {
        editActivityValidator.addValidator($('#edit-activities-date'), new CrateIt.Validation.RequiredValidator('Year'));
        editActivityValidator.addValidator($('#edit-activities-date'), new CrateIt.Validation.YearValidator());
    }

    if (templateVars['validate_grants_institution']) {
        editActivityValidator.addValidator($('#edit-activities-institution'), new CrateIt.Validation.RequiredValidator('Institution'));
        editActivityValidator.addValidator($('#edit-activities-institution'), new CrateIt.Validation.MaxLengthValidator('Institution', 256));
    }

    if (templateVars['validate_grants_title']) {
        editActivityValidator.addValidator($('#edit-activities-title'), new CrateIt.Validation.RequiredValidator('Title'));
        editActivityValidator.addValidator($('#edit-activities-title'), new CrateIt.Validation.MaxLengthValidator('Title', 256));
    }

    // TODO: add this to a namespace rather than exposing globally
    ActivitySearchManager = new SearchManager(activityDefinition, activitySelectedList, activity$resultsUl, activity$selectedUl, activity$notification, activity$editModal);

    $('#search_activity').click(function() {
        ActivitySearchManager.search($.trim($('#keyword_activity').val()));
    });

    $('#keyword_activity').keyup(function(e) {
        if (e.keyCode == 13) {
            ActivitySearchManager.search($.trim($(this).val()));
        }
    });

    var activitiesSelectedCount = function(e) {
        $('#activities_count').text(e.selected);
    };

    ActivitySearchManager.addEventListener(activitiesSelectedCount);
    ActivitySearchManager.notifyListeners();
    $('#clear_grant_numbers').click(function() {
        $('#clearMetadataField').text('Grants');
        attachModalHandlers($clearMetadataModal, ActivitySearchManager.clearSelected);
    });

    var addActivity = function() {
        var grant_number = $('#add-grant-number').val();
        var date = $('#add-grant-year').val();
        var title = $('#add-grant-title').val();
        var institution = $('#add-grant-institution').val();
        var overrides = {
            'grant_number': grant_number,
            'date': date,
            'title': title,
            'institution': institution
        };
        ActivitySearchManager.addRecord(overrides);
    };

    // TODO: Naming inconsistency here between 'grants' and activities
    var $addActivityModal = $('#addGrantModal');

    var addGrantValidator = new CrateIt.Validation.FormValidator($addActivityModal);
    if (templateVars['validate_grants_number']) {
        addGrantValidator.addValidator($('#add-grant-number'), new CrateIt.Validation.RequiredValidator('Grant number'));
        addGrantValidator.addValidator($('#add-grant-number'), new CrateIt.Validation.MaxLengthValidator('Grant number', 256));
    }

    if (templateVars['validate_grants_year']) {
        addGrantValidator.addValidator($('#add-grant-year'), new CrateIt.Validation.RequiredValidator('Year'));
        addGrantValidator.addValidator($('#add-grant-year'), new CrateIt.Validation.YearValidator());
    }

    if (templateVars['validate_grants_institution']) {
        addGrantValidator.addValidator($('#add-grant-institution'), new CrateIt.Validation.RequiredValidator('Institution'));
        addGrantValidator.addValidator($('#add-grant-institution'), new CrateIt.Validation.MaxLengthValidator('Institution', 256));
    }

    if (templateVars['validate_grants_title']) {
        addGrantValidator.addValidator($('#add-grant-title'), new CrateIt.Validation.RequiredValidator('Title'));
        addGrantValidator.addValidator($('#add-grant-title'), new CrateIt.Validation.MaxLengthValidator('Title', 256));
    }

    if (! templateVars['validate_grants_number'] && ! templateVars['validate_grants_year'] && ! templateVars['validate_grants_institution'] && ! templateVars['validate_grants_title']) {
        $('#addGrantModal .modal-footer .btn-primary, #editActivitiesModal .modal-footer .btn-primary').removeAttr('disabled').addClass('noval');
    }

    $('#add-activity').click(function() {
        attachModalHandlers($addActivityModal, addActivity);
    });

    var forDefinition = {
        manifestField: 'fors',
        actions: {
            search: 'FOR'
        },
        mapping: {
            'id': 'skos:prefLabel',
            'title': 'skos:prefLabel'
        },
        displayFields: ['title'],
        editFields: ['title'],
        editableRecords: ['manual', 'mint']
    };

    var forSelectedList = manifest.fors;
    var for$resultsUl = $('#search_for_results');
    var for$selectedUl = $('#selected_fors');
    var for$notification = $('#for_search_notification');
    var for$editModal = $('#editForsModal');

    var editForsValidator = new CrateIt.Validation.FormValidator(for$editModal);
    if (templateVars['validate_for_title']) {
        editForsValidator.addValidator($('#edit-fors-title'), new CrateIt.Validation.RequiredValidator('Title'));
        editForsValidator.addValidator($('#edit-fors-title'), new CrateIt.Validation.MaxLengthValidator('Title', 256));
    }

    // TODO: add this to a namespace rather than exposing globally
    ForSearchManager = new SearchManager(forDefinition, forSelectedList, for$resultsUl, for$selectedUl, for$notification, for$editModal);

    $('#search_for').click(function() {
        ForSearchManager.search($.trim($('#keyword_for').val()));
    });

    $('#keyword_for').keyup(function(e) {
        if (e.keyCode == 13) {
            ForSearchManager.search($.trim($(this).val()));
        }
    });

    var forsSelectedCount = function(e) {
        $('#fors_count').text(e.selected);
    };

    ForSearchManager.addEventListener(forsSelectedCount);
    ForSearchManager.notifyListeners();

    $('#clear_fors').click(function() {
        $('#clearMetadataField').text('fors');
        attachModalHandlers($clearMetadataModal, ForSearchManager.clearSelected);
    });

    var addFor = function() {
        var title = $('#add-for-title').val();
        var overrides = {
            'title': title
        };
        ForSearchManager.addRecord(overrides);
    };

    var $addForModal = $('#addForModal');

    var addForValidator = new CrateIt.Validation.FormValidator($addForModal);
    if (templateVars['validate_for_title']) {
        addForValidator.addValidator($('#add-for-title'), new CrateIt.Validation.RequiredValidator('Title'));
        addForValidator.addValidator($('#add-for-title'), new CrateIt.Validation.MaxLengthValidator('Title', 256));
    }

    if (! templateVars['validate_for_title']) {
        $('#addForModal .modal-footer .btn-primary, #editForsModal .modal-footer .btn-primary').removeAttr('disabled').addClass('noval');
    }

    $('#add-for').click(function() {
        attachModalHandlers($addForModal, addFor);
    });
}

function initAutoResizeMetadataTabs() {
    $('#meta-data').on('show.bs.collapse', function(e) {
        $(e.target).siblings('.panel-heading').find('.fa').removeClass('fa-caret-down').addClass('fa-caret-up');
        calulateHeights();
    });

    $('#meta-data').on('hide.bs.collapse', function(e) {
        $(e.target).siblings('.panel-heading').find('.fa').removeClass('fa-caret-up').addClass('fa-caret-down');
        calulateHeights();
    });

    $(window).resize(function() {
        calulateHeights();
    });
}
