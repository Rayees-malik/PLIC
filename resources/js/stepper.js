$.fn.extend({
  trackChanges: function () {
    $(this).on("change", ":input", function () {
      $(this.form).data("changed", true);
    });
  },
  isChanged: function () {
    return $(this).data("changed");
  },
  resetChanges: function () {
    $(this).data("changed", false);
  },
  forceChange: function () {
    $(this).data("changed", true);
  }
});

$(function () {
  createFormStepper(".form-stepper");
  $(".ajax-loader").fadeOut(250);
});

function reviewToggle() {
  $(this).toggleClass("open").next().find('.review-content').slideToggle(250);
}

function createFormStepper(selector) {
  let $stepper;
  let $dots;
  let $prevCtrl;
  let $nextCtrl;
  let $submitCtrl;
  let $form;

  let step = 0;
  let saveRoute = "";

  let config = {
    min: 0,
    max: 0
  };

  init();

  // set everything up
  function init() {
    if (typeof selector !== "string") {
      throw new Error(
        `[stepper.js] Selector must be a valid css selector. Received: ${typeof selector}`
      );
    }

    $stepper = $(selector);
    $dots = $stepper.find(".js-stepper-dot");
    $prevCtrl = $stepper.find(".js-prev").first();
    $nextCtrl = $stepper.find(".js-next").first();
    $submitCtrl = $stepper.find(".js-submit").first();

    saveRoute = $(".stepper-dot-wrap").data("route");
    if (!saveRoute) {
      throw new Error(
        "[stepper.js] No route set for saving model changes."
      );
    }

    $form = $stepper.find("form").first();

    // When stepper is in a signoff it doesn't have its own
    // form, look for closest parent
    if (!$form.length) $form = $stepper.closest("form");
    $form.trackChanges();
    window.stepperForm = $form;

    // bind event listners
    $stepper.on("click", ".js-stepper-button", moveStep);
    $stepper.on("click", ".js-stepper-dot", jumpToStep);
    $stepper.on("click", ".js-review-toggle", reviewToggle);

    // set max number of steps
    config.max = $stepper.find(".js-stepper-step").length - 1;
    render();
  }

  function isStepVisible(step) {
    return $(`.js-stepper-dot[data-step="${step}"]`).is(':visible');
  }

  // goes to PREV/NEXT step
  function moveStep() {
    let nextStep = step;
    do {
      nextStep = nextStep + ($(this).data("dir") === "next" ? 1 : -1);
    } while (!isStepVisible(nextStep) && nextStep > config.min && nextStep < config.max);
    setStep(nextStep);
  }

  // jumps to selected step
  function jumpToStep() {
    let nextStep = $(this).data("step");
    while (!isStepVisible(nextStep) && nextStep > config.min && nextStep < config.max) {
      nextStep++;
    }

    setStep(nextStep);
  }

  function setStep(newStep) {
    const prevStep = step;
    step = _.clamp(newStep, config.min, config.max);

    if (prevStep !== step) {
      updateModel();
      window.scrollTo(0, 0);
    }
  }

  // updates DOM
  function render(data) {
    // check for forced step changed
    if (data && data.step !== null) {
      step = data.step;
    }

    // hide next crtl and show submit button in last step
    if (step === config.max) {
      $submitCtrl.css("display", "inline-flex");
      $nextCtrl.css("display", "none");
    } else {
      $submitCtrl.css("display", "none");
      $nextCtrl.css("display", "inline-flex");
    }

    if (step === config.min) {
      $prevCtrl.css({ "opacity": "0.4", "pointer-events": "none" });
    } else if (step > config.min) {
      $prevCtrl.css({ "opacity": "1", "pointer-events": "all" });
    }

    if (data && data.view) {
      $newView = $($.parseHTML(data.view));
      $newView.find(".js-stepper-step").each(function () {
        $(`#${$(this).attr("id")}`).replaceWith($(this));

        if (typeof $(this).data('hidden') !== 'undefined') {
          const dot = `.js-stepper-dot[data-step="${$(this).data('step')}"]`;
          $(this).data('hidden') ? $(dot).hide() : $(dot).show();
        }
      });

      // call custom onRender functions (from stepper)
      if (typeof window.onRender === "function") window.onRender();
      // Automatically re-initialize any chosenselect and uploaders.
      if (window.chosenSelectLoaders) window.chosenSelectLoaders.forEach(loader => loader());
      if (window.uploaderSetups) window.uploaderSetups.forEach(uploader => uploader());
      // Reinit datepickers
      flatpickr($(".js-datepicker"));
      flatpickr($(".js-datepicker-range"), { mode: "range" });

      window.showChanges(); // Show model changes (if applicable)
      window.activatePopovers(); // Activate popovers (must be after showChanges())
    }

    $steps = $stepper.find(".js-stepper-step");
    $steps.removeClass("active");
    $steps.eq(step).addClass("active");

    $dots.removeClass("active").removeClass("done");
    $dots.eq(step).addClass("active");
    if (step > 0) $dots.slice(0, step).addClass("done");

    if (!isStepVisible(step))
      setStep(step + 1);
  }

  function updateModel() {
    if ($form.isChanged() || step === config.max) {
      const data = $form.serialize();
      $(".ajax-loader").fadeIn(250);
      return axios
        .post(saveRoute, data)
        .then(function (response) {
          $form.resetChanges();
          render(response.data);
        })
        .catch(function (error) {
          console.error(error);
          if (error.response.data.error.statusCode === 400) window.location.href = "/";
        })
        .finally(function () {
          $(".ajax-loader").fadeOut(250);
        });
    }

    render();
  }

  window.updateStepperModel = updateModel;
}