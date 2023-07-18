// ACCORDION TOGGLE
// EXPLANATION
// element = ".accordion-wrap"
// element.children[0] = ".accordion-header"
// element.children[1] = ".accordion-body"
// element.children[1].children[0] = ".accordion-body-content"

$(function () {
    $(document).on('keydown', ':input:not(textarea):not(:submit)', function (event) {
        if (event.key == "Enter" && !$(this).hasClass('js-allow-submit')) {
            event.preventDefault();
        }
    });

    $(document).on('submit', 'form', function (e) {
        if (this.hasAttribute('submitting')) {
            e.preventDefault();
            return;
        }

        this.setAttribute('submitting', true);;
        setTimeout(function () { this.removeAttribute('submitting'); }.bind(this), 5000);
    });

    $(".accordion-open").accordion({
        active: 0,
        collapsible: true,
        heightStyle: "content"
    });

    $(".accordion").accordion({
        active: false,
        collapsible: true,
        heightStyle: "content"
    });

    $('body').on('click', '.accordion-header', function () {
        window.toggleAcc($(this).parent());
    });

    $('body').on('click', '.js-bigpicture', function (e) {
        window.BigPicture({
            el: e.target,
            imgSrc: $(this).data('bp'),
        });
    });

    function toggleOpenClass(elem) {
        const elemClasses = document.getElementById(elem).classList;
        const isElemOpen = elemClasses.contains(elem + "--open");

        if (isElemOpen) {
            elemClasses.remove(elem + "--open");
        } else {
            elemClasses.add(elem + "--open");
        }
    }

    $("#open-side-menu")
        .add("#header-backdrop")
        .on('click', function () {
            toggleOpenClass("side-nav-wrap");
            toggleOpenClass("header-backdrop");
        });

    $("#open-mobile-search")
        .add("#close-mobile-search")
        .on('click', function () {
            toggleOpenClass("search-wrap");
            toggleOpenClass("header-backdrop");
        });

    $("#open-mobile-notifications")
        .add("#close-mobile-notifications")
        .on('click', function () {
            toggleOpenClass("notifications-wrap");
            toggleOpenClass("header-backdrop");
        });

    // IN THE CASE OF ACCOUNT DROPDOWN WE ARE ADDING STYLE JUST ON MOBILE TO OPEN ACCORDION IN SIDE MENU
    // ON DESKTOP WE CHANGE STYLE ON HOVER AND THIS FUNCTION DOESNT AFFECT VISUAL STYLE
    $("#account-btn").on('click', function () {
        toggleOpenClass("account-controls-wrap");
    });

    $(".js-nav-linkgroup-btn").on('click', function () {
        const elemClasses = this.parentElement.classList;
        const isElemOpen = elemClasses.contains("nav-linkgroup-wrap--open");

        if (isElemOpen) {
            elemClasses.remove("nav-linkgroup-wrap--open");
        } else {
            elemClasses.add("nav-linkgroup-wrap--open");
        }
    });

    // MOVE SEARCH WRAP AND NOTIFICATIONS TO HEADER IF ON MOBILE
    // MOVE USER DROPDOWN TO THE SIDE MENU WHEN ON MOBILE
    const windowWidth = window.innerWidth;
    const searchWrap = document.getElementById("search-wrap");
    const notificationsWrap = document.getElementById("notifications-wrap");
    const accountControlsWrap = document.getElementById("account-controls-wrap");

    if (windowWidth < 1200 && searchWrap && notificationsWrap) {
        const header = document.getElementById("header");
        const sideNav = document.getElementById("side-nav-wrap");

        header.appendChild(searchWrap);
        header.appendChild(notificationsWrap);
        sideNav.prepend(accountControlsWrap);
    }

    flatpickr($(".js-datepicker"));
    flatpickr($(".js-datepicker-range"), { mode: "range" });

    window.showChanges(); // Show model changes (if applicable)
    window.activatePopovers(); // Activate popovers (must be after showChanges())

    // DataTables Scroll Fix
    $('body').on('page.dt', '.dataTable', function () {
        $('html, body').animate({ scrollTop: $(".dataTables_wrapper").offset().top }, 'slow');
    });
});

window.toggleAcc = function ($accordion) {
    $body = $accordion.find('.accordion-body').first();
    if ($accordion.hasClass('accordion-closed')) {
        $body.animate({ height: $body.children().first().height() }, 100, 'linear');
        $accordion.removeClass('accordion-closed');
    } else {
        $accordion.find('.accordion-body').animate({ height: 0 }, 100, 'linear');
        $accordion.addClass('accordion-closed');
    }
}

window.openAcc = function ($accordion) {
    if ($accordion.hasClass('accordion-closed'))
        window.toggleAcc($accordion);
}

window.closeAcc = function ($accordion) {
    if (!$accordion.hasClass('accordion-closed'))
        window.toggleAcc($accordion);
}

