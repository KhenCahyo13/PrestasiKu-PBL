$(document).ready(function() {
    // Action buttons
    const nextButton = $('#nextButton');
    const previousButton = $('#previousButton');
    const submitButton = $('#submitButton');
    // Steps
    const accountStep = $('#accountStep');
    const personalInformationStep = $('#personalInformationStep');
    const successfulStep = $('#successfulStep');
    // Circle Steppers
    const accountStepper = $('#accountStepper');
    const personalInformationStepper = $('#personalInformationStepper');
    const successStepper = $('#successStepper');
    // Circle Steppers Title
    const personalInformationTitle = $('#personalInformationTitle');
    const successTitle = $('#successTitle');
    // Stepper Lines
    const stepperLine1 = $('#stepperLine1');
    const stepperLine2 = $('#stepperLine2');
    // Others Element
    const loginElement = $('#loginElement');

    nextButton.on('click', function() {
        accountStep.addClass('d-none');
        accountStepper.addClass('stepper-completed');
        accountStepper.html('<i class="fi fi-br-check text-white" style="line-height: 0;"></i>');
        personalInformationStep.removeClass('d-none');
        personalInformationStepper.removeClass('text-secondary');
        personalInformationStepper.addClass('stepper-active');
        personalInformationStepper.addClass('text-primary');
        personalInformationTitle.removeClass('text-secondary');
        personalInformationTitle.addClass('text-primary');
        stepperLine1.addClass('stepper-line-active');
    });

    previousButton.on('click', function() {
        accountStep.removeClass('d-none');
        accountStepper.html('1');
        accountStepper.removeClass('stepper-completed');
        personalInformationStep.addClass('d-none');
        personalInformationStepper.addClass('text-secondary');
        personalInformationStepper.removeClass('stepper-active');
        personalInformationStepper.removeClass('text-primary');
        personalInformationTitle.addClass('text-secondary');
        personalInformationTitle.removeClass('text-primary');
    });

    submitButton.on('click', function() {
        personalInformationStep.addClass('d-none');
        personalInformationStepper.addClass('stepper-completed');
        personalInformationStepper.html('<i class="fi fi-br-check text-white" style="line-height: 0;"></i>');
        successfulStep.removeClass('d-none');
        successStepper.removeClass('text-secondary');
        successStepper.addClass('stepper-completed');
        successStepper.html('<i class="fi fi-br-check text-white" style="line-height: 0;"></i>');
        successTitle.addClass('text-primary');
        loginElement.addClass('d-none');
        stepperLine2.addClass('stepper-line-active');
    });
});