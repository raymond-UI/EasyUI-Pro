// custom-admin-script.js

jQuery(document).ready(function ($) {
    // Object to store previous color values
    var previousColors = {
        primary: '',
        secondary: '',
        tertiary: ''
    };

    // Function to update color picker values
    function updateColorPicker(baseColor) {
        // Get the new color value from the CSS variable
        var newColor = getComputedStyle(document.documentElement).getPropertyValue('--' + baseColor + '_color_light_15').trim();

        // If the new color is not white, update the color picker value
        if (newColor !== '#ffffff') {
            // Select the color picker input element for the specified base color
            var colorPickerInput = $('input[name=' + baseColor + '_base_color]');

            // Update the color picker value
            colorPickerInput.val(newColor).trigger('input');

            // Update the previous color value
            previousColors[baseColor] = newColor;
        }
    }

    // Function to initialize color picker values
    function initializeColorPickers() {
        updateColorPicker('primary');
        updateColorPicker('secondary');
        updateColorPicker('tertiary');
    }

    // Call the function to initialize color pickers
    initializeColorPickers();

    // Event listener for color picker changes
    $('input[type="color"]').on('input', function () {
        // Get the base color from the color picker name
        var baseColor = $(this).attr('name').replace('_base_color', '');

        // Get the new color value from the color picker
        var newColor = $(this).val();

        // Update the color pickers for other base colors with previous values
        Object.keys(previousColors).forEach(function (color) {
            if (color !== baseColor) {
                var colorPickerInput = $('input[name=' + color + '_base_color]');
                colorPickerInput.val(previousColors[color]).trigger('input');
            }
        });

        // Update the color pickers for the current base color with the new value
        updateColorPicker(baseColor);
    });
});
