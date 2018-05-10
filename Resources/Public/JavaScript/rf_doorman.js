define([
    'jquery',
    'rfdoorman'
], function ($, rfdoorman) {
    'use strict';

    Ext.ns('TYPO3');
    collectDoormanData();

    $('.doorman .btn').on('click', function () {
        sendHandle($(this).data('type'), $(this).closest('li.userrow').data('user'), $(this).closest('li.userrow').data('type'));
    });

    function sendHandle(type, user, usertype) {
        TYPO3.jQuery.ajax({
            method: "POST",
            url: TYPO3.settings.ajaxUrls['handleDoorman'],
            data: {
                handleType: type,
                user: user,
                usertype: usertype
            },
            dataType: "json"
        }).done(function (result) {
            $('li[data-user="' + user + '"]').addClass('removed');
            $('#t3js-doorman-counter').text($('#t3js-doorman-counter').text()-1);
        }).error(function (result) {
            window.console && console.error('error', result);
        });
        
    }

    function collectDoormanData() {
        var collect = $('.dropdown-list.doorman').data('fecount') +  $('.dropdown-list.doorman').data('becount');
        $('#t3js-doorman-counter').text(collect).fadeIn();
    }

});