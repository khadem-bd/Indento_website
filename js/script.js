$(document).ready(function () {
    feather.replace();

    $("#demoRequestPopup").click(function () {
        $(".modal-wrapper").addClass("show");
    })

    $("#cancelDemo").click(function () {
        $(".modal-wrapper").removeClass("show");
        setTimeout(function () {
            $("#demoRequest")[0].reset();
            location.reload();
        }, 1000);
    })


    $("form#demoRequest").submit(function (event) {
        event.preventDefault();
        ajaxForm("#demoRequest", "controllers/demo_request/process_demo_request.php", "Demo request send successfully, we will get in touch with you shortly", "#demoRequestSuccessText", "#demoRequestSuccess", "#demoRequestErrorText", "#demoRequestError", "refresh", "");
    });

    $("form#subscriberForm").submit(function (event) {
        event.preventDefault();
        ajaxForm("#subscriberForm", "controllers/subscribers/add_subscriber.php", "Congratulations, you are now our subscriber", "#subscriberFormSuccessText", "#subscriberFormSuccess", "#subscriberFormErrorText", "#subscriberFormError", "refresh", "");
    });

    $("form#sendMessage").submit(function (event) {
        event.preventDefault();
        ajaxForm("#sendMessage", "controllers/contact_us/send_message.php", "Message send successfully", "#sendMessageSuccessText", "#sendMessageSuccess", "#sendMessageErrorText", "#sendMessageError", "refresh", "");
    });

    $(".navigation li a").click(function (event) {
        event.preventDefault();
        const target = $(this).attr("href");
        var pos = $(target).position();
        var top = pos.top;
        if (target == "#heroSection") {
            $('html, body').animate({
                scrollTop: 0
            }, 500);
        } else {
            $('html, body').animate({
                scrollTop: top
            }, 500);
        }

    })

    $(".hamburgerMenu").click(function () {
        $(".nav-wrapper").addClass("show");
    })

    $(".closeMobleMenu").click(function () {
        $(".nav-wrapper").removeClass("show");
    })

    $('#testimonialSlider').slick({
        dots: false,
        infinite: true,
        arrows: false,
        autoplay: true,
        autoplaySpeed: 2000,
        speed: 300,
    });
})