window.activatePopovers = function () {
    $('[data-toggle="popover"]').popover({
        trigger: 'hover',
        container: 'body',
        html: true,
        content: function () {
            const target = $(this).data('content-target');
            if (target)
                return $(target).html();
        }
    });
}

window.showChanges = function () {
    if (typeof modelChanges === 'object') {
        for (const [key, data] of Object.entries(modelChanges)) {

            const $anchor = getChangeAnchor(key, data);
            if (!$anchor || !$anchor.length) {
                setTimeout(function () {
                    addTooltip(getChangeAnchor(key, data), key);
                    window.activatePopovers();
                }, 1000);
                continue;
            }

            addTooltip($anchor, key);
        }
    }
}

function addTooltip($anchor, key) {
    if (!$anchor || !$anchor.length)
        return;

    const html = `<div class="tooltip-wrap"><div class="tooltip-icon" data-toggle="popover" title="Change History" data-content-target=".js-change-${key}"><i class="material-icons warning-message">history</i></div></div>`;

    if ($anchor.is(':checkbox') || $anchor.is(':radio')) {
        $anchor.closest('label').after(html);
    } else if ($anchor.is('textarea')) {
        $anchor.closest('div.input-wrap').addClass('input-warning');
        $anchor.before(html);
    } else if ($anchor.is('select')) {
        $anchor.closest('div.dropdown-wrap').addClass('dropdown-warning');
        $anchor.closest('div').before(html);
    } else if ($anchor.is('input')) {
        $anchor.closest('div.input-wrap').addClass('input-warning');
        if ($anchor.hasClass('js-anchor-append'))
            $anchor.closest('div.input-wrap').append(html);
        else
            $anchor.closest('div.input-wrap').append(html);
    } else {
        $anchor.after(html);
    }
}

function getChangeAnchor(key, data) {
    switch (data.type) {
        case 'pivot':
            return $(`[name="${data.property}[]"][value="${data.id}"]`).not('.no-history').first();
        case 'pivot_data':
            return $(`[name="${data.property}[${data.id}]"]`).not('.no-history').first();
        case 'contact':
            $idInput = $(`[name^="contact-id["][value="${data.id}"]`).not('.no-history').first();
            if (!$idInput.length) break;
            if (data.property === 'contact-deleted') {
                return $idInput.parent().find('button').not('.no-history').first();
            } else {
                const target = $idInput.attr("name").replace('contact-id', data.property)
                return $(`[name="${target}"]`).not('.no-history').first();
            }
        case 'media':
            return $(`.js-media-${data.id}`).not('.no-history').first();
        case 'concat':
            return $(`[name="${key}[]`).not('.no-history').first();
        case 'child_concat':
            return $(`[name="${data.property}[${data.id}][]`).not('.no-history').first().closest('div.input-wrap');
        case 'lineitem':
            return $(`.js-id[value="${data.id}"]`).not('.no-history').first().closest('tr').find(`[name="${data.property}[]`).not('.no-history').first();
    }

    return $(`[name="${key}"]`).not('.no-history').first();
}

window.restoreMedia = function () {
    const $input = $(`.js-${$(this).data('type')}-uploader[name^="${$(this).data('collection')}"]`).first();
    if (!$input.length)
        return false;

    const api = $.fileuploader.getInstance($input);

    if (api.getOptions().limit && (api.getChoosedFiles().length + api.getAppendedFiles().length) < Number(api.getOptions().limit)) {
        $(`<input type="hidden" name="media-deleted[${$(this).data('file-id')}]" value="0" />`).insertBefore(this);
        window.stepperForm.forceChange();
        window.updateStepperModel();
    } else {
        alert('Maximum number of files for this category has already been reached. Please delete one first.');
    }
}

$.fn.values = function (data) {
    var els = this.find(':input').get();

    if (arguments.length === 0) {
        // return all data
        data = {};

        $.each(els, function () {
            if (this.name && !this.disabled && (this.checked ||
                /select|textarea/i.test(this.nodeName) ||
                /number|email|text|hidden|password/i.test(this.type))) {
                if (data[this.name] == undefined) {
                    data[this.name] = [];
                }
                data[this.name].push($(this).val());
            }
        });
        return data;
    } else {
        $.each(els, function () {
            if (this.name && data[this.name]) {
                var names = data[this.name];
                var $this = $(this);
                if (Object.prototype.toString.call(names) !== '[object Array]') {
                    names = [names]; //backwards compat to old version of this code
                }
                if (this.type == 'checkbox' || this.type == 'radio') {
                    var val = $this.val();
                    var found = false;
                    for (var i = 0; i < names.length; i++) {
                        if (names[i] == val) {
                            found = true;
                            break;
                        }
                    }
                    $this.attr("checked", found);
                } else {
                    $this.val(names[0]);
                }
            }
        });
        return this;
    }
};
