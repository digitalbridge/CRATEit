/**
 * ownCloud - Cr8it App
 *
 * @author Lloyd Harischandra
 * @copyright 2014 University of Western Sydney www.uws.edu.au
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

function getFileName(dir, filename) {
    var baseUrl = '';
    if (dir === '/') {
        baseUrl = filename;
    } else {
        baseUrl = dir.replace(/^\//g, '') + '/' + filename;
    }
    return baseUrl;
}

$(document).ready(function() {
    var selected_crate = function() {
        var selected_crate ='';
        var c_url = OC.generateUrl('apps/crate_it/crate/get_crate_name');
        $.ajax({
            async: false,
            url: c_url,
            type: 'get',
            dataType: 'json',
            success: function (data) {
                selected_crate = 'Selected Crate: '+data;
            },
            error: function(jqXHR) {
                selected_crate = jqXHR.responseJSON.msg;
            }
        });
       return selected_crate;
    };

    if (location.pathname.indexOf("files") != -1) {
        $('body').append(
            '<div class="modal" id="addingToCrateModal" tabindex="-1" role="dialog" aria-labelledby="addingToCrateModalLabel" aria-hidden="true">' +
                '<div class="modal-dialog">' +
                    '<div class="modal-content">' +
                        '<div class="modal-header">' +
                            '<h4 class="modal-title" id="addingToCrateModalLabel">Adding file(s) to crate...</h4>' +
                        '</div>' +
                        '<div class="modal-body" style="text-align: center">' +
                            '<img class="center-block" src="/owncloud/apps/crate_it/img/ajax-spinner-loader.gif" style="width: 50px">' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>');
        if (typeof FileActions !== 'undefined') {
            FileActions.register('all', 'Add to crate', OC.PERMISSION_READ, '', function(filename) {
                $('div#addingToCrateModal').modal();
                var payload = {
                    file: getFileName($('#dir').val(), filename)
                };
                var c_url = OC.generateUrl('apps/crate_it/crate/add');
                $.ajax({
                    url: c_url,
                    type: 'post',
                    dataType: 'json',
                    data: payload,
                    async: true,
                    complete: function(jqXHR) {
                        $('div#addingToCrateModal').modal('hide');
                        OC.Notification.show(jqXHR.responseJSON.msg);
                        setTimeout(function() {
                            OC.Notification.hide();
                        }, 3000);
                    }
                });
            });
        }
        $('div#controls').append('<div id="selectedCrate" class="">'+selected_crate()+'</div>');
    } else if (location.pathname.indexOf("crate_it") != -1) {
        loadTemplateVars();
        drawCrateContents();
        initCrateActions();
        updateCrateSize();
        setupDescriptionOps();
        setupRetentionPeriodOps();
        setupEmbargoDetailsOps();
        initSearchHandlers();
        initAutoResizeMetadataTabs();
    }
});